<?php

namespace App\Livewire\Pages\CandidatesComparison;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Employee Comparison')]
class Index extends Component
{
    public User $user;
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $selectedEmployees = [];
    public $searchTerms = ['', '', ''];
    public $searchResults = [[], [], []];
    public $showSearchDropdown = [false, false, false];
    public $columnCount = 3;
    public $isSuperadmin = false;

    // New properties for chart data
    public $papiChartData = [];
    public $discChartData = [];
    public $softCompetenciesData = [];

    protected $listeners = ['employeeSelected' => '$refresh'];

    public function mount()
    {
        $this->user = Auth::user();

        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('candidates_comparison_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();
        }

        // Initialize chart data arrays
        $this->initializeChartData();
    }

    public function updated($property)
    {
        // Jika ada perubahan pada selectedEmployees, inisialisasi ulang charts
        if (str_starts_with($property, 'selectedEmployees')) {
            $this->dispatch('charts-loaded');
        }
    }

    private function initializeChartData()
    {
        // Initialize arrays for chart data
        $this->papiChartData = array_fill(0, $this->columnCount, null);
        $this->discChartData = array_fill(0, $this->columnCount, null);
        $this->softCompetenciesData = array_fill(0, $this->columnCount, [
            'strengths' => [],
            'weaknesses' => []
        ]);
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['candidates_comparison_company_id' => $companyId]);

        // Reset employee selections when changing company
        $this->resetEmployeeData();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('candidates_comparison_company_id');

