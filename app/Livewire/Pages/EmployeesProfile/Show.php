<?php

namespace App\Livewire\Pages\EmployeesProfile;

use App\Models\Employee;
use App\Models\EmployeeHierarchy;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Title('Employee Profile')]
#[Layout('components.layouts.app')]
class Show extends Component
{
    public User $user;
    public Employee $employee;
    public $accessDenied = false;
    public $accessDeniedMessage = '';

    public $softCompetencies = [];
    public $workDirectionData = [];
    public $discData = [];

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->user = Auth::user();

        // Cek akses sebelum menampilkan profile
        if (!$this->canAccessEmployee($employee)) {
            $this->accessDenied = true;
            $this->setAccessDeniedMessage();
            // Redirect ke index dengan pesan error
            Toaster::error($this->accessDeniedMessage);
            return redirect()->route('employees-profile.index');
        }

        $this->softCompetencies = $employee->getScores();
        $this->workDirectionData = $this->getWorkDirectionData();
        $this->discData = $this->getDISCData();

        Log::info('(EP) User ' . $this->user->name . ' (' . $this->user->email . ')' . ' melihat profile karyawan ' . $this->employee->name);
        activity()->event('Employee Profile')->withProperties(['actor' => $this->user->name, 'employee' => $this->employee->name])->log('Actor viewed employee profile ' . $this->employee->name);
    }

    /**
     * Cek apakah user punya akses ke profile employee tertentu
     */
    private function canAccessEmployee(Employee $targetEmployee): bool
    {
        // Superadmin, Admin, HC-Operator: all access
        if ($this->user->hasRole('superadmin') || $this->user->hasRole('admin') || $this->user->hasRole('hc-operator')) {
            return true;
        }

        // Pastikan target employee ada di company yang sama
        if ($targetEmployee->company_id !== $this->user->company_id) {
            return false;
        }

        $userBand = $this->user->band;

        // Band 1: bisa lihat SEMUA di company yang sama
        if ($userBand == 1) {
            return true;
        }

        // Band 2: bisa lihat band 2 ke bawah (2, 3, 4, 5) di company yang sama
        // Tidak peduli apakah dia punya bawahan atau tidak
        if ($userBand == 2) {
            return $targetEmployee->band >= 2;
        }

        // Band 3+: hanya bisa lihat DIRINYA dan BAWAHAN
        if ($userBand >= 3) {
            if ($targetEmployee->email === $this->user->email) {
                return true;
            }
            return $this->isSubordinate($targetEmployee);
        }

        return false;
    }

    /**
     * Cek apakah employee adalah bawahan langsung
     */
    private function isSubordinate(Employee $targetEmployee): bool
    {
        // Load position dari target employee
        $targetPosition = Position::where('employee_id', $targetEmployee->id)->first();

        if (!$targetPosition) {
            return false;
        }

        // Ambil position user yang login
        $currentEmployee = Employee::with('position')
            ->where('email', $this->user->email)
            ->first();

        if (!$currentEmployee || !$currentEmployee->position) {
            return false;
        }

        $positionId = $currentEmployee->position->id;
        $companyId = $currentEmployee->company_id;

        // Cek di employee hierarchy
        return EmployeeHierarchy::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->where('position_id', $targetPosition->id)
            ->exists();
    }

    /**
     * Set pesan akses ditolak
     */
    private function setAccessDeniedMessage(): void
    {
        $userBand = $this->user->band;

        if ($userBand == 2) {
            $this->accessDeniedMessage = 'You do not have access to view this employee profile. Band 2 can only view employees with band 2 or lower.';
        } elseif ($userBand >= 3) {
            $this->accessDeniedMessage = 'You do not have access to view this employee profile. You can only view your own profile and team members.';
        } else {
            $this->accessDeniedMessage = 'You do not have access to view this employee profile.';
        }
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
        return view('livewire.pages.employees-profile.show', [
            'accessDenied' => $this->accessDenied,
            'accessDeniedMessage' => $this->accessDeniedMessage,
        ]);
    }
}
