<?php

namespace App\Livewire\Pages\Users;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
#[Title('Create User')]
class Create extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $band = '';
    public $company_id = '';
    public $selectedRoles = [];
    public $profile_photo;
    public $profile_photo_preview = null;
    public $bandOptions = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:12',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'password_confirmation' => 'required|same:password',
            'band' => 'nullable|numeric|min:1|max:5',
            'company_id' => 'nullable|exists:companies,id',
            'selectedRoles' => 'nullable|array',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    public function mount()
    {
        $this->bandOptions = range(1, 5);
    }

    public function updatedProfilePhoto()
    {
        $this->validate([
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($this->profile_photo) {
            $this->profile_photo_preview = $this->profile_photo->temporaryUrl();
        }
    }

    public function removeProfilePhoto()
    {
        $this->profile_photo = null;
        $this->profile_photo_preview = null;
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            // Authorization check
            $this->authorize('create', User::class);

            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'company_id' => $this->company_id ?: null,
                'email_verified_at' => now(),
                'band' => $this->band ?: null,
            ]);

            // Upload profile photo if exists
            if ($this->profile_photo) {
                $path = $this->profile_photo->store('profiles', 'public');
                $user->update(['profile_path' => $path]);
            }

            // Assign roles if selected
            if (!empty($this->selectedRoles)) {
                $user->syncRoles($this->selectedRoles);
            }

            DB::commit();

            return redirect()->route('users.index')->success('User created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to create user: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create user');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create user', $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('livewire.pages.users.create', [
            'companies' => $companies,
            'roles' => $roles,
        ]);
    }
}
