<?php

namespace App\Livewire\Pages\EmployeesProfile;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeHierarchy;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Employee Profile')]
class Index extends Component
{
    use WithPagination;

    public User $user;
    public ?Employee $employee = null;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'gender')]
    public $filterGender = '';

    #[Url(as: 'unit')]
    public $filterUnit = '';

    #[Url(as: 'dept')]
    public $filterDepartment = '';

    #[Url(as: 'band')]
    public $filterBand = '';

    #[Url]
    public $selectedCompanyId = null;

    public $companies = [];
    public $isSuperadmin = false;
    public $isAdminOrHCOperator = false; // FIX: jadikan property class
    public $selectedCompany = null;
    public $subordinates = [];
    public $hasSubordinates = false;
    public $accessDenied = false;
    public $accessDeniedMessage = '';
    public $canSeeAllEmployees = false;
    public $userBandLevel = '';

    // Options for dropdowns (will be filtered based on other selected filters)
    public $bandOptions = [];
    public $unitOptions = [];
    public $departmentOptions = [];

    public function mount()
    {
        $this->user = Auth::user();

        // Superadmin: pilih company sendiri (bisa multi-company)
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // FIX: simpan sebagai property class agar bisa diakses di render()
        $this->isAdminOrHCOperator = $this->user->hasRole('admin') || $this->user->hasRole('hc-operator');

        // Superadmin: all access - pilih company sendiri
        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('employees_profile_company_id') ?? request('company_id');
            $this->canSeeAllEmployees = true;
            $this->userBandLevel = 'Superadmin - Full access';

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
            return;
        } elseif ($this->isAdminOrHCOperator) {
            // Admin, HC-Operator: gunakan company_id dari user, tampilkan seluruh karyawan di company tersebut
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();
            $this->userBandLevel = 'Full access';
            $this->canSeeAllEmployees = true;
            return;
        } else {
            // User biasa (non-admin): gunakan company_id dari user, tidak perlu pilih company
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();

            // Load employee untuk cek subordinates
            $this->employee = Employee::with('position')->where('email', $this->user->email)->first();
            $this->loadSubordinates();

            $userBand = $this->user->band;

            // Set variabel untuk view
            $this->userBandLevel = match (true) {
                $userBand == 1 => 'Band 1 - Full access',
                $userBand == 2 => 'Band 2 and below ',
                default => 'Team members only',
            };

            // Band 3+: hanya bisa lihat bawahan, kalau tidak ada bawahan redirect ke profile sendiri
            if ($userBand >= 3) {
                if (!$this->hasSubordinates) {
                    // Tidak ada bawahan, redirect ke profile sendiri
                    Toaster::warning('You do not have any team members. Redirecting to your profile.');
                    return redirect()->route('employees-profile.show', $this->employee->slug);
                }
                $this->canSeeAllEmployees = false;
            } elseif ($userBand == 2) {
                // Band 2: bisa lihat seluruh data karyawan band 2 ke bawah di company yang sama
                // Tidak perlu punya bawahan - tetap bisa akses semua
                $this->canSeeAllEmployees = false;
            }
        }
    }

    /**
     * Cek apakah user punya akses ke halaman employee tertentu
     */
    public function canAccessEmployee(Employee $targetEmployee): bool
    {
        // Superadmin, Admin, HC-Operator: all access
        if ($this->isSuperadmin || $this->isAdminOrHCOperator) {
            return true;
        }

        $userBand = $this->user->band;

        // Band 1: bisa lihat SEMUA di company yang sama
        if ($userBand == 1) {
            return true;
        }

        // Band 2: bisa lihat SEMUA employee band 2-5 di company yang sama
        if ($userBand == 2) {
            return $targetEmployee->band >= 2 && $targetEmployee->band <= 5;
        }

        // Band 3+: hanya bisa lihat BAWAHAN dan DIRINYA
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
        if (!$this->employee || !$this->employee->position) {
            return false;
        }

        // Load position dari target employee
        $targetPosition = Position::where('employee_id', $targetEmployee->id)->first();

        if (!$targetPosition) {
            return false;
        }

        $positionId = $this->employee->position->id;
        $companyId = $this->employee->company_id;

        // Cek di employee hierarchy
        return EmployeeHierarchy::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->where('position_id', $targetPosition->id)
            ->exists();
    }

    /**
     * Cek apakah user mencoba mengakses employee yang tidak diizinkan
     */
    public function checkAccess(): void
    {
        $targetSlug = request()->route('slug');

        if ($targetSlug) {
            $targetEmployee = Employee::where('slug', $targetSlug)->first();

            if ($targetEmployee && !$this->canAccessEmployee($targetEmployee)) {
                $this->accessDenied = true;

                if ($this->user->band == 2) {
                    $this->accessDeniedMessage = 'You do not have access to view this employee profile. Band 2 can only view peers and team members.';
                } elseif ($this->user->band >= 3) {
                    $this->accessDeniedMessage = 'You do not have access to view this employee profile. You can only view your own profile and team members.';
                }

                Toaster::error($this->accessDeniedMessage);
            }
        }
    }

    private function loadSubordinates(): void
    {
        if (!$this->employee || !$this->employee->position) {
            $this->hasSubordinates = false;
            $this->subordinates = [];
            return;
        }

        $positionId = $this->employee->position->id;
        $companyId = $this->employee->company_id;

        $subordinatePositionIds = EmployeeHierarchy::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->pluck('position_id')
            ->toArray();

        if (empty($subordinatePositionIds)) {
            $this->hasSubordinates = false;
            $this->subordinates = [];
            return;
        }

        // Ambil employee IDs dari position bawahan (lewat relasi Position -> employee_id)
        $subordinateEmployees = Employee::with('position')
            ->whereHas('position', fn($q) => $q->whereIn('id', $subordinatePositionIds))
            ->where('company_id', $companyId)
            ->get();

        if ($subordinateEmployees->isEmpty()) {
            $this->hasSubordinates = false;
            $this->subordinates = [];
            return;
        }

        $this->hasSubordinates = true;
        $this->subordinates = $subordinateEmployees->map(fn($emp) => [
            'id'              => $emp->id,
            'slug'            => $emp->slug,
            'employee_number' => $emp->employee_number,
            'name'            => $emp->name,
            'band'            => $emp->band,
            'gender'          => $emp->gender,
            'job_grade'       => $emp->position?->job_grade ?? '-',
            'personal_grade'  => $emp->personal_grade,
            'position'        => $emp->position?->name ?? 'No Position',
            'work_unit'       => $emp->position->work_unit ?? '-',
        ])->toArray();
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['employees_profile_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('employees_profile_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterGender()
    {
        $this->resetPage();
    }

    public function updatingFilterUnit()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
        $this->resetPage();
    }

    public function updatingFilterBand()
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterGender = '';
        $this->filterUnit = '';
        $this->filterDepartment = '';
        $this->filterBand = '';
        $this->resetPage();
    }

    /**
     * Get unique work units from position data
     * Returns unique values from section, biro, department, directorate columns
     */
    private function getUniqueWorkUnits($positions): array
    {
        $units = collect();

        foreach ($positions as $position) {
            // Collect values from all unit columns
            if ($position->section) {
                $units->push($position->section);
            }
            if ($position->biro) {
                $units->push($position->biro);
            }
            if ($position->department) {
                $units->push($position->department);
            }
            if ($position->directorate) {
                $units->push($position->directorate);
            }
        }

        return $units->unique()->sort()->values()->toArray();
    }

    /**
     * Build filter options based on current selected filters
     * This makes the dropdown options dependent on each other
     */
    private function buildFilterOptions(): void
    {
        $companyId = $this->selectedCompanyId ?? Auth::user()->company_id;

        // Get all employee IDs for this company
        $companyEmployeeIds = Employee::where('company_id', $companyId)->pluck('id')->toArray();

        // Build base query for positions (without current filters)
        $basePositionQuery = Position::whereIn('employee_id', $companyEmployeeIds);

        // Build base query for employees (without current filters)
        $baseEmployeeQuery = Employee::where('company_id', $companyId);

        // Get all positions data first
        $allPositions = (clone $basePositionQuery)->get();

        // Get all unique values
        $allBands = (clone $baseEmployeeQuery)
            ->whereNotNull('band')
            ->distinct()
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        $allDepartments = $allPositions
            ->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Get all work units (from all unit columns)
        $allUnits = $this->getUniqueWorkUnits($allPositions);

        // Start with all options
        $this->bandOptions = $allBands;
        $this->departmentOptions = $allDepartments;
        $this->unitOptions = $allUnits;

        // Apply dependent filtering - start from bands, then filter departments and units
        // based on employees in those bands
        if (!empty($this->filterBand)) {
            $bandEmployeeIds = (clone $baseEmployeeQuery)
                ->where('band', $this->filterBand)
                ->pluck('id')
                ->toArray();

            $bandPositions = Position::whereIn('employee_id', $bandEmployeeIds)->get();

            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $bandPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
            ));

            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($bandPositions)
            ));
        }

        // Apply department filter - get bands and units from employees in that department
        if (!empty($this->filterDepartment)) {
            $deptPositions = (clone $basePositionQuery)
                ->where('department', $this->filterDepartment)
                ->get();

            $deptEmployeeIds = $deptPositions->pluck('employee_id')->toArray();

            // Filter bands based on employees in this department
            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                (clone $baseEmployeeQuery)->whereIn('id', $deptEmployeeIds)
                    ->whereNotNull('band')
                    ->distinct()
                    ->orderBy('band')
                    ->pluck('band')
                    ->toArray()
            ));

            // Filter units based on positions in this department
            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($deptPositions)
            ));
        }

        // Apply unit filter - get bands and departments from positions with this unit
        if (!empty($this->filterUnit)) {
            $unitPositions = (clone $basePositionQuery)
                ->where(function ($q) {
                    $q->where('section', $this->filterUnit)
                        ->orWhere('biro', $this->filterUnit)
                        ->orWhere('department', $this->filterUnit)
                        ->orWhere('directorate', $this->filterUnit);
                })
                ->get();

            $unitEmployeeIds = $unitPositions->pluck('employee_id')->toArray();

            // Filter bands based on employees in positions with this unit
            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                (clone $baseEmployeeQuery)->whereIn('id', $unitEmployeeIds)
                    ->whereNotNull('band')
                    ->distinct()
                    ->orderBy('band')
                    ->pluck('band')
                    ->toArray()
            ));

            // Filter departments based on positions with this unit
            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $unitPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
            ));
        }

        // Reset invalid selections if they no longer exist in filtered options
        if (!empty($this->filterBand) && !in_array($this->filterBand, $this->bandOptions)) {
            $this->filterBand = '';
        }
        if (!empty($this->filterDepartment) && !in_array($this->filterDepartment, $this->departmentOptions)) {
            $this->filterDepartment = '';
        }
        if (!empty($this->filterUnit) && !in_array($this->filterUnit, $this->unitOptions)) {
            $this->filterUnit = '';
        }
    }

    public function render()
    {
        // Jika superadmin belum pilih company, tidak perlu build filter options
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.employees-profile.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'employees' => collect(),
                'bands' => [],
                'units' => [],
                'departments' => [],
                'total' => 0,
                'userBand' => $this->user->band,
                'hasSubordinates' => $this->hasSubordinates,
                'subordinates' => $this->subordinates,
                'user' => $this->user,
                'userBandLevel' => $this->userBandLevel,
            ]);
        }

        // Build dependent filter options (only when company is selected)
        $this->buildFilterOptions();

        // Tentukan company ID
        $companyId = $this->selectedCompanyId ?? Auth::user()->company_id;
        $selectedCompany = Company::find($companyId);

        // Query employees dengan position
        $query = Employee::with(['position'])
            ->where('company_id', $companyId);

        // Filter search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_number', 'like', '%' . $this->search . '%');
            });
        }

        // Filter gender
        if (!empty($this->filterGender)) {
            $query->where('gender', $this->filterGender);
        }

        // Filter department
        if (!empty($this->filterDepartment)) {
            $query->whereHas('position', function ($q) {
                $q->where('department', $this->filterDepartment);
            });
        }

        // Filter band
        if (!empty($this->filterBand)) {
            $query->where('band', $this->filterBand);
        }

        // Filter unit menggunakan work_unit
        if (!empty($this->filterUnit)) {
            $query->whereHas('position', function ($q) {
                $q->where('section', $this->filterUnit)
                    ->orWhere('biro', $this->filterUnit)
                    ->orWhere('department', $this->filterUnit)
                    ->orWhere('directorate', $this->filterUnit);
            });
        }

        // FIX: superadmin dan admin/hc-operator skip semua logika filter band
        // Mereka sudah punya canSeeAllEmployees = true dari mount()
        if (!$this->isSuperadmin && !$this->isAdminOrHCOperator) {
            $userBand = $this->user->band;

            // Band 2: bisa lihat band 2 ke bawah (band 2, 3, 4, 5) di company yang sama
            if ($userBand == 2) {
                $query->where('band', '>=', 2)->where('band', '<=', 5);
            }

            // Band 3+: hanya bisa lihat DIRINYA dan BAWAHAN
            if ($userBand >= 3) {
                $this->canSeeAllEmployees = false;
                // Jika tidak punya bawahan, redirect ke profile sendiri
                if (!$this->hasSubordinates && $this->employee) {
                    Toaster::warning('You do not have any team members. Redirecting to your profile.');
                    return redirect()->route('employees-profile.show', $this->employee->slug);
                }
                $subordinateIds = collect($this->subordinates)->pluck('id')->toArray();
                $query->whereIn('id', $subordinateIds);
            } else {
                $this->canSeeAllEmployees = true;
            }
        }

        // Paginate
        $employees = $query->paginate(10);

        return view('livewire.pages.employees-profile.index', [
            'showCompanySelection' => false,
            'employees' => $employees,
            'total' => $employees->total(),
            'bands' => $this->bandOptions,
            'units' => $this->unitOptions,
            'departments' => $this->departmentOptions,
            'selectedCompany' => $selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'userBand' => $this->user->band,
            'hasSubordinates' => $this->hasSubordinates,
            'subordinates' => $this->subordinates,
            'user' => $this->user,
            'userBandLevel' => $this->userBandLevel,
        ]);
    }
}