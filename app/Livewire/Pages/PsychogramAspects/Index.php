<?php

namespace App\Livewire\Pages\PsychogramAspects;

use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Models\PsychogramAspect;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Psychogram Aspects')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'sort_order_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'status')]
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function edit($aspectId)
    {
        $aspect = PsychogramAspect::findOrFail($aspectId);
        return $this->redirect(route('psychogram-aspects.edit', $aspect), navigate: true);
    }

    public function toggleStatus($aspectId)
    {
        $aspect = PsychogramAspect::findOrFail($aspectId);
        $aspect->update(['is_active' => !$aspect->is_active]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status updated successfully!'
        ]);
    }

    protected function getModelClass(): string
    {
        return PsychogramAspect::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Psychogram Aspect';
    }

    protected function performDelete($aspect): void
    {
        // Cek jika aspect digunakan di psychogram scores sebelum menghapus
        if ($aspect->scores()->exists()) {
            Toaster::error('Cannot delete aspect because it is being used in psychogram scores.');
            return;
        } else {
            $aspect->delete();
        }
    }

    public function render()
    {
        $aspects = PsychogramAspect::query()
            ->with(['company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('aspect_code', 'like', '%' . $this->search . '%')
                        ->orWhere('aspect_name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, fn($query) => $query->where('company_id', $this->companyFilter))
            ->when($this->statusFilter === 'active', fn($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($query) => $query->where('is_active', false))
            ->when($this->sortBy === 'aspect_code_asc', fn($query) => $query->orderBy('aspect_code', 'asc'))
            ->when($this->sortBy === 'aspect_code_desc', fn($query) => $query->orderBy('aspect_code', 'desc'))
            ->when($this->sortBy === 'aspect_name_asc', fn($query) => $query->orderBy('aspect_name', 'asc'))
            ->when($this->sortBy === 'aspect_name_desc', fn($query) => $query->orderBy('aspect_name', 'desc'))
            ->when($this->sortBy === 'sort_order_asc', fn($query) => $query->orderBy('sort_order', 'asc'))
            ->when($this->sortBy === 'sort_order_desc', fn($query) => $query->orderBy('sort_order', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.psychogram-aspects.index', [
            'aspects' => $aspects,
            'companies' => $companies,
        ]);
    }
}
