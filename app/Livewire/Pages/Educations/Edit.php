<?php

namespace App\Livewire\Pages\Educations;

use App\Models\Education;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Edit Education')]
class Edit extends Component
{
    public Education $education;

    public $employee;
    public $institution_name;
    public $major;
    public $degree;
    public $start_date;
    public $end_date;

    public function mount(Education $education)
    {
        $this->education = $education;

        // Set nilai dari education yang akan diedit
        $this->employee = $education->employee;
        $this->institution_name = $education->institution_name;
        $this->major = $education->major;
        $this->degree = $education->degree;
        $this->start_date = $education->start_date?->format('Y-m-d');
        $this->end_date = $education->end_date?->format('Y-m-d');
    }


    public function rules()
    {
        return [
            'institution_name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'institution_name.required' => 'Institution name is required.',
            'major.required' => 'Major/Field of study is required.',
            'degree.required' => 'Degree level is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.before_or_equal' => 'Start date cannot be in the future.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    public function update()
    {
        try {
            $this->validate();

            $this->education->update([
                'institution_name' => $this->institution_name,
                'major' => $this->major,
                'degree' => $this->degree,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            return redirect()->route('educations.index')->success('Education record has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update education record. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.pages.educations.edit');
    }
}
