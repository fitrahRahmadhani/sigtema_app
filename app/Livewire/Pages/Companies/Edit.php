<?php

namespace App\Livewire\Pages\Companies;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Company')]
class Edit extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public Company $company;
    public $logo;
    public $name;
    public $cocd;
    public $is_active;
    public $current_logo;

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->name = $company->name;
        $this->cocd = $company->cocd;
        $this->is_active = $company->is_active;
        $this->current_logo = $company->logo_path;
    }

    protected function rules()
    {
        return [
            'logo' => ['nullable', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'cocd' => ['required', 'string', 'max:5'],
            'is_active' => ['boolean'],
        ];
    }

    protected function messages()
    {
        return [
            'logo.image' => 'File must be an image (JPG, PNG, etc).',
            'logo.max' => 'Image size cannot exceed 2MB.',
            'name.required' => 'Company name is required.',
            'name.max' => 'Company name cannot exceed 255 characters.',
            'cocd.required' => 'Company code is required.',
            'cocd.max' => 'Company code cannot exceed 5 characters.',
        ];
    }


    public function save()
    {
        try {
            $this->validate();
            $this->authorize('update', $this->company);

            DB::beginTransaction();

            $data = [
                'slug' => Str::slug($this->name),
                'name' => $this->name,
                'cocd' => $this->cocd,
                'is_active' => $this->is_active,
            ];

            // Jika ada logo baru yang diupload
            if ($this->logo) {
                // Hapus logo lama jika ada
                if ($this->company->logo_path && Storage::disk('public')->exists($this->company->logo_path)) {
                    Storage::disk('public')->delete($this->company->logo_path);
                }

                // Upload logo baru
                $date = now()->format('Ymd');
                $slug = Str::slug($this->name);
                $extension = $this->logo->getClientOriginalExtension();
                $filename = "{$slug}_{$date}.{$extension}";

                $logoPath = $this->logo->storeAs('companies/logos', $filename, 'public');
                $data['logo_path'] = $logoPath;
            }

            $this->company->update($data);

            DB::commit();

            return redirect()->route('companies.index')->success('Company updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit company');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit company', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.companies.edit');
    }
}
