<?php

namespace App\Livewire\Pages\Employees;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Employee')]
class Create extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public User $user;
    public Company $company;

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
    public $profile_photo;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'employee_number' => ['required', 'string', 'unique:employees,employee_number'],
            'email' => ['required', 'email', 'unique:employees,email'],
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
            'profile_photo.image' => 'File must be an image (JPG, PNG, etc).',
            'profile_photo.max' => 'Image size cannot exceed 2MB.',
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        if ($this->user->hasRole('superadmin')) {
            $this->company = Company::find(session('employees_company_id'));
            if (!$this->company) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('employees.index');
            }
        } else {
            $this->company = $this->user->company;
        }
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            // Authorization check
            $this->authorize('create', [Employee::class, $this->company]);

            DB::beginTransaction();

            // Prepare base data
            $data = [
                'slug' => Str::uuid(),
                'name' => $this->name,
                'employee_number' => $this->employee_number,
                'email' => $this->email,
                'phone' => $this->phone,
                'gender' => $this->gender,
                'personal_grade' => $this->personal_grade,
                'band' => $this->band,
                'company_id' => $this->company->id,
                'birth_date' => $this->birth_date ?? null,
                'hire_date' => $this->hire_date ?? null,
                'password' => $this->password ? Hash::make($this->password) : null,
            ];

            // Create employee
            $employee = Employee::create($data);

            // Upload profile photo if exists
            if ($this->profile_photo) {
                $path = $this->profile_photo->store('employees', 'public');
                $employee->update(['profile_path' => $path]);
            }

            DB::commit();

            return redirect()->route('employees.index')->success('Employee created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create employee', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.pages.employees.create');
    }
}
