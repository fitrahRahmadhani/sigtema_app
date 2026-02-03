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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Employee')]
class Edit extends Component
{
    use WithFileUploads;

    public Employee $employee;

    public $name, $employee_number, $email, $phone;
    public $birth_date, $gender, $personal_grade, $hire_date;
    public $company_id, $band;

    #[Validate('nullable|string|min:8|confirmed')]
    public $password = '';
    public $password_confirmation = '';

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
            'name' => 'required|string|max:255',
            'employee_number' => ['required', 'string', Rule::unique('employees')->ignore($this->employee->id)],
            'email' => ['required', 'email', Rule::unique('employees')->ignore($this->employee->id)],
            'phone' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'personal_grade' => 'required|string|max:50',
            'hire_date' => 'nullable|date',
            'band' => 'required|in:1,2,3,4,5',
            'company_id' => 'required|exists:companies,id',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
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
