<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use App\Models\Burnout;
use App\Models\Innovation;
use App\Models\Mbti;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Index extends Component
{
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    // Metrics
    public $totalEmployees = 0;
    public $vacantPositions = 0;
    public $maleCount = 0;
    public $femaleCount = 0;
    public $avgAge = 0;

    // Chart Data
    public $genderDistribution = [];
    public $bandDistribution = [];
    public $departmentDistribution = [];
    public $ageDistribution = [];
    public $tenureDistribution = [];
    public $mbtiDistribution = [];
    public $burnoutLevels = [];

    // Table Data
    public $vacantPositionsList = [];
    public $topInnovators = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('dashboard_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)->get();
        }

        if ($this->selectedCompanyId) {
            $this->loadDashboardData();
        }
    }

    public function updatedSelectedCompanyId($value)
    {
        if ($value) {
            $this->selectedCompany = Company::find($value);
            session(['dashboard_company_id' => $value]);
            $this->loadDashboardData();
            $this->dispatch('company-changed');
        }
    }

    public function loadDashboardData()
    {
        if (!$this->selectedCompanyId) {
            return;
        }

        $companyId = $this->selectedCompanyId;

        // Reset all data first
        $this->reset([
            'totalEmployees',
            'vacantPositions',
            'maleCount',
            'femaleCount',
            'avgAge',
            'genderDistribution',
            'bandDistribution',
            'departmentDistribution',
            'ageDistribution',
            'tenureDistribution',
            'mbtiDistribution',
            'burnoutLevels',
            'vacantPositionsList',
            'topInnovators'
        ]);

        // Basic Metrics
        $this->totalEmployees = Employee::where('company_id', $companyId)->count();

        $this->vacantPositions = Position::where('company_id', $companyId)
            ->whereNull('employee_id')
            ->count();

        // Male and Female Count
        $this->maleCount = Employee::where('company_id', $companyId)
            ->where('gender', 'male')
            ->count();

        $this->femaleCount = Employee::where('company_id', $companyId)
            ->where('gender', 'female')
            ->count();

        // Average Age - Using accessor
        $employeesWithAge = Employee::where('company_id', $companyId)
            ->whereNotNull('birth_date')
            ->get();

        if ($employeesWithAge->count() > 0) {
            $totalAge = 0;
            $validAges = 0;

            foreach ($employeesWithAge as $emp) {
                $age = $emp->age; // Menggunakan accessor getAgeAttribute()
                if ($age !== "-" && is_numeric($age)) {
                    $totalAge += $age;
                    $validAges++;
                }
            }

            if ($validAges > 0) {
                $this->avgAge = round($totalAge / $validAges, 1);
            }
        }

        // Vacant Positions List
        $this->vacantPositionsList = Position::where('company_id', $companyId)
            ->whereNull('employee_id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($position) {
                return [
                    'name' => $position->name,
                    'code' => $position->position_code,
                    'department' => $position->department ?? '-',
                    'directorate' => $position->directorate ?? '-',
                    'job_grade' => $position->job_grade ?? '-',
                    'work_unit' => $position->work_unit ?? '-',
                ];
            })
            ->toArray();

        // Gender Distribution
        $this->genderDistribution = Employee::where('company_id', $companyId)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get()
            ->map(fn($item) => [
                'label' => ucfirst($item->gender),
                'value' => $item->total
            ])
            ->toArray();

        // Band Distribution
        $this->bandDistribution = Employee::where('company_id', $companyId)
            ->select('band', DB::raw('count(*) as total'))
            ->groupBy('band')
            ->orderBy('band')
            ->get()
            ->map(fn($item) => [
                'label' => $item->band,
                'value' => $item->total
            ])
            ->toArray();

        // Department Distribution (from positions)
        $this->departmentDistribution = Position::where('company_id', $companyId)
            ->whereNotNull('employee_id')
            ->whereNotNull('department')
            ->select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn($item) => [
                'label' => $item->department,
                'value' => $item->total
            ])
            ->toArray();

        // Age Distribution - Menggunakan accessor
        $allEmployees = Employee::where('company_id', $companyId)
            ->whereNotNull('birth_date')
            ->get();

        $ageGroups = [
            '< 25' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55+' => 0,
        ];

        foreach ($allEmployees as $emp) {
            $age = $emp->age; // Menggunakan accessor

            if ($age === "-" || !is_numeric($age)) {
                continue;
            }

            if ($age < 25) $ageGroups['< 25']++;
            elseif ($age < 35) $ageGroups['25-34']++;
            elseif ($age < 45) $ageGroups['35-44']++;
            elseif ($age < 55) $ageGroups['45-54']++;
            else $ageGroups['55+']++;
        }

        $this->ageDistribution = collect($ageGroups)
            ->map(fn($value, $key) => ['label' => $key, 'value' => $value])
            ->values()
            ->toArray();

        // Tenure Distribution - Jika ada accessor untuk tenure, bisa dimanfaatkan
        $employeesWithTenure = Employee::where('company_id', $companyId)
            ->whereNotNull('hire_date')
            ->get();

        $tenureGroups = [
            '< 1 year' => 0,
            '1-3 years' => 0,
            '4-6 years' => 0,
            '7-10 years' => 0,
            '10+ years' => 0,
        ];

        foreach ($employeesWithTenure as $emp) {
            // Jika ada accessor untuk tenure (years of service) di model, bisa digunakan
            // Jika tidak, gunakan cara manual
            $years = now()->diffInYears($emp->hire_date);
            if ($years < 1) $tenureGroups['< 1 year']++;
            elseif ($years < 4) $tenureGroups['1-3 years']++;
            elseif ($years < 7) $tenureGroups['4-6 years']++;
            elseif ($years < 11) $tenureGroups['7-10 years']++;
            else $tenureGroups['10+ years']++;
        }

        $this->tenureDistribution = collect($tenureGroups)
            ->map(fn($value, $key) => ['label' => $key, 'value' => $value])
            ->values()
            ->toArray();

        // MBTI Distribution
        $employeeIds = Employee::where('company_id', $companyId)->pluck('id');

        $this->mbtiDistribution = Mbti::whereIn('employee_id', $employeeIds)
            ->whereNotNull('mbti')
            ->select('mbti', DB::raw('count(*) as total'))
            ->groupBy('mbti')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->mbti,
                'value' => $item->total
            ])
            ->toArray();

        // Burnout Levels - using numeric scores instead of result text
        $burnoutData = Burnout::whereIn('employee_id', $employeeIds)
            ->whereNotNull('emotional_exhaustion')
            ->whereNotNull('depersonalization')
            ->whereNotNull('achievement_decline')
            ->get();

        $burnoutCategories = [
            'Low' => 0,
            'Moderate' => 0,
            'High' => 0,
        ];

        foreach ($burnoutData as $burnout) {
            $totalScore = $burnout->emotional_exhaustion + $burnout->depersonalization + $burnout->achievement_decline;

            if ($totalScore < 50) {
                $burnoutCategories['Low']++;
            } elseif ($totalScore < 80) {
                $burnoutCategories['Moderate']++;
            } else {
                $burnoutCategories['High']++;
            }
        }

        $this->burnoutLevels = collect($burnoutCategories)
            ->map(fn($value, $key) => ['label' => $key, 'value' => $value])
            ->values()
            ->toArray();

        // Top Innovators - only active employees
        $activeEmployeeIds = Employee::where('company_id', $companyId)
            ->pluck('id');

        $this->topInnovators = Innovation::whereIn('employee_id', $activeEmployeeIds)
            ->whereNotNull('employee_id')
            ->where('is_active', true)
            ->select('employee_id', DB::raw('count(*) as total'))
            ->groupBy('employee_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $employee = Employee::find($item->employee_id);
                return [
                    'name' => $employee->name ?? 'Unknown',
                    'total' => $item->total,
                ];
            })
            ->toArray();

        $this->dispatch('charts-ready');
        $this->dispatch('dashboard-data-loaded');
        $this->dispatch('company-changed');
    }

    public function render()
    {
        return view('livewire.pages.dashboard.index');
    }
}
