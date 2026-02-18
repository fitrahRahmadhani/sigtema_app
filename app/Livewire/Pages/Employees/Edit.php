<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

#[Layout('components.layouts.app')]
#[Title('Edit Employee')]
class Edit extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public Employee $employee;

    public $name = '';
    public $employee_number = '';
    public $email = '';
    public $phone = '';
    public $birth_date = '';
    public $gender = '';
    public $password = '';
    public $password_confirmation = '';
    public $personal_grade = '';
    public $hire_date = '';
    public $band = '';
    public $company_id = '';
    public $profile_photo;
    public $existing_profile_photo = null;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'employee_number' => [
                'required',
                'string',
                Rule::unique('employees')->ignore($this->employee->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($this->employee->id)
            ],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['required', 'in:Male,Female'],
            'password' => [
                'nullable',
                'string',
                'min:12',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
            'password_confirmation' => ['nullable', 'same:password'],
            'personal_grade' => ['required', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'band' => ['required', 'in:1,2,3,4,5'],
            'company_id' => ['required', 'exists:companies,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Employee name is required.',
            'name.max' => 'Employee name cannot exceed 255 characters.',
            'employee_number.required' => 'Employee number is required.',
            'employee_number.unique' => 'This employee number is already registered.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'birth_date.date' => 'Please enter a valid birth date.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be either Male or Female.',
            'password.min' => 'Password must be at least 12 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character (@$!%*#?&).',
            'password.confirmed' => 'Password confirmation does not match.',
            'personal_grade.required' => 'Personal grade is required.',
            'personal_grade.max' => 'Personal grade cannot exceed 50 characters.',
            'hire_date.date' => 'Please enter a valid hire date.',
            'band.required' => 'Band is required.',
            'band.in' => 'Band must be between 1 and 5.',
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Selected company does not exist.',
            'profile_photo.image' => 'File must be an image (JPG, PNG, etc).',
            'profile_photo.max' => 'Image size cannot exceed 2MB.',
        ];
    }

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

    public function deleteExistingPhoto()
    {
        try {
            // Authorization check
            $this->authorize('update', $this->employee);

            DB::beginTransaction();

            $oldPhotoPath = $this->employee->profile_path;

            // Update database first
            $this->employee->update(['profile_path' => null]);

            // Delete file after successful database update
            if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            $this->existing_profile_photo = null;

            DB::commit();

            Toaster::success('Profile photo deleted successfully!');
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('delete employee photo');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('delete employee photo', $e->getMessage());
        }
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            // Authorization check
            $this->authorize('update', $this->employee);

            DB::beginTransaction();

            $oldPhotoPath = $this->employee->profile_path;

            // Prepare data to update
            $data = [
                'name' => $this->name,
                'employee_number' => $this->employee_number,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'band' => $this->band,
                'gender' => $this->gender,
                'personal_grade' => $this->personal_grade,
                'hire_date' => $this->hire_date,
                'company_id' => $this->company_id,
            ];

            // Update password if provided
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            // Upload profile photo if exists
            if ($this->profile_photo) {
                $newPath = $this->profile_photo->store('employees', 'public');
                $data['profile_path'] = $newPath;
            }

            // Update employee
            $this->employee->update($data);

            // Delete old photo after successful database update (only if new photo uploaded)
            if ($this->profile_photo && $oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            DB::commit();

            return redirect()->route('employees.index')->success('Employee updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit employee', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('employees.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.employees.edit', [
            'companies' => $companies,
        ]);
    }
}
