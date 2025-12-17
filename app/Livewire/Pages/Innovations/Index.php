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

#[Layout('components.layouts.app')]
#[Title('Innovations')]
class Index extends Component
{
    use WithPagination;

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

    public $syncStatus = 'idle';
    public $lastSyncResult = null;

    // Reset pagination on filter changes
    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy', 'teamFilter', 'categoryFilter', 'replicationFilter', 'statusFilter'])) {
            $this->resetPage();
        }
    }

    /**
     * Start sync via queue job
     */
    public function syncFromApi()
    {
        try {
            // Cek apakah sudah ada job yang sedang berjalan
            $currentStatus = Cache::get("sync_status_" . Auth::id(), 'idle');

            if ($currentStatus === 'processing') {
                Toaster::warning('Sync is already in progress. Please wait.');
                return;
            }

            // Dispatch job ke queue (akan berjalan di background)
            SyncInnovationsJob::dispatch(Auth::id())
                ->onQueue('default');

            // Set status queued
            Cache::put("sync_status_" . Auth::id(), 'queued', 3600);
            $this->syncStatus = 'queued';

            Toaster::success('Sync has been queued and will run in background. You can close this page.');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue sync: ' . $e->getMessage());
            Log::error('Sync dispatch failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check sync status (otomatis dipanggil dari frontend)
     */
    public function checkSyncStatus()
    {
        $this->syncStatus = Cache::get("sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_sync_result_" . Auth::id());

        // Jika selesai, tampilkan notifikasi
        if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
            Toaster::success(sprintf(
                'Sync completed! Created: %d, Updated: %d, Skipped: %d',
                $this->lastSyncResult['created'] ?? 0,
                $this->lastSyncResult['updated'] ?? 0,
                $this->lastSyncResult['skipped'] ?? 0
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

    public function render()
    {
        $innovations = Innovation::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('team', 'like', '%' . $this->search . '%')
                        ->orWhere('theme', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->teamFilter, fn($q) => $q->where('team', $this->teamFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->when($this->replicationFilter, fn($q) => $q->where('replication', $this->replicationFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->sortBy === 'title_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($this->sortBy === 'title_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->paginate(10);

        $teams = Innovation::whereNotNull('team')->distinct()->pluck('team')->sort();
        $categories = Innovation::whereNotNull('category')->distinct()->pluck('category')->sort();
        $replications = Innovation::whereNotNull('replication')->distinct()->pluck('replication')->sort();
        $statuses = Innovation::whereNotNull('status')->distinct()->pluck('status')->sort();

        return view(
            'livewire.pages.innovations.index',
            compact('innovations', 'teams', 'categories', 'replications', 'statuses')
        );
    }
}
