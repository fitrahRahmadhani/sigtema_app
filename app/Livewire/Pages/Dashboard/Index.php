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
use Illuminate\Support\Facades\Cache;

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
            // Cache companies untuk performa lebih baik
            $this->companies = Cache::remember('active_companies', 3600, function () {
                return Company::where('is_active', true)
                    ->select('id', 'name', 'logo_path', 'is_active')
                    ->orderBy('name')
                    ->get();
            });

            $this->selectedCompanyId = session('dashboard_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)
                ->select('id', 'name', 'logo_path', 'is_active')
                ->get();
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

        // Cache dashboard data untuk 5 menit
        $cacheKey = "dashboard_data_{$companyId}";
        $cacheDuration = 300; // 5 menit

        // Atau langsung load tanpa cache jika ingin real-time
        $this->loadMetrics($companyId);
        $this->loadChartData($companyId);
        $this->loadTableData($companyId);

        // Dispatch events
        $this->dispatch('charts-ready');
        $this->dispatch('dashboard-data-loaded');
    }

    private function loadMetrics($companyId)
    {
        // Basic Metrics - Optimized dengan single query
        $metrics = Employee::where('company_id', $companyId)
            ->selectRaw('
                COUNT(*) as total_employees,
                SUM(CASE WHEN LOWER(gender) = \'male\' THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN LOWER(gender) = \'female\' THEN 1 ELSE 0 END) as female_count
            ')
            ->first();

        $this->totalEmployees = $metrics->total_employees ?? 0;
        $this->maleCount = $metrics->male_count ?? 0;
        $this->femaleCount = $metrics->female_count ?? 0;

        // Vacant Positions
        $this->vacantPositions = Position::where('company_id', $companyId)
            ->whereNull('employee_id')
            ->count();

        // Average Age - Optimized calculation
        $this->calculateAverageAge($companyId);
    }

    private function calculateAverageAge($companyId)
    {
        // MySQL dan PostgreSQL compatible
        $dbDriver = DB::connection()->getDriverName();

        if ($dbDriver === 'pgsql') {
            // PostgreSQL
            $avgAge = Employee::where('company_id', $companyId)
                ->whereNotNull('birth_date')
                ->selectRaw('AVG(EXTRACT(YEAR FROM AGE(birth_date))) as avg_age')
                ->value('avg_age');
        } else {
            // MySQL
            $avgAge = Employee::where('company_id', $companyId)
                ->whereNotNull('birth_date')
                ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, birth_date, CURDATE())) as avg_age')
                ->value('avg_age');
        }

        $this->avgAge = $avgAge ? round($avgAge, 1) : 0;
    }

    private function loadChartData($companyId)
    {
        // Gender Distribution - Fixed untuk case sensitivity
        $this->genderDistribution = Employee::where('company_id', $companyId)
            ->selectRaw('
                LOWER(gender) as gender_lower,
                COUNT(*) as total
            ')
            ->groupBy('gender_lower')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst($item->gender_lower),
                    'value' => (int) $item->total
                ];
            })
            ->toArray();

        // Band Distribution
        $this->bandDistribution = Employee::where('company_id', $companyId)
            ->whereNotNull('band')
            ->select('band', DB::raw('COUNT(*) as total'))
            ->groupBy('band')
            ->orderBy('band')
            ->get()
            ->map(fn($item) => [
                'label' => $item->band,
                'value' => (int) $item->total
            ])
            ->toArray();

        // Department Distribution
        $this->departmentDistribution = Position::where('company_id', $companyId)
            ->whereNotNull('employee_id')
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->select('department', DB::raw('COUNT(*) as total'))
            ->groupBy('department')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn($item) => [
                'label' => $item->department,
                'value' => (int) $item->total
            ])
            ->toArray();

        // Age Distribution - Database compatible
        $this->calculateAgeDistribution($companyId);

        // Tenure Distribution - Database compatible
        $this->calculateTenureDistribution($companyId);

        // MBTI Distribution
        $employeeIds = Employee::where('company_id', $companyId)->pluck('id');

        $this->mbtiDistribution = Mbti::whereIn('employee_id', $employeeIds)
            ->whereNotNull('mbti')
            ->where('mbti', '!=', '')
            ->select('mbti', DB::raw('COUNT(*) as total'))
            ->groupBy('mbti')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->mbti,
                'value' => (int) $item->total
            ])
            ->toArray();

        // Burnout Levels
        $this->calculateBurnoutLevels($employeeIds);
    }

    private function calculateAgeDistribution($companyId)
    {
        $dbDriver = DB::connection()->getDriverName();

        if ($dbDriver === 'pgsql') {
            // PostgreSQL
            $ageData = Employee::where('company_id', $companyId)
                ->whereNotNull('birth_date')
                ->selectRaw("
                    CASE
                        WHEN EXTRACT(YEAR FROM AGE(birth_date)) < 25 THEN '< 25'
                        WHEN EXTRACT(YEAR FROM AGE(birth_date)) < 35 THEN '25-34'
                        WHEN EXTRACT(YEAR FROM AGE(birth_date)) < 45 THEN '35-44'
                        WHEN EXTRACT(YEAR FROM AGE(birth_date)) < 55 THEN '45-54'
                        ELSE '55+'
                    END as age_group,
                    COUNT(*) as total
                ")
                ->groupBy('age_group')
                ->get();
        } else {
            // MySQL
            $ageData = Employee::where('company_id', $companyId)
                ->whereNotNull('birth_date')
                ->selectRaw("
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 25 THEN '< 25'
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 35 THEN '25-34'
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 45 THEN '35-44'
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 55 THEN '45-54'
                        ELSE '55+'
                    END as age_group,
                    COUNT(*) as total
                ")
                ->groupBy('age_group')
                ->get();
        }

        // Initialize all age groups with 0
        $ageGroups = [
            '< 25' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55+' => 0,
        ];

        // Fill with actual data
        foreach ($ageData as $item) {
            $ageGroups[$item->age_group] = (int) $item->total;
        }

        // Ensure order
        $this->ageDistribution = collect(['< 25', '25-34', '35-44', '45-54', '55+'])
            ->map(fn($key) => ['label' => $key, 'value' => $ageGroups[$key]])
            ->toArray();
    }

    private function calculateTenureDistribution($companyId)
    {
        $dbDriver = DB::connection()->getDriverName();

        if ($dbDriver === 'pgsql') {
            // PostgreSQL
            $tenureData = Employee::where('company_id', $companyId)
                ->whereNotNull('hire_date')
                ->selectRaw("
                    CASE
                        WHEN EXTRACT(YEAR FROM AGE(hire_date)) < 1 THEN '< 1 year'
                        WHEN EXTRACT(YEAR FROM AGE(hire_date)) < 4 THEN '1-3 years'
                        WHEN EXTRACT(YEAR FROM AGE(hire_date)) < 7 THEN '4-6 years'
                        WHEN EXTRACT(YEAR FROM AGE(hire_date)) < 11 THEN '7-10 years'
                        ELSE '10+ years'
                    END as tenure_group,
                    COUNT(*) as total
                ")
                ->groupBy('tenure_group')
                ->get();
        } else {
            // MySQL
            $tenureData = Employee::where('company_id', $companyId)
                ->whereNotNull('hire_date')
                ->selectRaw("
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) < 1 THEN '< 1 year'
                        WHEN TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) < 4 THEN '1-3 years'
                        WHEN TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) < 7 THEN '4-6 years'
                        WHEN TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) < 11 THEN '7-10 years'
                        ELSE '10+ years'
                    END as tenure_group,
                    COUNT(*) as total
                ")
                ->groupBy('tenure_group')
                ->get();
        }

        // Initialize all tenure groups with 0
        $tenureGroups = [
            '< 1 year' => 0,
            '1-3 years' => 0,
            '4-6 years' => 0,
            '7-10 years' => 0,
            '10+ years' => 0,
        ];

        // Fill with actual data
        foreach ($tenureData as $item) {
            $tenureGroups[$item->tenure_group] = (int) $item->total;
        }

        // Ensure order
        $this->tenureDistribution = collect(['< 1 year', '1-3 years', '4-6 years', '7-10 years', '10+ years'])
            ->map(fn($key) => ['label' => $key, 'value' => $tenureGroups[$key]])
            ->toArray();
    }

    private function calculateBurnoutLevels($employeeIds)
    {
        $burnoutData = Burnout::whereIn('employee_id', $employeeIds)
            ->whereNotNull('emotional_exhaustion')
            ->whereNotNull('depersonalization')
            ->whereNotNull('achievement_decline')
            ->selectRaw('
                CASE
                    WHEN (emotional_exhaustion + depersonalization + achievement_decline) < 50 THEN \'Low\'
                    WHEN (emotional_exhaustion + depersonalization + achievement_decline) < 80 THEN \'Moderate\'
                    ELSE \'High\'
                END as burnout_level,
                COUNT(*) as total
            ')
            ->groupBy('burnout_level')
            ->get();

        // Initialize all levels with 0
        $burnoutCategories = [
            'Low' => 0,
            'Moderate' => 0,
            'High' => 0,
        ];

        // Fill with actual data
        foreach ($burnoutData as $item) {
            $burnoutCategories[$item->burnout_level] = (int) $item->total;
        }

        // Ensure order: Low, Moderate, High
        $this->burnoutLevels = collect(['Low', 'Moderate', 'High'])
            ->map(fn($key) => ['label' => $key, 'value' => $burnoutCategories[$key]])
            ->toArray();
    }

    private function loadTableData($companyId)
    {
        // Vacant Positions List
        $this->vacantPositionsList = Position::where('company_id', $companyId)
            ->whereNull('employee_id')
            ->select([
                'name',
                'position_code',
                'department',
                'directorate',
                'job_grade',
                'work_unit'
            ])
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

        // Top Innovators - Optimized dengan JOIN
        $this->topInnovators = Innovation::join('employees', 'innovations.employee_id', '=', 'employees.id')
            ->where('employees.company_id', $companyId)
            ->where('innovations.is_active', true)
            ->whereNotNull('innovations.employee_id')
            ->select('employees.id', 'employees.name', DB::raw('COUNT(*) as total'))
            ->groupBy('employees.id', 'employees.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total' => (int) $item->total,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pages.dashboard.index');
    }
}
