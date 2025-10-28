<?php

namespace App\Livewire\Pages\Companies;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    function test()
    {
        Toaster::error('Test');
    }
    public function render()
    {
        return view('livewire.pages.companies.show');
    }
}
