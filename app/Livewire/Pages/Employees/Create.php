<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Company;
use Livewire\Component;
use App\Models\Employee;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    #[Validate('nullable|date')]
    public $birth_date = '';

    #[Validate('required|in:male,female')]
    public $gender = '';

    #[Validate('nullable|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    #[Validate('required|string|max:50')]
    public $personal_grade = '';

    #[Validate('nullable|date')]
    public $hire_date = '';

    #[Validate('required|in:band 1,band 2,band 3,band 4,band 5')]
    public $band = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public function mount()
    {
        $user = Auth::user();
        $this->company_id = $user->hasRole('Admin') ? $user->company_id : null;
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
                'gender' => $this->gender,
                'personal_grade' => $this->personal_grade,
                'band' => $this->band,
                'company_id' => $this->company_id,
            ];

            if (!empty($this->birth_date)) {
                $data['birth_date'] = $this->birth_date;
            }

            if (!empty($this->hire_date)) {
                $data['hire_date'] = $this->hire_date;
            }

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

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
        return redirect()->route('employees.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.employees.create', [
            'companies' => $companies,
        ]);
    }
}
