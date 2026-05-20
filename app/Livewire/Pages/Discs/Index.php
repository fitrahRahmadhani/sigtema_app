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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('DISC Result')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $sourceFilter = '';

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
        if (in_array($property, ['search', 'sortBy', 'sourceFilter', 'activeFilter'])) {
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
            $rateLimitKey = 'disc_sync_attempts_' . Auth::id();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                Toaster::warning("Too many sync attempts. Try again in {$seconds} seconds.");
                return;
            }

            // Cek lock untuk mencegah multiple sync
            $lockKey = "disc_sync_lock_" . Auth::id();
            if (Cache::has($lockKey)) {
                Toaster::warning('Another DISC sync is already in progress. Please wait.');
                return;
            }

            // Cek status saat ini
            $currentStatus = Cache::get("disc_sync_status_" . Auth::id(), 'idle');
            if ($currentStatus === 'processing' || $currentStatus === 'queued') {
                Toaster::warning('DISC sync is already in progress. Please wait.');
                return;
            }

            // Dispatch job ke queue
            SyncDiscsJob::dispatch(Auth::id())->onQueue('default');

            // Update rate limiter
            RateLimiter::hit($rateLimitKey, 600); // 10 menit

            // Set status queued
            Cache::put("disc_sync_status_" . Auth::id(), 'queued', 3600);

            $this->syncStatus = 'queued';
            $this->syncProgress = 5;
            $this->syncMessage = 'DISC sync job has been queued...';
            $this->isPolling = true;

            Toaster::success('DISC sync has been queued and will run in background.');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue DISC sync: ' . $e->getMessage());
            Log::error('DISC sync dispatch failed', [
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
            // Event listeners untuk Pusher (DISC specific)
            "echo-private:disc-sync-status.{$userId},.disc.sync.status.updated" => 'handleDiscSyncStatusUpdate',
            "echo-private:disc-sync-status.{$userId},.disc.sync.progress.updated" => 'handleDiscSyncProgressUpdate',

            // Local events
            'refreshDiscData' => '$refresh',
            'clearDiscSyncStatus' => 'clearSyncStatus',
        ];
    }

    /**
     * Handle DISC sync status updates dari Pusher
     */
    public function handleDiscSyncStatusUpdate($event)
    {
        $this->syncStatus = $event['status'] ?? 'idle';
        $this->syncMessage = $event['message'] ?? '';

        if (isset($event['result'])) {
            $this->lastSyncResult = $event['result'];

            // Simpan ke cache juga
            Cache::put("last_disc_sync_result_" . Auth::id(), $event['result'], 86400);
        }

        // Update cache status
        Cache::put("disc_sync_status_" . Auth::id(), $this->syncStatus, 86400);

        // Show toaster berdasarkan status
        if ($this->syncStatus === 'completed') {
            $result = $this->lastSyncResult;
            $message = sprintf(
                'DISC sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $result['created'] ?? 0,
                $result['updated'] ?? 0,
                $result['deactivated'] ?? 0
            );

            Toaster::success($message);

            // Auto-refresh data setelah 1 detik
            $this->dispatch('refreshDiscData');

            // Reset polling setelah 5 detik
            $this->dispatch('stopDiscPolling');
        } elseif ($this->syncStatus === 'failed') {
            $result = $this->lastSyncResult;
            $errorMessage = $result['error'] ?? 'Unknown error';
            Toaster::error('DISC sync failed: ' . $errorMessage);

            // Reset polling setelah 5 detik
            $this->dispatch('stopDiscPolling');
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
     * Handle DISC sync progress updates dari Pusher
     */
    public function handleDiscSyncProgressUpdate($event)
    {
        $this->syncProgress = $event['progress'] ?? 0;
        $this->syncMessage = $event['message'] ?? '';
        $this->syncCurrent = $event['current'] ?? 0;
        $this->syncTotal = $event['total'] ?? 0;

        // Update cache status ke processing
        if ($this->syncStatus !== 'processing') {
            $this->syncStatus = 'processing';
            Cache::put("disc_sync_status_" . Auth::id(), 'processing', 86400);
        }
    }

    /**
     * Check sync status secara manual (fallback jika Pusher tidak bekerja)
     */
    public function checkSyncStatus()
    {
        $this->syncStatus = Cache::get("disc_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_disc_sync_result_" . Auth::id());

        // Jika selesai, tampilkan notifikasi
        if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
            Toaster::success(sprintf(
                'DISC sync completed! Created: %d, Updated: %d, Deactivated: %d',
                $this->lastSyncResult['created'] ?? 0,
                $this->lastSyncResult['updated'] ?? 0,
                $this->lastSyncResult['deactivated'] ?? 0
            ));

            // Refresh data
            $this->dispatch('refreshDiscData');
            $this->isPolling = false;
        } elseif ($this->syncStatus === 'failed' && $this->lastSyncResult) {
            Toaster::error('DISC sync failed: ' . ($this->lastSyncResult['error'] ?? 'Unknown error'));
            $this->isPolling = false;
        }
    }

    /**
     * Clear sync status
     */
    public function clearSyncStatus()
    {
        Cache::forget("disc_sync_status_" . Auth::id());
        Cache::forget("last_disc_sync_result_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->syncProgress = 0;
        $this->syncMessage = '';
        $this->syncCurrent = 0;
        $this->syncTotal = 0;
        $this->isPolling = false;

        Toaster::info('DISC sync status cleared');
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
        $this->syncStatus = Cache::get("disc_sync_status_" . Auth::id(), 'idle');
        $this->lastSyncResult = Cache::get("last_disc_sync_result_" . Auth::id());

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
        $this->dispatch('discSyncPollingUpdated', isPolling: $this->isPolling);
    }

    /**
     * Required methods for WithDeleteConfirmation trait
     */
    protected function getModelClass(): string
    {
        return Disc::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'DISC Assessment';
    }

    protected function performDelete($disc): void
    {
        $disc->delete();
    }

    /**
     * Bulk activate all inactive records
     */
    public function bulkActivate()
    {
        $count = Disc::where('is_active', false)->update(['is_active' => true]);

        Toaster::success("Successfully activated {$count} DISC assessments.");
        $this->dispatch('refreshDiscData');
    }

    /**
     * Bulk deactivate all active records
     */
    public function bulkDeactivate()
    {
        $count = Disc::where('is_active', true)->update(['is_active' => false]);

        Toaster::success("Successfully deactivated {$count} DISC assessments.");
        $this->dispatch('refreshDiscData');
    }

    /**
     * Bulk delete - confirmation for active records
     */
    public function bulkDeleteActive()
    {
        $count = Disc::where('is_active', true)->count();
        $this->deleteName = $count . ' active DISC assessment' . ($count != 1 ? 's' : '');
        $this->deleteType = 'Active DISC Assessments';
        $this->deleteToken = 'bulk_active';
        $this->showDeleteModal = true;
    }

    /**
     * Bulk delete - confirmation for inactive records
     */
    public function bulkDeleteInactive()
    {
        $count = Disc::where('is_active', false)->count();
        $this->deleteName = $count . ' inactive DISC assessment' . ($count != 1 ? 's' : '');
        $this->deleteType = 'Inactive DISC Assessments';
        $this->deleteToken = 'bulk_inactive';
        $this->showDeleteModal = true;
    }

    /**
     * Bulk delete - confirmation for all records
     */
    public function bulkDeleteAll()
    {
        $count = Disc::count();
        $this->deleteName = 'ALL ' . $count . ' DISC assessment' . ($count != 1 ? 's' : '');
        $this->deleteType = 'All DISC Assessments';
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
                    $deleted = Disc::where('is_active', true)->delete();
                    Toaster::success("Successfully deleted {$deleted} active DISC assessments.");
                    break;

                case 'bulk_inactive':
                    $deleted = Disc::where('is_active', false)->delete();
                    Toaster::success("Successfully deleted {$deleted} inactive DISC assessments.");
                    break;

                case 'bulk_all':
                    $deleted = Disc::delete();
                    Toaster::success("Successfully deleted all {$deleted} DISC assessments.");
                    break;

                default:
                    Toaster::error('Invalid bulk delete request.');
            }

            $this->resetDeleteState();
            $this->dispatch('refreshDiscData');
        } catch (\Exception $e) {
            $this->deleting = false;
            $this->handleException('bulk delete DISC assessments', $e->getMessage());
        }
    }

    /**
     * Override delete method to handle bulk deletes
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
            Toaster::success('DISC assessment deleted successfully!');
            $this->dispatch('refreshDiscData');
        } catch (\Exception $e) {
            $this->resetDeleteState();
            $this->handleException('delete DISC assessment', $e->getMessage());
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
     * Toggle individual DISC assessment status
     */
    public function toggleStatus($id)
    {
        $disc = Disc::findOrFail($id);
        $disc->is_active = !$disc->is_active;
        $disc->save();

        $status = $disc->is_active ? 'activated' : 'deactivated';
        Toaster::success("DISC assessment {$status} successfully.");

        // Refresh data without resetting pagination
        $this->dispatch('refreshDiscData');
    }

    /**
     * Render with complete statistics
     */
    public function render()
    {
        if ($this->isPolling) {
            $this->checkSyncStatus();
        }

        $discs = Disc::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // HANYA PENCARIAN BERDASARKAN NAMA DAN NOMOR KARYAWAN
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->sourceFilter, fn($q) => $q->where('source', $this->sourceFilter))
            ->when($this->activeFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->activeFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'employee_asc', fn($q) => $q->orderBy('employee_number', 'asc'))
            ->when($this->sortBy === 'employee_desc', fn($q) => $q->orderBy('employee_number', 'desc'))
            ->paginate(10);

        $sources = Disc::whereNotNull('source')->distinct()->pluck('source')->sort();

        $activeCount = Disc::where('is_active', true)->count();
        $inactiveCount = Disc::where('is_active', false)->count();
        $totalCount = Disc::count();
        $externalCount = Disc::where('source', 'eksternal')->count();
        $internalCount = Disc::where('source', 'internal')->count();

        return view(
            'livewire.pages.discs.index',
            compact(
                'discs',
                'sources',
                'activeCount',
                'inactiveCount',
                'totalCount',
                'externalCount',
                'internalCount'
            )
        );
    }
}
