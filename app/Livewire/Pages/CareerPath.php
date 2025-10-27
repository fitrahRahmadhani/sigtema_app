<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CareerPath extends Component
{
    #[Title('Career Path')]

    public function render()
    {
        return view('livewire.pages.career-path');
    }
}
