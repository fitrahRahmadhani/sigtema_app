<?php

namespace App\Livewire\Pages\EmployeesProfile;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Employee Profile')]
#[Layout('components.layouts.app')]
class Show extends Component
{
    public User $user;
    public $employee;

    public $softCompetencies = [];
    public $workDirectionData = [];
    public $discData = [];

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->user = Auth::user();

        if ($this->user->hasRole('head-of-unit') && $this->user->band > $this->employee->band) {
            return redirect()->route('employees-profile.index')->warning('You do not have permission to access this page.');
        }
        $this->softCompetencies = $employee->getScores();
        $this->workDirectionData = $this->getWorkDirectionData();
        $this->discData = $this->getDISCData();

        Log::info('(EP) User ' . $this->user->name . ' (' . $this->user->email . ')' . ' melihat profile karyawan ' . $this->employee->name);
        activity()->withProperties(['actor' => $this->user->name, 'employee' => $this->employee->name])->log('(EP) User viewed employee profile');
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
        if (!$this->employee->disc || $this->employee->disc?->interpretation1 == '(Skor tidak valid)' || $this->employee->disc?->interpretation2 == '(Skor tidak valid)' || $this->employee->disc?->interpretation3 == '(Skor tidak valid)') {
            return $this->getDefaultDISCData();
        }
        return $this->employee->disc;
    }

    private function getDefaultDISCData()
    {
        return [
            // DISC 1
            'D1' => 0,
            'I1' => 0,
            'S1' => 0,
            'C1' => 0,
            'interpretation1' => 'No data available',

            // DISC 2
            'D2' => 0,
            'I2' => 0,
            'S2' => 0,
            'C2' => 0,
            'interpretation2' => 'No data available',

            // DISC 3
            'D3' => 0,
            'I3' => 0,
            'S3' => 0,
            'C3' => 0,
            'interpretation3' => 'No data available',
        ];
    }

    public function render()
    {
        return view('livewire.pages.employees-profile.show');
    }
}
