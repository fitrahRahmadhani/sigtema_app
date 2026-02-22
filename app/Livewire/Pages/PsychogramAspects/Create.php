<?php

namespace App\Livewire\Pages\PsychogramAspects;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\PsychogramAspect;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Psychogram Aspect')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public User $user;
    public $aspect_code = '';
    public $aspect_name = '';
    public $description = '';
    public $is_active = true;
    public $sort_order = 0;

    public $company_id = '';
    public $selectedCompany = null;
    public $isSuperadmin = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // Ambil company_id dari session untuk superadmin, atau dari user untuk admin biasa
        if ($this->isSuperadmin) {
            $companyId = session('psychogram_aspects_company_id');
            if (!$companyId) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('psychogram-aspects.index');
            }
        } else {
            $companyId = $this->user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);
        $this->company_id = $companyId;
    }

    public function rules()
    {
        return [
            'aspect_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('psychogram_aspects', 'aspect_code')
                    ->where(
                        fn($query) =>
                        $query->where('company_id', $this->company_id)
                    ),
            ],
            'aspect_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'aspect_code.unique' => 'This aspect code already exists for this company.',
            'aspect_code.required' => 'Aspect code is required.',
            'aspect_name.required' => 'Aspect name is required.',
            'description.string' => 'Description must be a string.',
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer' => 'Sort order must be an integer.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ];
    }

    public function save()
    {

        try {
            $this->validate();
            $this->authorize('create', [PsychogramAspect::class, $this->user->company]);
            DB::beginTransaction();

            PsychogramAspect::create([
                'company_id' => $this->company_id,
                'aspect_code' => $this->aspect_code,
                'aspect_name' => $this->aspect_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            DB::commit();

            return redirect()->route('psychogram-aspects.index')
                ->success('Psychogram aspect created successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create psychogram aspect');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create psychogram aspect', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-aspects.index');
    }

    public function render()
    {
        return view('livewire.pages.psychogram-aspects.create');
    }
}
