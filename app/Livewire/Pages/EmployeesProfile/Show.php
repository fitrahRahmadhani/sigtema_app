<?php

namespace App\Livewire\Pages\EmployeesProfile;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Employee Profile')]
#[Layout('components.layouts.app')]
class Show extends Component
{

    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function render()
    {
        return view('livewire.pages.employees-profile.show');
    }
}
