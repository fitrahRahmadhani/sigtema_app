<?php

namespace App\Livewire\Pages\Burnouts;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Burnout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncBurnoutsJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.app')]
#[Title('Employee Burnout')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $resultFilter = '';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $polling = false;

    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy', 'resultFilter'])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('Burnout sync is already in progress. Please wait.');
                return;
            }

            SyncBurnoutsJob::dispatch(Auth::id())->onQueue('default');

            $this->syncStatus = 'queued';
            Cache::put("burnout_sync_status_" . Auth::id(), 'queued', 3600);
            $this->startPolling();

            Toaster::success('Burnout sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue burnout sync: ' . $e->getMessage());
        }
    }

    private function getSyncStatus(): string
    {
        $status = Cache::get("burnout_sync_status_" . Auth::id(), 'idle');
        $lastResult = Cache::get("last_burnout_sync_result_" . Auth::id());

        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    private function startPolling(): void
    {
        $this->polling = true;
        Cache::put("burnout_polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        $startTime = Cache::get("burnout_polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("burnout_polling_start_" . Auth::id());
            return;
        }

        $this->syncStatus = $this->getSyncStatus();
        $this->lastSyncResult = Cache::get("last_burnout_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("burnout_polling_start_" . Auth::id());

            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'Burnout sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));
                $this->dispatch('refresh-data');
            }
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("burnout_sync_status_" . Auth::id());
        Cache::forget("last_burnout_sync_result_" . Auth::id());
        Cache::forget("burnout_polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('Burnout sync status cleared');
    }

    public function render()
    {
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_burnout_sync_result_" . Auth::id());
        }

        $burnouts = Burnout::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('result', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->resultFilter, fn($q) => $q->where('result', $this->resultFilter))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'ee_high', fn($q) => $q->orderBy('emotional_exhaustion', 'desc'))
            ->when($this->sortBy === 'ee_low', fn($q) => $q->orderBy('emotional_exhaustion', 'asc'))
            ->when($this->sortBy === 'dp_high', fn($q) => $q->orderBy('depersonalization', 'desc'))
            ->when($this->sortBy === 'dp_low', fn($q) => $q->orderBy('depersonalization', 'asc'))
            ->when($this->sortBy === 'ad_high', fn($q) => $q->orderBy('achievement_decline', 'desc'))
            ->when($this->sortBy === 'ad_low', fn($q) => $q->orderBy('achievement_decline', 'asc'))
            ->when($this->sortBy === 'result_asc', fn($q) => $q->orderBy('result', 'asc'))
            ->when($this->sortBy === 'result_desc', fn($q) => $q->orderBy('result', 'desc'))
            ->paginate(10);

        $results = Burnout::whereNotNull('result')->distinct()->pluck('result')->sort();

        return view('livewire.pages.burnouts.index', compact('burnouts', 'results'));
    }
}
