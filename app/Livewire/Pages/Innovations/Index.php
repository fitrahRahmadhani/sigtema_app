<?php

namespace App\Livewire\Pages\Innovations;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Innovation;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncInnovationsJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Innovations')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $teamFilter = '';

    #[Url]
    public $categoryFilter = '';

    #[Url]
    public $replicationFilter = '';

    #[Url]
    public $statusFilter = '';

    #[Url]
    public $activeFilter = 'active'; // active, inactive, all

    public $syncStatus = 'idle';
    public $lastSyncResult = null;

    // Reset pagination on filter changes
    public function updating($property, $value)
    {
        if (in_array($property, [
            'search', 'sortBy', 'teamFilter', 'categoryFilter',
            'replicationFilter', 'statusFilter', 'activeFilter'
        ])) {
            $this->resetPage();
        }
    }

    /**
     * Start sync dengan rate limiting dan locking
     */
    public function syncFromApi()
    {
        try {
            // Rate limiting: maksimal 3 sync dalam 10 menit
            $rateLimitKey = 'sync_attempts_' . Auth::id();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                Toaster::warning("Too many sync attempts. Try again in {$seconds} seconds.");
                return;
            }

            // Cek lock untuk mencegah multiple sync
            $lockKey = "innovation_sync_lock_" . Auth::id();
            if (Cache::has($lockKey)) {
                Toaster::warning('Another sync is already in progress. Please wait.');
                return;
            }

            // Cek status saat ini
            $currentStatus = Cache::get("sync_status_" . Auth::id(), 'idle');
            if ($currentStatus === 'processing' || $currentStatus === 'queued') {
                Toaster::warning('Sync is already in progress. Please wait.');
                return;
            }

            // Dispatch job ke queue
            SyncInnovationsJob::dispatch(Auth::id())->onQueue('default');

            // Update rate limiter
            RateLimiter::hit($rateLimitKey, 600); // 10 menit

            // Set status queued
            Cache::put("sync_status_" . Auth::id(), 'queued', 3600);
            $this->syncStatus = 'queued';

            Toaster::success('Sync has been queued and will run in background.');

        } catch (\Exception $e) {
            Toaster::error('Failed to queue sync: ' . $e->getMessage());
            Log::error('Sync dispatch failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check sync status
     */
    public function checkSyncStatus()
    {
        $this->syncStatus = Cache::get("sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_sync_result_" . Auth::id());

        // Jika selesai, tampilkan notifikasi
        if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
            Toaster::success(sprintf(
                'Sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $this->lastSyncResult['created'] ?? 0,
                $this->lastSyncResult['updated'] ?? 0,
                $this->lastSyncResult['deactivated'] ?? 0
            ));
        } elseif ($this->syncStatus === 'failed' && $this->lastSyncResult) {
            Toaster::error('Sync failed: ' . ($this->lastSyncResult['error'] ?? 'Unknown error'));
        }
    }

    /**
     * Clear sync status
     */
    public function clearSyncStatus()
    {
        Cache::forget("sync_status_" . Auth::id());
        Cache::forget("last_sync_result_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;

        Toaster::info('Sync status cleared');
    }

    /**
     * Mount - load initial status
     */
    public function mount()
    {
        $this->syncStatus = Cache::get("sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_sync_result_" . Auth::id());
    }

    /**
     * Required methods for WithDeleteConfirmation trait
     */
    protected function getModelClass(): string
    {
        return Innovation::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Innovation';
    }

    protected function performDelete($innovation): void
    {
        $innovation->delete();
    }

    /**
     * Bulk activate/deactivate
     */
    public function bulkActivate()
    {
        Innovation::where('source', 'eksternal')
            ->where('is_active', false)
            ->update(['is_active' => true]);

        Toaster::success('All inactive innovations activated.');
        $this->resetPage();
    }

    public function bulkDeactivate()
    {
        Innovation::where('source', 'eksternal')
            ->where('is_active', true)
            ->update(['is_active' => false]);

        Toaster::success('All active innovations deactivated.');
        $this->resetPage();
    }

    /**
     * Toggle individual innovation status
     */
    public function toggleStatus($id)
    {
        $innovation = Innovation::findOrFail($id);
        $innovation->is_active = !$innovation->is_active;
        $innovation->save();

        $status = $innovation->is_active ? 'activated' : 'deactivated';
        Toaster::success("Innovation {$status} successfully.");
    }

    public function render()
    {
        $innovations = Innovation::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->teamFilter, fn($q) => $q->where('team', $this->teamFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->when($this->replicationFilter, fn($q) => $q->where('replication', $this->replicationFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->activeFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->activeFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($this->sortBy === 'title_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($this->sortBy === 'title_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->paginate(10);

        $teams = Innovation::whereNotNull('team')->distinct()->pluck('team')->sort();
        $categories = Innovation::whereNotNull('category')->distinct()->pluck('category')->sort();
        $replications = Innovation::whereNotNull('replication')->distinct()->pluck('replication')->sort();
        $statuses = Innovation::whereNotNull('status')->distinct()->pluck('status')->sort();

        $activeCount = Innovation::where('is_active', true)->count();
        $inactiveCount = Innovation::where('is_active', false)->count();
        $totalCount = Innovation::count();

        return view(
            'livewire.pages.innovations.index',
            compact('innovations', 'teams', 'categories', 'replications', 'statuses', 'activeCount', 'inactiveCount', 'totalCount')
        );
    }
}
