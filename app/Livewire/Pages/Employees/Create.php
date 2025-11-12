<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Employee')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|unique:employees,employee_number')]
    public $employee_number = '';

    #[Validate('required|email|unique:employees,email')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('required|date')]
    public $birth_date = '';

    #[Validate('required|in:male,female')]
    public $gender = '';

    #[Validate('nullable|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    #[Validate('required|string|max:50')]
    public $personal_grade = '';

    #[Validate('required|date')]
    public $hire_date = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|exists:positions,id')]
    public $position_id = '';

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public $positions = [];

    public function mount()
    {
        $this->positions = collect();
    }

    public function updatedCompanyId()
    {
        $this->position_id = '';

        if ($this->company_id) {
            $this->positions = Position::where('company_id', $this->company_id)
                ->orderBy('name')
                ->get();
        } else {
            $this->positions = collect();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'slug' => Str::uuid(),
                'name' => $this->name,
                'employee_number' => $this->employee_number,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'password' => Hash::make($this->password),
                'personal_grade' => $this->personal_grade,
                'hire_date' => $this->hire_date,
                'company_id' => $this->company_id,
                'position_id' => $this->position_id,
            ];

            if ($this->profile_photo) {
                $path = $this->profile_photo->store('employees', 'public');
                $data['profile_path'] = $path;
            }

            Employee::create($data);

            return redirect()->route('employees.index')->success('Employee created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create employee: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('employees.index')->success('Employee created successfully');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.employees.create', [
            'companies' => $companies,
        ]);
    }
}
