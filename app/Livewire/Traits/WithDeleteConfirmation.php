<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\On;
use Illuminate\Support\Facades\Crypt;
use Masmerise\Toaster\Toaster;

trait WithDeleteConfirmation
{
    public $showDeleteModal = false;
    public $deleteToken = null;
    public $deleteName = '';
    public $deleteType = '';
    public $deleting = false;

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete($id, $name = null)
    {
        try {
            $item = $this->getModelClass()::findOrFail($id);

            // Generate secure token
            $token = Crypt::encryptString(json_encode([
                'id' => $id,
                'model' => $this->getModelClass(),
                'expires_at' => now()->addMinutes(10)->timestamp,
                'nonce' => bin2hex(random_bytes(16))
            ]));

            $this->deleteToken = $token;
            $this->deleteName = $name ?: $item->name ?? 'this item';
            $this->deleteType = $this->getModelDisplayName();
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            Toaster::error('Error preparing deletion: ' . $e->getMessage());
        }
    }

    /**
     * Perform the deletion with token validation
     */
    #[On('confirm-delete')]
    public function delete()
    {
        if (!$this->deleteToken) {
            Toaster::error('Invalid deletion request.');
            return;
        }

        $this->deleting = true;

        try {
            // Decrypt and validate token
            $payload = json_decode(Crypt::decryptString($this->deleteToken), true);

            // Validate token structure
            if (!isset($payload['id'], $payload['model'], $payload['expires_at'], $payload['nonce'])) {
                throw new \Exception('Invalid token structure');
            }

            // Check expiration
            if ($payload['expires_at'] < now()->timestamp) {
                throw new \Exception('Deletion request has expired. Please try again.');
            }

            // Verify model class matches
            if ($payload['model'] !== $this->getModelClass()) {
                throw new \Exception('Invalid token for this resource');
            }

            // Find item
            $item = $this->getModelClass()::findOrFail($payload['id']);

            // Custom deletion logic
            $this->performDelete($item);

            // Reset state
            $this->resetDeleteState();

            Toaster::success($this->getModelDisplayName() . ' deleted successfully!');

            // Refresh pagination if exists
            if (method_exists($this, 'resetPage')) {
                $this->resetPage();
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->resetDeleteState();
            Toaster::error($this->getModelDisplayName() . ' not found or already deleted.');
        } catch (\Exception $e) {
            $this->resetDeleteState();
            Toaster::error('Deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Cancel deletion
     */
    public function cancelDelete()
    {
        $this->resetDeleteState();
    }

    /**
     * Reset deletion state
     */
    protected function resetDeleteState()
    {
        $this->showDeleteModal = false;
        $this->deleteToken = null;
        $this->deleteName = '';
        $this->deleteType = '';
        $this->deleting = false;
    }

    /**
     * Custom deletion logic - can be overridden
     */
    protected function performDelete($item): void
    {
        $item->delete();
    }

    /**
     * Get the model class name
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the model display name
     */
    protected function getModelDisplayName(): string
    {
        return class_basename($this->getModelClass());
    }
}
