<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.vacant-positions.index');
    }
}
