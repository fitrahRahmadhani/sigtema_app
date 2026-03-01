<?php

namespace App\Livewire\Pages\Companies;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Companies')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function edit($companyId)
    {
        $company = Company::findOrFail($companyId);
        return $this->redirect(route('companies.edit', $company->slug), navigate: true);
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return Company::class;
    }

    // Override performDelete
    protected function performDelete($company): void
    {
        $this->authorize('delete', [Company::class]);


        try {
            DB::beginTransaction();
            $logoPath = $company->logo_path;

            // Delete the company from database
            $company->delete();

            // Delete logo
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete company', $e->getMessage());
        }
    }

    public function render()
    {
        $companies = Company::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('cocd', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        return view('livewire.pages.companies.index', [
            'companies' => $companies,
        ]);
    }
}
