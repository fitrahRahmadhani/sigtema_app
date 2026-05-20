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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Employee Burnout')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $activeFilter = 'active'; // active, inactive, all

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $syncProgress = 0;
    public $syncMessage = '';
    public $syncCurrent = 0;
    public $syncTotal = 0;
    public $isPolling = false;

    // Reset pagination on filter changes
    public function updating($property, $value)
    {
        if (in_array($property, [
            'search',
            'sortBy',
            'activeFilter'
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
            $rateLimitKey = 'burnout_sync_attempts_' . Auth::id();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                Toaster::warning("Too many sync attempts. Try again in {$seconds} seconds.");
                return;
            }

            // Cek lock untuk mencegah multiple sync
            $lockKey = "burnout_sync_lock_" . Auth::id();
            if (Cache::has($lockKey)) {
                Toaster::warning('Another burnout sync is already in progress. Please wait.');
                return;
            }

            // Cek status saat ini
            $currentStatus = Cache::get("burnout_sync_status_" . Auth::id(), 'idle');
            if ($currentStatus === 'processing' || $currentStatus === 'queued') {
                Toaster::warning('Burnout sync is already in progress. Please wait.');
                return;
            }

            // Dispatch job ke queue
            SyncBurnoutsJob::dispatch(Auth::id())->onQueue('default');

            // Update rate limiter
            RateLimiter::hit($rateLimitKey, 600); // 10 menit

            // Set status queued
            Cache::put("burnout_sync_status_" . Auth::id(), 'queued', 3600);

            $this->syncStatus = 'queued';
            $this->syncProgress = 5;
            $this->syncMessage = 'Burnout sync job has been queued...';
            $this->isPolling = true;

            Toaster::success('Burnout sync has been queued and will run in background.');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue burnout sync: ' . $e->getMessage());
            Log::error('Burnout sync dispatch failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Setup event listeners untuk Pusher
     */
    protected function getListeners()
    {
        $userId = Auth::id();

        return [
            // Event listeners untuk Pusher
            "echo-private:burnout-sync-status.{$userId},.burnout.sync.status.updated" => 'handleSyncStatusUpdate',
            "echo-private:burnout-sync-status.{$userId},.burnout.sync.progress.updated" => 'handleSyncProgressUpdate',

            // Local events
            'refreshData' => '$refresh',
            'clearSyncStatus' => 'clearSyncStatus',
        ];
    }

    /**
     * Handle sync status updates dari Pusher
     */
    public function handleSyncStatusUpdate($event)
    {
        $this->syncStatus = $event['status'] ?? 'idle';
        $this->syncMessage = $event['message'] ?? '';

        if (isset($event['result'])) {
            $this->lastSyncResult = $event['result'];

            // Simpan ke cache juga
            Cache::put("last_burnout_sync_result_" . Auth::id(), $event['result'], 86400);
        }

        // Update cache status
        Cache::put("burnout_sync_status_" . Auth::id(), $this->syncStatus, 86400);

        // Show toaster berdasarkan status
        if ($this->syncStatus === 'completed') {
            $result = $this->lastSyncResult;
            $message = sprintf(
                'Burnout sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $result['created'] ?? 0,
                $result['updated'] ?? 0,
                $result['deactivated'] ?? 0
            );

            Toaster::success($message);

            // Auto-refresh data setelah 1 detik
            $this->dispatch('refreshData');

            // Reset polling setelah 5 detik
            $this->dispatch('stopPolling');
        } elseif ($this->syncStatus === 'failed') {
            $result = $this->lastSyncResult;
            $errorMessage = $result['error'] ?? 'Unknown error';
            Toaster::error('Burnout sync failed: ' . $errorMessage);

            // Reset polling setelah 5 detik
            $this->dispatch('stopPolling');
        }

        // Reset progress jika sudah selesai
        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->syncProgress = 0;
            $this->syncCurrent = 0;
            $this->syncTotal = 0;
            $this->isPolling = false;
        }
    }

    /**
     * Handle sync progress updates dari Pusher
     */
    public function handleSyncProgressUpdate($event)
    {
        $this->syncProgress = $event['progress'] ?? 0;
        $this->syncMessage = $event['message'] ?? '';
        $this->syncCurrent = $event['current'] ?? 0;
        $this->syncTotal = $event['total'] ?? 0;

        // Update cache status ke processing
        if ($this->syncStatus !== 'processing') {
            $this->syncStatus = 'processing';
            Cache::put("burnout_sync_status_" . Auth::id(), 'processing', 86400);
        }
    }

    /**
     * Check sync status secara manual (fallback jika Pusher tidak bekerja)
     */
    public function checkSyncStatus()
    {
        $this->syncStatus = Cache::get("burnout_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_burnout_sync_result_" . Auth::id());

        // Jika selesai, tampilkan notifikasi
        if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
            Toaster::success(sprintf(
                'Burnout sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $this->lastSyncResult['created'] ?? 0,
                $this->lastSyncResult['updated'] ?? 0,
                $this->lastSyncResult['deactivated'] ?? 0
            ));

            // Refresh data
            $this->dispatch('refreshData');
            $this->isPolling = false;
        } elseif ($this->syncStatus === 'failed' && $this->lastSyncResult) {
            Toaster::error('Burnout sync failed: ' . ($this->lastSyncResult['error'] ?? 'Unknown error'));
            $this->isPolling = false;
        }
    }

    /**
     * Clear sync status
     */
    public function clearSyncStatus()
    {
        Cache::forget("burnout_sync_status_" . Auth::id());
        Cache::forget("last_burnout_sync_result_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->syncProgress = 0;
        $this->syncMessage = '';
        $this->syncCurrent = 0;
        $this->syncTotal = 0;
        $this->isPolling = false;

        Toaster::info('Burnout sync status cleared');
    }

    /**
     * Stop polling
     */
    public function stopPolling()
    {
        $this->isPolling = false;
    }

    /**
     * Mount - load initial status
     */
    public function mount()
    {
        $this->syncStatus = Cache::get("burnout_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_burnout_sync_result_" . Auth::id());

        // Jika masih processing atau queued, aktifkan polling
        if (in_array($this->syncStatus, ['processing', 'queued'])) {
            $this->isPolling = true;
        }
    }

    /**
     * Dehydrate - tambahkan data ke view
     */
    public function dehydrate()
    {
        // Tambahkan polling flag ke JavaScript
        $this->dispatch('syncPollingUpdated', isPolling: $this->isPolling);
    }

    /**
     * Required methods for WithDeleteConfirmation trait
     */
    protected function getModelClass(): string
    {
        return Burnout::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Burnout Record';
    }

    protected function performDelete($burnout): void
    {
        $burnout->delete();
    }

    /**
     * Bulk activate all inactive records
     */
    public function bulkActivate()
    {
        $count = Burnout::where('is_active', false)->update(['is_active' => true]);

        Toaster::success("Successfully activated {$count} burnout records.");
        $this->dispatch('refreshData');
    }

    /**
     * Bulk deactivate all active records
     */
    public function bulkDeactivate()
    {
        $count = Burnout::where('is_active', true)->update(['is_active' => false]);

        Toaster::success("Successfully deactivated {$count} burnout records.");
        $this->dispatch('refreshData');
    }

    /**
     * Bulk delete - confirmation for active records
     */
    public function bulkDeleteActive()
    {
        $count = Burnout::where('is_active', true)->count();
        $this->deleteName = $count . ' active burnout record' . ($count != 1 ? 's' : '');
        $this->deleteType = 'Active Burnout Records';
        $this->deleteToken = 'bulk_active';
        $this->showDeleteModal = true;
    }

    /**
     * Bulk delete - confirmation for inactive records
     */
    public function bulkDeleteInactive()
    {
        $count = Burnout::where('is_active', false)->count();
        $this->deleteName = $count . ' inactive burnout record' . ($count != 1 ? 's' : '');
        $this->deleteType = 'Inactive Burnout Records';
        $this->deleteToken = 'bulk_inactive';
        $this->showDeleteModal = true;
    }

    /**
     * Bulk delete - confirmation for all records
     */
    public function bulkDeleteAll()
    {
        $count = Burnout::count();
        $this->deleteName = 'ALL ' . $count . ' burnout record' . ($count != 1 ? 's' : '');
        $this->deleteType = 'All Burnout Records';
        $this->deleteToken = 'bulk_all';
        $this->showDeleteModal = true;
    }

    /**
     * Execute bulk delete based on token
     */
    public function executeBulkDelete()
    {
        $this->deleting = true;

        try {
            $deleted = 0;

            switch ($this->deleteToken) {
                case 'bulk_active':
                    $deleted = Burnout::where('is_active', true)->delete();
                    Toaster::success("Successfully deleted {$deleted} active burnout records.");
                    break;

                case 'bulk_inactive':
                    $deleted = Burnout::where('is_active', false)->delete();
                    Toaster::success("Successfully deleted {$deleted} inactive burnout records.");
                    break;

                case 'bulk_all':
                    $deleted = Burnout::delete();
                    Toaster::success("Successfully deleted all {$deleted} burnout records.");
                    break;

                default:
                    Toaster::error('Invalid bulk delete request.');
            }

            $this->resetDeleteState();
            $this->dispatch('refreshData');
        } catch (\Exception $e) {
            $this->deleting = false;
            $this->handleException('bulk delete burnout records', $e->getMessage());
        }
    }

    /**
     * Override delete method to handle bulk deletes
     * Called when user clicks "Yes, Delete" in the modal
     */
    public function delete()
    {
        $this->deleting = true;

        // Check if this is a bulk delete operation
        if (in_array($this->deleteToken, ['bulk_active', 'bulk_inactive', 'bulk_all'])) {
            return $this->executeBulkDelete();
        }

        // Single item deletion
        try {
            $item = $this->getModelClass()::findOrFail($this->getIdFromToken());
            $this->performDelete($item);
            $this->resetDeleteState();
            Toaster::success('Burnout record deleted successfully!');
            $this->dispatch('refreshData');
        } catch (\Exception $e) {
            $this->resetDeleteState();
            $this->handleException('delete burnout record', $e->getMessage());
        }
    }

    /**
     * Get ID from encrypted token
     */
    protected function getIdFromToken()
    {
        if (!$this->deleteToken) {
            return null;
        }

        try {
            $payload = json_decode(\Illuminate\Support\Facades\Crypt::decryptString($this->deleteToken), true);
            return $payload['id'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Toggle individual burnout status
     */
    public function toggleStatus($id)
    {
        $burnout = Burnout::findOrFail($id);
        $burnout->is_active = !$burnout->is_active;
        $burnout->save();

        $status = $burnout->is_active ? 'activated' : 'deactivated';
        Toaster::success("Burnout record {$status} successfully.");

        // Refresh data without resetting pagination
        $this->dispatch('refreshData');
    }

    public function render()
    {
        $burnouts = Burnout::query()
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
            ->when($this->activeFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->activeFilter === 'inactive', fn($q) => $q->where('is_active', false))
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

        $activeCount = Burnout::where('is_active', true)->count();
        $inactiveCount = Burnout::where('is_active', false)->count();
        $totalCount = Burnout::count();

        return view(
            'livewire.pages.burnouts.index',
            compact('burnouts', 'activeCount', 'inactiveCount', 'totalCount')
        );
    }
}
