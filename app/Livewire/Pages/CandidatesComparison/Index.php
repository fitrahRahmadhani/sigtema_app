<?php

namespace App\Livewire\Pages\CandidatesComparison;

use App\Models\Company;
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
    public $canSearchAllEmployees = false;
    public $hasSubordinates = false;
    public $subordinateIds = [];
    public $userBandLevel = '';
    public $accessRestricted = false;
    public $accessMessage = '';
    public $subordinateCount = 0;

    // New properties for chart data
    public $papiChartData = [];
    public $discChartData = [];
    public $softCompetenciesData = [];

    protected $listeners = ['employeeSelected' => '$refresh'];

    public function mount()
    {
        $this->user = Auth::user();

        // Cek tipe user
        $isSuperadmin = $this->user->hasRole('superadmin');
        $isAdminOrHcOperator = $this->user->hasRole('admin') || $this->user->hasRole('hc-operator');

        if ($isSuperadmin) {
            // Superadmin: pilih company terlebih dahulu
            $this->isSuperadmin = true;
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('candidates_comparison_company_id') ?? request('company_id');
            $this->canSearchAllEmployees = true;
            $this->userBandLevel = 'Superadmin - All access';

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } elseif ($isAdminOrHcOperator) {
            // Admin & HC-Operator: tidak perlu pilih company, langsung gunakan company_id dari User
            $this->isSuperadmin = false;
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->selectedCompanyId)->get();
            $this->canSearchAllEmployees = true;
            $this->userBandLevel = 'Admin/HC-Operator - All access';
        } else {
            // Non-admin users - cek berdasarkan band
            $this->isSuperadmin = false;
            $this->loadSubordinates();

            $userBand = $this->user->band;

            // Band 1: akses penuh ke semua karyawan
            if ($userBand == 1) {
                $this->canSearchAllEmployees = true;
                $this->userBandLevel = 'Band 1 - All access';
            }
            // Band 2: bisa pilih selvel (band 2) dan bawah (band 3, 4, 5)
            elseif ($userBand == 2) {
                $this->canSearchAllEmployees = false;
                $this->userBandLevel = 'Band 2 - Same level and subordinates';
            }
            // Band 3, 4, 5: hanya bisa lihat anggota tim
            else {
                $this->canSearchAllEmployees = false;
                $this->userBandLevel = 'Band 3 - Team members only';

                if (!$this->hasSubordinates) {
                    $this->accessRestricted = true;
                    $this->accessMessage = 'You cannot use this feature because you do not have any team members.';
                }
            }

            // Gunakan company_id dari User
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();
        }

        // Initialize chart data arrays
        $this->initializeChartData();
    }

    /**
     * Load subordinates (anggota tim) untuk user
     */
    private function loadSubordinates(): void
    {
        $currentEmployee = Employee::with('position')
            ->where('email', $this->user->email)
            ->first();

        if (!$currentEmployee || !$currentEmployee->position) {
            $this->hasSubordinates = false;
            $this->subordinateIds = [];
            $this->subordinateCount = 0;
            return;
        }

        $positionId = $currentEmployee->position->id;
        $companyId = $currentEmployee->company_id;

        // Ambil position_id bawahan langsung dari EmployeeHierarchy
        $subordinatePositionIds = EmployeeHierarchy::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->pluck('position_id')
            ->toArray();

        if (empty($subordinatePositionIds)) {
            $this->hasSubordinates = false;
            $this->subordinateIds = [];
            $this->subordinateCount = 0;
            return;
        }

        // Ambil employee IDs dari position bawahan (lewat relasi Position -> employee_id)
        $subordinateEmployees = Position::whereIn('id', $subordinatePositionIds)
            ->whereNotNull('employee_id')
            ->pluck('employee_id')
            ->toArray();

        $this->hasSubordinates = !empty($subordinateEmployees);
        $this->subordinateIds = $subordinateEmployees;
        $this->subordinateCount = count($subordinateEmployees);
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
        // Jangan search jika tidak ada company selected
        if (!$this->selectedCompanyId) {
            return;
        }

        if (strlen($value) >= 3) {
            $selectedIds = collect($this->selectedEmployees)
                ->filter()
                ->pluck('id')
                ->toArray();

            $query = Employee::with([
                'position',
                'educations',
                'technicalCompetencies',
                'mcuResults',
                'positionHistories',
                'innovations',
                'company',
                'papi',
                'disc',
            ])
                ->where('company_id', $this->selectedCompanyId)
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('employee_number', 'like', "%{$value}%")
                        ->orWhereHas('position', function ($positionQuery) use ($value) {
                            $positionQuery->where('name', 'like', "%{$value}%");
                        });
                })
                ->whereNotIn('id', $selectedIds)
                ->limit(5);

            // Terapkan filter berdasarkan role dan band
            $isAdminOrHcOperator = $this->user->hasRole('admin') || $this->user->hasRole('hc-operator');
            $userBand = $this->user->band;

            // Admin & HC-Operator: akses penuh, tidak perlu filter band
            if (!$isAdminOrHcOperator) {
                // Band 2: hanya bisa pilih band 2 ke bawah (3, 4, 5)
                if ($userBand == 2) {
                    $query->where('band', '>=', 2);
                }
                // Band 3+: hanya tampilkan anggota tim
                elseif ($userBand >= 3 && !empty($this->subordinateIds)) {
                    $query->whereIn('id', $this->subordinateIds);
                }
            }

            $this->searchResults[$index] = $query->get();

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
            // Validasi akses berdasarkan role dan band
            $isAdminOrHcOperator = $this->user->hasRole('admin') || $this->user->hasRole('hc-operator');
            $userBand = $this->user->band;

            if (!$isAdminOrHcOperator) {
                // Band 2: harus band 2 ke bawah
                if ($userBand == 2 && $employee->band < 2) {
                    Toaster::error('You do not have access to select this employee. Band 2 can only select employees with band 2 or lower.');
                    return;
                }

                // Band 3+: harus anggota tim
                if ($userBand >= 3) {
                    if (!in_array($employee->id, $this->subordinateIds)) {
                        Toaster::error('You do not have access to select this employee. You can only select your team members.');
                        return;
                    }
                }
            }

            $this->selectedEmployees[$index] = $employee;
            $this->searchTerms[$index] = $employee->name;
            $this->showSearchDropdown[$index] = false;
            $this->searchResults[$index] = [];

            // Load chart data for this employee
            $this->loadChartData($employee, $index);

            // Dispatch event untuk update chart
            $this->dispatch('employee-selected', index: $index);
            $this->dispatch('charts-loaded');

            Log::info('(CC) User ' . $this->user->name . ' selected employee ' . $employee->name);
            activity()->event('Candidate Comparison')->withProperties(['actor' => $this->user->name, 'employee' => $employee->name])->log('Actor compared employee ' . $employee->name);
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
        // Hanya superadmin yang perlu pilih company
        $showCompanySelection = $this->user->hasRole('superadmin') && !$this->selectedCompanyId;

        return view('livewire.pages.candidates-comparison.index', [
            'showCompanySelection' => $showCompanySelection,
            'companies' => $this->companies,
            'isSuperadmin' => $this->isSuperadmin,
            'selectedCompany' => $this->selectedCompany,
            'canSearchAllEmployees' => $this->canSearchAllEmployees,
            'hasSubordinates' => $this->hasSubordinates,
            'userBandLevel' => $this->userBandLevel,
            'accessRestricted' => $this->accessRestricted,
            'accessMessage' => $this->accessMessage,
            'subordinateCount' => $this->subordinateCount,
        ]);
    }
}
