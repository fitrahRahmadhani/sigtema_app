<?php

namespace App\Livewire\Pages\Users;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Title('Users')]

    public function render()
    {
        return view('livewire.pages.users.index');
    }
}
