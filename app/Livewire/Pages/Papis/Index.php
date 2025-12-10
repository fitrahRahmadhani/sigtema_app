<?php

namespace App\Livewire\Pages\Papis;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Papi;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncPapisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.app')]
#[Title('PAPI Assessment')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $polling = false;

    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('PAPI sync is already in progress. Please wait.');
                return;
            }

            SyncPapisJob::dispatch(Auth::id())->onQueue('default');

            $this->syncStatus = 'queued';
            Cache::put("papi_sync_status_" . Auth::id(), 'queued', 3600);
            $this->startPolling();

            Toaster::success('PAPI sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue PAPI sync: ' . $e->getMessage());
        }
    }

    private function getSyncStatus(): string
    {
        $status = Cache::get("papi_sync_status_" . Auth::id(), 'idle');
        $lastResult = Cache::get("last_papi_sync_result_" . Auth::id());

        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    private function startPolling(): void
    {
        $this->polling = true;
        Cache::put("papi_polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        $startTime = Cache::get("papi_polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("papi_polling_start_" . Auth::id());
            return;
        }

        $this->syncStatus = $this->getSyncStatus();
        $this->lastSyncResult = Cache::get("last_papi_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("papi_polling_start_" . Auth::id());

            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'PAPI sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));
                $this->dispatch('refresh-data');
            }
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("papi_sync_status_" . Auth::id());
        Cache::forget("last_papi_sync_result_" . Auth::id());
        Cache::forget("papi_polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('PAPI sync status cleared');
    }

    public function render()
    {
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_papi_sync_result_" . Auth::id());
        }

        $papis = Papi::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'N_high', fn($q) => $q->orderBy('N', 'desc'))
            ->when($this->sortBy === 'N_low', fn($q) => $q->orderBy('N', 'asc'))
            ->when($this->sortBy === 'G_high', fn($q) => $q->orderBy('G', 'desc'))
            ->when($this->sortBy === 'G_low', fn($q) => $q->orderBy('G', 'asc'))
            ->when($this->sortBy === 'A_high', fn($q) => $q->orderBy('A', 'desc'))
            ->when($this->sortBy === 'A_low', fn($q) => $q->orderBy('A', 'asc'))
            ->paginate(10);

        return view('livewire.pages.papis.index', compact('papis'));
    }
}
