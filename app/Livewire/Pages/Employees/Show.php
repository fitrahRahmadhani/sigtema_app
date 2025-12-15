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
    public $workDirectionData = [];


    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->softCompetencies = $employee->getScores();
        $this->workDirectionData = $this->getWorkDirectionData();
    }

    private function getWorkDirectionData()
    {
        // Data dummy sesuai gambar yang Anda kirimkan
        return [
            ['code' => 'N', 'score' => 6],
            ['code' => 'G', 'score' => 5],
            ['code' => 'A',  'score' => 6],
            ['code' => 'L',  'score' => 8],
            ['code' => 'P',  'score' => 4],
            ['code' => 'I',  'score' => 4],
            ['code' => 'T', 'score' => 2],
            ['code' => 'V',  'score' => 3],
            ['code' => 'O',  'score' => 4],
            ['code' => 'B',  'score' => 9],
            ['code' => 'S',  'score' => 2],
            ['code' => 'X',  'score' => 4],
            ['code' => 'C',  'score' => 6],
            ['code' => 'D',  'score' => 4],
            ['code' => 'R',  'score' => 7],
            ['code' => 'Z',  'score' => 7],
            ['code' => 'E',  'score' => 4],
            ['code' => 'K',  'score' => 3],
            ['code' => 'F',  'score' => 5],
            ['code' => 'W',  'score' => 6],
        ];
    }

    public function render()
    {
        return view('livewire.pages.employees.show');
    }
}
