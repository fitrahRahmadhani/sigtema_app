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

    public $softCompetencies = [];
    public $workDirectionData = [];
    public $discData = [];

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->softCompetencies = $employee->getScores();
        $this->workDirectionData = $this->getWorkDirectionData();
        $this->discData = $this->getDISCData();
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

    private function getDISCData()
    {
        return [
            // DISC 1 - Dominance oriented
            'D1' => 75,
            'I1' => 45,
            'S1' => 30,
            'C1' => 50,
            'interpretation1' => 'High Dominance - Direct, results-oriented, decisive',

            // DISC 2 - Influence oriented
            'D2' => 40,
            'I2' => 80,
            'S2' => 60,
            'C2' => 35,
            'interpretation2' => 'High Influence - Enthusiastic, persuasive, social',

            // DISC 3 - Steadiness oriented
            'D3' => 30,
            'I3' => 45,
            'S3' => 85,
            'C3' => 55,
            'interpretation3' => 'High Steadiness - Patient, reliable, supportive',
        ];
    }

    public function render()
    {
        return view('livewire.pages.employees-profile.show');
    }
}
