<?php

namespace App\Livewire\Pages\Discs;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Disc;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncDiscsJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.app')]
#[Title('DISC Assessment')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $interpretation1Filter = '';

    #[Url]
    public $interpretation2Filter = '';

    #[Url]
    public $interpretation3Filter = '';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $polling = false;

    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy', 'interpretation1Filter', 'interpretation2Filter', 'interpretation3Filter'])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('DISC sync is already in progress. Please wait.');
                return;
            }

            SyncDiscsJob::dispatch(Auth::id())->onQueue('default');

            $this->syncStatus = 'queued';
            Cache::put("disc_sync_status_" . Auth::id(), 'queued', 3600);
            $this->startPolling();

            Toaster::success('DISC sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue DISC sync: ' . $e->getMessage());
        }
    }

    private function getSyncStatus(): string
    {
        $status = Cache::get("disc_sync_status_" . Auth::id(), 'idle');
        $lastResult = Cache::get("last_disc_sync_result_" . Auth::id());

        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    private function startPolling(): void
    {
        $this->polling = true;
        Cache::put("disc_polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        $startTime = Cache::get("disc_polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("disc_polling_start_" . Auth::id());
            return;
        }

        $this->syncStatus = $this->getSyncStatus();
        $this->lastSyncResult = Cache::get("last_disc_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("disc_polling_start_" . Auth::id());

            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'DISC sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));
                $this->dispatch('refresh-data');
            }
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("disc_sync_status_" . Auth::id());
        Cache::forget("last_disc_sync_result_" . Auth::id());
        Cache::forget("disc_polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('DISC sync status cleared');
    }

    public function render()
    {
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_disc_sync_result_" . Auth::id());
        }

        $discs = Disc::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('interpretation1', 'like', '%' . $this->search . '%')
                        ->orWhere('interpretation2', 'like', '%' . $this->search . '%')
                        ->orWhere('interpretation3', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->interpretation1Filter, fn($q) => $q->where('interpretation1', $this->interpretation1Filter))
            ->when($this->interpretation2Filter, fn($q) => $q->where('interpretation2', $this->interpretation2Filter))
            ->when($this->interpretation3Filter, fn($q) => $q->where('interpretation3', $this->interpretation3Filter))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'interpretation1_asc', fn($q) => $q->orderBy('interpretation1', 'asc'))
            ->when($this->sortBy === 'interpretation1_desc', fn($q) => $q->orderBy('interpretation1', 'desc'))
            ->when($this->sortBy === 'interpretation2_asc', fn($q) => $q->orderBy('interpretation2', 'asc'))
            ->when($this->sortBy === 'interpretation2_desc', fn($q) => $q->orderBy('interpretation2', 'desc'))
            ->when($this->sortBy === 'interpretation3_asc', fn($q) => $q->orderBy('interpretation3', 'asc'))
            ->when($this->sortBy === 'interpretation3_desc', fn($q) => $q->orderBy('interpretation3', 'desc'))
            ->paginate(10);

        $interpretation1s = Disc::whereNotNull('interpretation1')->distinct()->pluck('interpretation1')->sort();
        $interpretation2s = Disc::whereNotNull('interpretation2')->distinct()->pluck('interpretation2')->sort();
        $interpretation3s = Disc::whereNotNull('interpretation3')->distinct()->pluck('interpretation3')->sort();

        return view('livewire.pages.discs.index', compact('discs', 'interpretation1s', 'interpretation2s', 'interpretation3s'));
    }
}
