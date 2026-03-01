<?php

namespace App\Livewire\Pages\Companies;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Company')]
class Create extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public $logo;
    public $name;
    public $cocd;
    public $is_active = true;

    protected function rules()
    {
        return [
            'logo' => ['required', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'cocd' => ['required', 'string', 'max:5'],
            'is_active' => ['boolean'],
        ];
    }

    protected function messages()
    {
        return [
            'logo.required' => 'Logo is required.',
            'logo.image' => 'File must be an image (JPG, PNG, etc).',
            'logo.max' => 'Image size cannot exceed 2MB.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'cocd.required' => 'Company code is required.',
            'cocd.max' => 'Company code cannot exceed 5 characters.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();

            $this->authorize('create', [Company::class]);

            DB::beginTransaction();

            // Generate custom path: company-name_YYYYMMDD.extension
            $date = now()->format('Ymd');
            $slug = Str::uuid();
            $extension = $this->logo->getClientOriginalExtension();
            $filename = "{$slug}_{$date}.{$extension}";

            // Store with custom filename
            $logoPath = $this->logo->storeAs('companies/logos', $filename, 'public');

            Company::create([
                'slug' => $slug,
                'name' => $this->name,
                'cocd' => $this->cocd,
                'logo_path' => $logoPath,
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            return redirect()->route('companies.index')->success('Company created successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create company');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create company', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.companies.create');
    }
}
