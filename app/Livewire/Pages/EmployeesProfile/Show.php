<?php

namespace App\Livewire\Pages\EmployeesProfile;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    #[Title('Employee Profile')]

    public function render()
    {
        return view('livewire.pages.employees-profile.show');
    }
}