        // Reset employee selections
        $this->resetEmployeeData();
    }

    private function resetEmployeeData()
    {
        $this->selectedEmployees = array_fill(0, $this->columnCount, null);
        $this->searchTerms = array_fill(0, $this->columnCount, '');
        $this->searchResults = array_fill(0, $this->columnCount, []);
        $this->showSearchDropdown = array_fill(0, $this->columnCount, false);
        $this->initializeChartData();

        // Dispatch event untuk update chart
        $this->dispatch('charts-loaded');
    }

    public function addColumn()
    {
        if ($this->columnCount < 5) {
            $this->columnCount++;
            $this->searchTerms[] = '';
            $this->searchResults[] = [];
            $this->showSearchDropdown[] = false;
            $this->selectedEmployees[] = null;
            $this->papiChartData[] = null;
            $this->discChartData[] = null;
            $this->softCompetenciesData[] = [
                'strengths' => [],
                'weaknesses' => []
            ];

            // Dispatch event untuk update chart
            $this->dispatch('charts-loaded');
        }
    }

    public function removeColumn($index)
    {
        if ($this->columnCount > 2) {
            array_splice($this->searchTerms, $index, 1);
            array_splice($this->searchResults, $index, 1);
            array_splice($this->showSearchDropdown, $index, 1);
            array_splice($this->selectedEmployees, $index, 1);
            array_splice($this->papiChartData, $index, 1);
            array_splice($this->discChartData, $index, 1);
            array_splice($this->softCompetenciesData, $index, 1);
            $this->columnCount--;

            // Dispatch event untuk update chart
            $this->dispatch('charts-loaded');
        }
    }

    private function loadChartData($employee, $index)
    {
        // Load PAPI data
        if ($employee->papi) {
            $this->papiChartData[$index] = $employee->papi->getFormattedData();
        } else {
            $this->papiChartData[$index] = $this->getDefaultPAPIData();
        }

        // Load DISC data
        if ($employee->disc && ($employee->disc?->interpretation1 != '(Skor tidak valid)' && $employee->disc?->interpretation2 != '(Skor tidak valid)' && $employee->disc?->interpretation3 != '(Skor tidak valid)')) {
            $this->discChartData[$index] = [
                'D1' => $employee->disc->D1 ?? 0,
                'I1' => $employee->disc->I1 ?? 0,
                'S1' => $employee->disc->S1 ?? 0,
                'C1' => $employee->disc->C1 ?? 0,
                'interpretation1' => $employee->disc->interpretation1 ?? 'No data available',
                'D2' => $employee->disc->D2 ?? 0,
                'I2' => $employee->disc->I2 ?? 0,
                'S2' => $employee->disc->S2 ?? 0,
                'C2' => $employee->disc->C2 ?? 0,
                'interpretation2' => $employee->disc->interpretation2 ?? 'No data available',
                'D3' => $employee->disc->D3 ?? 0,
                'I3' => $employee->disc->I3 ?? 0,
                'S3' => $employee->disc->S3 ?? 0,
                'C3' => $employee->disc->C3 ?? 0,
                'interpretation3' => $employee->disc->interpretation3 ?? 'No data available',
            ];
        } else {
            $this->discChartData[$index] = $this->getDefaultDISCData();
        }

        // Load Soft Competencies data menggunakan method getScores()
        try {
            $this->softCompetenciesData[$index] = $employee->getScores();
        } catch (\Exception $e) {
            $this->softCompetenciesData[$index] = [
                'strengths' => [],
                'weaknesses' => []
            ];
        }
    }

    private function getDefaultPAPIData()
    {
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

    private function getDefaultDISCData()
    {
        return [
            'D1' => 0,
            'I1' => 0,
            'S1' => 0,
            'C1' => 0,
            'interpretation1' => 'No data available',
            'D2' => 0,
            'I2' => 0,
            'S2' => 0,
            'C2' => 0,
            'interpretation2' => 'No data available',
            'D3' => 0,
            'I3' => 0,
            'S3' => 0,
            'C3' => 0,
            'interpretation3' => 'No data available',
        ];
    }

    public function updatedSearchTerms($value, $index)
    {
        // Don't search if no company selected
        if (!$this->selectedCompanyId) {
            return;
        }

        if (strlen($value) >= 3) {
            $selectedIds = collect($this->selectedEmployees)
                ->filter()
                ->pluck('id')
                ->toArray();

            $this->searchResults[$index] = Employee::with([
                'position',
                'educations',
                'technicalCompetencies',
                'mcuResults',
                'positionHistories',
                'innovations',
                'company',
                'papi',
                'disc',
                // Hapus softCompetencies dari eager loading
            ])
                ->where('company_id', $this->selectedCompanyId)
                ->when(
                    $this->user->hasRole('head-of-unit'),
                    fn($q) =>
                    $q->where('band', '>=', (int) $this->user->band)
                )
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('employee_number', 'like', "%{$value}%")
                        ->orWhereHas('position', function ($positionQuery) use ($value) {
                            $positionQuery->where('name', 'like', "%{$value}%");
                        });
                })
                ->whereNotIn('id', $selectedIds)
                ->limit(5)
                ->get();

            $this->showSearchDropdown[$index] = !empty($this->searchResults[$index]);
        } else {
            $this->searchResults[$index] = [];
            $this->showSearchDropdown[$index] = false;
        }
    }

    public function selectEmployee($employeeId, $index)
    {
        $employee = Employee::with([
            'position',
            'educations',
            'technicalCompetencies',
            'mcuResults',
            'positionHistories',
            'innovations',
            'company',
            'papi',
            'disc',
        ])->find($employeeId);

        if ($employee) {
            $this->selectedEmployees[$index] = $employee;
            $this->searchTerms[$index] = $employee->name;
            $this->showSearchDropdown[$index] = false;
            $this->searchResults[$index] = [];

            // Load chart data for this employee
            $this->loadChartData($employee, $index);

            // Dispatch event to initialize charts
            $this->dispatch('employee-selected', index: $index);
            $this->dispatch('charts-loaded');

            Log::info('(CC) User ' . $this->user->name . ' selected employee ' . $employee->name);
        }
    }

    public function clearEmployee($index)
    {
        $this->selectedEmployees[$index] = null;
        $this->searchTerms[$index] = '';
        $this->searchResults[$index] = [];
        $this->showSearchDropdown[$index] = false;
        $this->papiChartData[$index] = null;
        $this->discChartData[$index] = null;
        $this->softCompetenciesData[$index] = [
            'strengths' => [],
            'weaknesses' => []
        ];

        // Dispatch event to update charts
        $this->dispatch('employee-cleared', index: $index);
        $this->dispatch('charts-loaded');
    }

    public function render()
    {
        $showCompanySelection = $this->isSuperadmin && !$this->selectedCompanyId;

        return view('livewire.pages.candidates-comparison.index', [
            'showCompanySelection' => $showCompanySelection,
            'companies' => $this->companies,
            'isSuperadmin' => $this->isSuperadmin,
            'selectedCompany' => $this->selectedCompany
        ]);
    }
}
