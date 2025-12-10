<?php

namespace App\Livewire\Pages\Mbtis;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Mbti;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncMbtisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.app')]
#[Title('MBTI Assessment')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $mbtiFilter = '';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $polling = false;

    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy', 'mbtiFilter'])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('MBTI sync is already in progress. Please wait.');
                return;
            }

            SyncMbtisJob::dispatch(Auth::id())->onQueue('default');

            $this->syncStatus = 'queued';
            Cache::put("mbti_sync_status_" . Auth::id(), 'queued', 3600);
            $this->startPolling();

            Toaster::success('MBTI sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue MBTI sync: ' . $e->getMessage());
        }
    }

    private function getSyncStatus(): string
    {
        $status = Cache::get("mbti_sync_status_" . Auth::id(), 'idle');
        $lastResult = Cache::get("last_mbti_sync_result_" . Auth::id());

        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    private function startPolling(): void
    {
        $this->polling = true;
        Cache::put("mbti_polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        $startTime = Cache::get("mbti_polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("mbti_polling_start_" . Auth::id());
            return;
        }

        $this->syncStatus = $this->getSyncStatus();
        $this->lastSyncResult = Cache::get("last_mbti_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("mbti_polling_start_" . Auth::id());

            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'MBTI sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));
                $this->dispatch('refresh-data');
            }
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("mbti_sync_status_" . Auth::id());
        Cache::forget("last_mbti_sync_result_" . Auth::id());
        Cache::forget("mbti_polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('MBTI sync status cleared');
    }

    public function render()
    {
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_mbti_sync_result_" . Auth::id());
        }

        $mbtis = Mbti::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('mbti', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->mbtiFilter, fn($q) => $q->where('mbti', $this->mbtiFilter))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'mbti_asc', fn($q) => $q->orderBy('mbti', 'asc'))
            ->when($this->sortBy === 'mbti_desc', fn($q) => $q->orderBy('mbti', 'desc'))
            ->paginate(10);

        $mbtiTypes = Mbti::whereNotNull('mbti')->distinct()->pluck('mbti')->sort();

        return view('livewire.pages.mbtis.index', compact('mbtis', 'mbtiTypes'));
    }
}
