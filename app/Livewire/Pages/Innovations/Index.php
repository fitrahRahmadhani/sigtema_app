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

    public $syncStatus = 'idle'; // idle, queued, processing, completed, failed
    public $lastSyncResult = null;
    public $polling = false;

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
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('Sync is already in progress. Please wait.');
                return;
            }

            // Dispatch job
            SyncInnovationsJob::dispatch(Auth::id())
                ->onQueue('default'); // Gunakan queue default

            // Update status
            $this->syncStatus = 'queued';
            Cache::put("sync_status_" . Auth::id(), 'queued', 3600);

            // Mulai polling untuk cek status
            $this->startPolling();

            Toaster::success('Sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue sync: ' . $e->getMessage());
        }
    }

    /**
     * Get current sync status
     */
    private function getSyncStatus(): string
    {
        // Cek dari cache
        $status = Cache::get("sync_status_" . Auth::id(), 'idle');

        // Jika ada last result, update status
        $lastResult = Cache::get("last_sync_result_" . Auth::id());
        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    /**
     * Start polling untuk cek status job
     */
    private function startPolling(): void
    {
        $this->polling = true;

        // Simpan timestamp polling
        Cache::put("polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    /**
     * Check sync status (dipanggil dari frontend via polling)
     */
    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        // Cek apakah polling sudah timeout (5 menit)
        $startTime = Cache::get("polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("polling_start_" . Auth::id());
            return;
        }

        // Ambil status terbaru
        $this->syncStatus = $this->getSyncStatus();

        // Ambil hasil terakhir
        $this->lastSyncResult = Cache::get("last_sync_result_" . Auth::id());

        // Jika sync selesai, berhenti polling
        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("polling_start_" . Auth::id());

            // Show appropriate message
            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'Sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));

                // Refresh data
                $this->dispatch('refresh-data');
            }
        }
    }

    /**
     * Clear sync status (manual reset)
     */
    public function clearSyncStatus()
    {
        Cache::forget("sync_status_" . Auth::id());
        Cache::forget("last_sync_result_" . Auth::id());
        Cache::forget("polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('Sync status cleared');
    }

    public function render()
    {
        // Auto-check status jika sedang polling
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            // Update status awal
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_sync_result_" . Auth::id());
        }

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
