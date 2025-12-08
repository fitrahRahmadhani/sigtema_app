<?php

namespace App\Livewire\Pages\Personalities;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Personality;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Jobs\SyncPersonalitiesJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.app')]
#[Title('Personality Assessment')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $mbtiFilter = '';

    #[Url]
    public $discFilter = '';

    #[Url]
    public $papiFilter = '';

    public $syncStatus = 'idle';
    public $lastSyncResult = null;
    public $polling = false;

    public function updating($property, $value)
    {
        if (in_array($property, ['search', 'sortBy', 'mbtiFilter', 'discFilter', 'papiFilter'])) {
            $this->resetPage();
        }
    }

    public function syncFromApi()
    {
        try {
            if ($this->getSyncStatus() === 'processing') {
                Toaster::warning('Personality sync is already in progress. Please wait.');
                return;
            }

            SyncPersonalitiesJob::dispatch(Auth::id())->onQueue('default');

            $this->syncStatus = 'queued';
            Cache::put("personality_sync_status_" . Auth::id(), 'queued', 3600);
            $this->startPolling();

            Toaster::success('Personality sync has been queued. Processing in background...');
        } catch (\Exception $e) {
            Toaster::error('Failed to queue personality sync: ' . $e->getMessage());
        }
    }

    private function getSyncStatus(): string
    {
        $status = Cache::get("personality_sync_status_" . Auth::id(), 'idle');
        $lastResult = Cache::get("last_personality_sync_result_" . Auth::id());

        if ($lastResult) {
            $status = $lastResult['status'] ?? 'completed';
        }

        return $status;
    }

    private function startPolling(): void
    {
        $this->polling = true;
        Cache::put("personality_polling_start_" . Auth::id(), now()->timestamp, 3600);
    }

    public function checkSyncStatus()
    {
        if (!$this->polling) {
            return;
        }

        $startTime = Cache::get("personality_polling_start_" . Auth::id());
        if ($startTime && (now()->timestamp - $startTime) > 300) {
            $this->polling = false;
            Cache::forget("personality_polling_start_" . Auth::id());
            return;
        }

        $this->syncStatus = $this->getSyncStatus();
        $this->lastSyncResult = Cache::get("last_personality_sync_result_" . Auth::id());

        if (in_array($this->syncStatus, ['completed', 'failed'])) {
            $this->polling = false;
            Cache::forget("personality_polling_start_" . Auth::id());

            if ($this->syncStatus === 'completed' && $this->lastSyncResult) {
                Toaster::success(sprintf(
                    'Personality sync completed! Created: %d, Updated: %d',
                    $this->lastSyncResult['created'] ?? 0,
                    $this->lastSyncResult['updated'] ?? 0
                ));
                $this->dispatch('refresh-data');
            }
        }
    }

    public function clearSyncStatus()
    {
        Cache::forget("personality_sync_status_" . Auth::id());
        Cache::forget("last_personality_sync_result_" . Auth::id());
        Cache::forget("personality_polling_start_" . Auth::id());

        $this->syncStatus = 'idle';
        $this->lastSyncResult = null;
        $this->polling = false;

        Toaster::info('Personality sync status cleared');
    }

    public function render()
    {
        if ($this->polling) {
            $this->checkSyncStatus();
        } else {
            $this->syncStatus = $this->getSyncStatus();
            $this->lastSyncResult = Cache::get("last_personality_sync_result_" . Auth::id());
        }

        $personalities = Personality::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('mbti', 'like', '%' . $this->search . '%')
                        ->orWhere('disc', 'like', '%' . $this->search . '%')
                        ->orWhere('papi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->mbtiFilter, fn($q) => $q->where('mbti', $this->mbtiFilter))
            ->when($this->discFilter, fn($q) => $q->where('disc', $this->discFilter))
            ->when($this->papiFilter, fn($q) => $q->where('papi', $this->papiFilter))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest('created_at'))
            ->when($this->sortBy === 'mbti_asc', fn($q) => $q->orderBy('mbti', 'asc'))
            ->when($this->sortBy === 'mbti_desc', fn($q) => $q->orderBy('mbti', 'desc'))
            ->paginate(10);

        $mbtiTypes = Personality::whereNotNull('mbti')->distinct()->pluck('mbti')->sort();
        $discTypes = Personality::whereNotNull('disc')->distinct()->pluck('disc')->sort();
        $papiTypes = Personality::whereNotNull('papi')->distinct()->pluck('papi')->sort();

        return view('livewire.pages.personalities.index', compact('personalities', 'mbtiTypes', 'discTypes', 'papiTypes'));
    }
}
