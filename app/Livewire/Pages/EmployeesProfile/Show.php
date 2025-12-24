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
        // Cek apakah employee memiliki data PAPI
        if (!$this->employee->papi) {
            // Jika tidak ada data, kembalikan array kosong atau data default
            return $this->getDefaultPAPIData();
        }

        // Ambil data terformat dari model Papi
        return $this->employee->papi->getFormattedData();
    }

    private function getDefaultPAPIData()
    {
        // Data default jika tidak ada data di database
        // Bisa juga return array kosong [] jika tidak ingin menampilkan data default
        return [
            ['code' => 'N', 'score' => 0],
            ['code' => 'G', 'score' => 0],
            ['code' => 'A', 'score' => 0],
            ['code' => 'L', 'score' => 0],
            ['code' => 'P', 'score' => 0],
            ['code' => 'I', 'score' => 0],
            ['code' => 'T', 'score' => 0],
            ['code' => 'V', 'score' => 0],
            ['code' => 'O', 'score' => 0],
            ['code' => 'B', 'score' => 0],
            ['code' => 'S', 'score' => 0],
            ['code' => 'X', 'score' => 0],
            ['code' => 'C', 'score' => 0],
            ['code' => 'D', 'score' => 0],
            ['code' => 'R', 'score' => 0],
            ['code' => 'Z', 'score' => 0],
            ['code' => 'E', 'score' => 0],
            ['code' => 'K', 'score' => 0],
            ['code' => 'F', 'score' => 0],
            ['code' => 'W', 'score' => 0],
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
