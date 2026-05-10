<?php

namespace App\Livewire\Pages\ReportTickets;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Issue Reports')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'sort')]
    public string $sortBy = 'newest';

    #[Url(as: 'month')]
    public string $monthFilter = '';

    public function mount(): void
    {
        $this->monthFilter = now()->format('Y-m');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingSortBy(): void
    {
        $this->resetPage();
    }
    public function updatingMonthFilter(): void
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return Ticket::class;
    }
    protected function getModelDisplayName(): string
    {
        return 'Report';
    }

    protected function performDelete($ticket): void
    {
        try {
            if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $ticket->delete();
        } catch (\Exception $e) {
            $this->handleException('delete report', $e->getMessage());
        }
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->with('user')
            ->when(
                $this->search,
                fn($q) =>
                $q->where(
                    fn($inner) =>
                    $inner->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhereHas(
                            'user',
                            fn($u) =>
                            $u->where('name', 'like', '%' . $this->search . '%')
                        )
                )
            )
            ->when(
                $this->monthFilter,
                fn($q) =>
                $q->whereYear('created_at', substr($this->monthFilter, 0, 4))
                    ->whereMonth('created_at', substr($this->monthFilter, 5, 2))
            )
            ->when($this->sortBy === 'newest',     fn($q) => $q->latest())
            ->when($this->sortBy === 'oldest',     fn($q) => $q->oldest())
            ->when($this->sortBy === 'title_asc',  fn($q) => $q->orderBy('title'))
            ->when($this->sortBy === 'title_desc', fn($q) => $q->orderByDesc('title'))
            ->paginate(15);

        return view('livewire.pages.report-tickets.index', [
            'tickets' => $tickets,
        ]);
    }
}
