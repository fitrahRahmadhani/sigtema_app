<?php

namespace App\Livewire\Pages\Employees;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
#[Title('Detail Pegawai')]
class Show extends Component
{
    public $employee;

    public $softCompetencies = [];


    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->softCompetencies = $employee->getScores();
    }

    public function render()
    {
        return view('livewire.pages.employees.show');
    }
}
