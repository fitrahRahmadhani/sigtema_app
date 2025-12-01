<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
#[Title('Bulk Uploads Employees')]
class Employees extends Component
{
    public function render()
    {
        return view('livewire.pages.bulk-uploads.employees');
    }
}
