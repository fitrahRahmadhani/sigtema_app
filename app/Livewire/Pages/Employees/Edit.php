<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Employee')]
class Edit extends Component
{
    use WithFileUploads;

    public Employee $employee;

    #[Validate('required|string|max:255')]
    public $name = '';

    public $employee_number = '';

    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('nullable|date')]
    public $birth_date = '';

    #[Validate('required|in:male,female')]
    public $gender = '';

    #[Validate([
        'nullable',
        'string',
        'min:12',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
        'confirmed'
    ])]
    public $password = '';

    public $password_confirmation = '';

    #[Validate('required|string|max:50')]
    public $personal_grade = '';

    #[Validate('nullable|date')]
    public $hire_date = '';

    #[Validate('required|in:1,2,3,4,5')]
    public $band = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public $existing_profile_photo = null;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->name = $employee->name;
        $this->employee_number = $employee->employee_number;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->birth_date = $employee->birth_date?->format('Y-m-d');
        $this->gender = $employee->gender;
        $this->personal_grade = $employee->personal_grade;
        $this->hire_date = $employee->hire_date?->format('Y-m-d');
        $this->band = $employee->band;
        $this->company_id = $employee->company_id;
        $this->existing_profile_photo = $employee->profile_path;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', Rule::unique('employees')->ignore($this->employee->id)],
            'employee_number' => ['required', 'string', Rule::unique('employees')->ignore($this->employee->id)],
        ];
    }

    public function deleteExistingPhoto()
    {
        if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
            Storage::disk('public')->delete($this->existing_profile_photo);
        }

        $this->employee->update(['profile_path' => null]);
        $this->existing_profile_photo = null;

        Toaster::success('Profile photo deleted successfully!');
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'employee_number' => $this->employee_number,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'personal_grade' => $this->personal_grade,
                'hire_date' => $this->hire_date,
                'company_id' => $this->company_id,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            if ($this->profile_photo) {
                if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
                    Storage::disk('public')->delete($this->existing_profile_photo);
                }

                $path = $this->profile_photo->store('employees', 'public');
                $data['profile_path'] = $path;
            }

            $this->employee->update($data);

            return redirect()->route('employees.index')->success('Employee updated successfully!');
        } catch (\Exception $e) {
            Toaster::error('Failed to update employee: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('employees.index'), navigate: true);
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.employees.edit', [
            'companies' => $companies,
        ]);
    }
}
