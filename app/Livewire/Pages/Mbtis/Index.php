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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('MBTI Results')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $mbtiFilter = '';

    #[Url]
    public $activeFilter = 'active';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $syncProgress = 0;
    public $syncMessage = '';
    public $syncCurrent = 0;
    public $syncTotal = 0;
    public $isPolling = false;

    public function updating($property, $value)
    {
        if (in_array($property, [
            'search',
            'sortBy',
            'mbtiFilter',
            'activeFilter'
        ])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            $rateLimitKey = 'mbti_sync_attempts_' . Auth::id();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                Toaster::warning("Too many sync attempts. Try again in {$seconds} seconds.");
                return;
            }

            $lockKey = "mbti_sync_lock_" . Auth::id();
            if (Cache::has($lockKey)) {
                Toaster::warning('Another MBTI sync is already in progress. Please wait.');
                return;
            }

            $currentStatus = Cache::get("mbti_sync_status_" . Auth::id(), 'idle');
            if ($currentStatus === 'processing' || $currentStatus === 'queued') {
                Toaster::warning('MBTI sync is already in progress. Please wait.');
                return;
            }

            SyncMbtisJob::dispatch(Auth::id())->onQueue('default');

            RateLimiter::hit($rateLimitKey, 600);

            Cache::put("mbti_sync_status_" . Auth::id(), 'queued', 3600);

            $this->syncStatus = 'queued';
            $this->syncProgress = 5;
            $this->syncMessage = 'MBTI sync job has been queued...';
            $this->isPolling = true;

            Toaster::success('MBTI sync has been queued and will run in background.');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue MBTI sync: ' . $e->getMessage());
            Log::error('MBTI sync dispatch failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function getListeners()
    {
        $userId = Auth::id();

        return [
            "echo-private:mbti-sync-status.{$userId},.mbti.sync.status.updated" => 'handleSyncStatusUpdate',
            "echo-private:mbti-sync-status.{$userId},.mbti.sync.progress.updated" => 'handleSyncProgressUpdate',
            'refreshData' => '$refresh',
            'clearSyncStatus' => 'clearSyncStatus',
        ];
    }

    public function handleSyncStatusUpdate($event)
    {
        Log::info('MBTI sync status update received', $event);

        $this->syncStatus = $event['status'] ?? 'idle';
        $this->syncMessage = $event['message'] ?? '';

        if (isset($event['result'])) {
            $this->lastSyncResult = $event['result'];
            Cache::put("last_mbti_sync_result_" . Auth::id(), $event['result'], 86400);
        }

        Cache::put("mbti_sync_status_" . Auth::id(), $this->syncStatus, 86400);

        if ($this->syncStatus === 'completed') {
            $result = $this->lastSyncResult;

            // Set progress ke 100% untuk memastikan
            $this->syncProgress = 100;
            $this->syncCurrent = $result['total'] ?? 0;
            $this->syncTotal = $result['total'] ?? 0;

            $message = sprintf(
                'MBTI sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $result['created'] ?? 0,
                $result['updated'] ?? 0,
                $result['deactivated'] ?? 0
            );

            Toaster::success($message);

            // Stop polling IMMEDIATELY
            $this->isPolling = false;
            $this->dispatch('syncPollingUpdated', isPolling: false);

            // Refresh data
            $this->dispatch('refreshData');

            Log::info('MBTI sync completed successfully', ['result' => $result]);
        } elseif ($this->syncStatus === 'failed') {
            $result = $this->lastSyncResult;
            $errorMessage = $result['error'] ?? 'Unknown error';

            Toaster::error('MBTI sync failed: ' . $errorMessage);

            // Stop polling
            $this->isPolling = false;
            $this->dispatch('syncPollingUpdated', isPolling: false);

            Log::error('MBTI sync failed', ['error' => $errorMessage]);
        }
    }

    public function handleSyncProgressUpdate($event)
    {
        Log::debug('MBTI sync progress update', $event);

        $this->syncProgress = $event['progress'] ?? 0;
        $this->syncMessage = $event['message'] ?? '';
        $this->syncCurrent = $event['current'] ?? 0;
        $this->syncTotal = $event['total'] ?? 0;

        if ($this->syncStatus !== 'processing') {
            $this->syncStatus = 'processing';
            Cache::put("mbti_sync_status_" . Auth::id(), 'processing', 86400);
        }
    }

    public function checkSyncStatus()
    {
        $oldStatus = $this->syncStatus;
        $this->syncStatus = Cache::get("mbti_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_mbti_sync_result_" . Auth::id());

        // Jika status berubah dari processing/queued ke completed
        if (in_array($oldStatus, ['processing', 'queued']) && $this->syncStatus === 'completed') {
            if ($this->lastSyncResult) {
                // Set progress ke 100%
                $this->syncProgress = 100;
                $this->syncCurrent = $this->lastSyncResult['total'] ?? 0;
                $this->syncTotal = $this->lastSyncResult['total'] ?? 0;

                Toaster::success(sprintf(
                    'MBTI sync completed! Created: %d, Updated: %d, Deactivated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0,
                    $this->lastSyncResult['deactivated'] ?? 0
                ));

                $this->dispatch('refreshData');
            }

            // Stop polling
            $this->isPolling = false;
            $this->dispatch('syncPollingUpdated', isPolling: false);
        } elseif (in_array($oldStatus, ['processing', 'queued']) && $this->syncStatus === 'failed') {
            if ($this->lastSyncResult) {
                Toaster::error('MBTI sync failed: ' . ($this->lastSyncResult['error'] ?? 'Unknown error'));
            }

            // Stop polling
            $this->isPolling = false;
            $this->dispatch('syncPollingUpdated', isPolling: false);
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("mbti_sync_status_" . Auth::id());
        Cache::forget("last_mbti_sync_result_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->syncProgress = 0;
        $this->syncMessage = '';
        $this->syncCurrent = 0;
        $this->syncTotal = 0;
        $this->isPolling = false;

        $this->dispatch('syncPollingUpdated', isPolling: false);

        Toaster::info('MBTI sync status cleared');
    }

    public function stopPolling()
    {
        $this->isPolling = false;
        $this->dispatch('syncPollingUpdated', isPolling: false);
    }

    public function mount()
    {
        $this->syncStatus = Cache::get("mbti_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_mbti_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['processing', 'queued'])) {
            $this->isPolling = true;
        }
    }

    public function dehydrate()
    {
        $this->dispatch('syncPollingUpdated', isPolling: $this->isPolling);
    }

    protected function getModelClass(): string
    {
        return Mbti::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'MBTI Record';
    }

    protected function performDelete($mbti): void
    {
        $mbti->delete();
    }

    public function bulkActivate()
    {
        $count = Mbti::where('source', 'eksternal')
            ->where('is_active', false)
            ->update(['is_active' => true]);

        Toaster::success("Successfully activated {$count} MBTI records.");
        $this->dispatch('refreshData');
    }

    public function bulkDeactivate()
    {
        $count = Mbti::where('source', 'eksternal')
            ->where('is_active', true)
            ->update(['is_active' => false]);

        Toaster::success("Successfully deactivated {$count} MBTI records.");
        $this->dispatch('refreshData');
    }

    public function render()
    {
        $mbtis = Mbti::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->mbtiFilter, fn($q) => $q->where('mbti', $this->mbtiFilter))
            ->when($this->activeFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->activeFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'mbti_asc', fn($q) => $q->orderBy('mbti', 'asc'))
            ->when($this->sortBy === 'mbti_desc', fn($q) => $q->orderBy('mbti', 'desc'))
            ->paginate(10);

        $mbtiTypes = Mbti::whereNotNull('mbti')->distinct()->pluck('mbti')->sort();

        $activeCount = Mbti::where('is_active', true)->count();
        $inactiveCount = Mbti::where('is_active', false)->count();
        $totalCount = Mbti::count();

        return view(
            'livewire.pages.mbtis.index',
            compact('mbtis', 'mbtiTypes', 'activeCount', 'inactiveCount', 'totalCount')
        );
    }
}
