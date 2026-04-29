<?php

namespace App\Livewire\Pages\ActivityLogs;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('components.layouts.app')]
#[Title('Activity Logs')]
class Index extends Component
{
    use WithPagination;

    public string $logFilter   = '';
    public string $monthFilter = '';
    public int    $perPage     = 10;

    public function mount(): void
    {
        $this->monthFilter = now()->format('Y-m');
    }

    public function updatingLogFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMonthFilter(): void
    {
        $this->resetPage();
    }

    private function getActiveYear(): int
    {
        return $this->monthFilter
            ? (int) substr($this->monthFilter, 0, 4)
            : now()->year;
    }

    public function render()
    {
        $activeYear = $this->getActiveYear();

        // Monthly activity count for the active year (for the chart)
        $monthlyData = Activity::query()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $activeYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill all 12 months (missing months = 0)
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = $monthlyData[$m] ?? 0;
        }

        // Paginated logs
        $logs = Activity::query()
            ->when($this->logFilter, fn($q) => $q->where('event', $this->logFilter))
            ->when($this->monthFilter, function ($q) {
                $q->whereYear('created_at', substr($this->monthFilter, 0, 4))
                    ->whereMonth('created_at', substr($this->monthFilter, 5, 2));
            })
            ->latest()
            ->paginate($this->perPage);

        $logNames = Activity::query()
            ->whereNotNull('event')
            ->distinct()
            ->pluck('event');

        return view('livewire.pages.activity-logs.index', [
            'logs'       => $logs,
            'logNames'   => $logNames,
            'chartData'  => $chartData,
            'activeYear' => $activeYear,
        ]);
    }
}
