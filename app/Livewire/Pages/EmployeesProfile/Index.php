<?php

namespace App\Livewire\Pages\EmployeesProfile;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'gender')]
    public $filterGender = '';

    #[Url(as: 'unit')]
    public $filterUnit = '';

    // Reset pagination saat filter berubah
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

    public function render()
    {
        // Dummy data untuk contoh - ganti dengan query database Anda
        $allEmployees = collect([
            [
                'id' => 1,
                'name' => 'M. Habib Sulaiman',
                'employee_id' => '113231001',
                'gender' => 'Male',
                'jg_pg' => '13/13',
                'position' => 'Talent Acquisition & Mobility',
                'unit' => 'Human Capital'
            ],
            [
                'id' => 2,
                'name' => 'Siti Nurhaliza',
                'employee_id' => '113231002',
                'gender' => 'Female',
                'jg_pg' => '12/12',
                'position' => 'HR Business Partner',
                'unit' => 'Human Capital'
            ],
            [
                'id' => 3,
                'name' => 'Ahmad Zaki',
                'employee_id' => '113231003',
                'gender' => 'Male',
                'jg_pg' => '14/14',
                'position' => 'Senior Software Engineer',
                'unit' => 'Information Technology'
            ],
            [
                'id' => 4,
                'name' => 'Dewi Kusuma',
                'employee_id' => '113231004',
                'gender' => 'Female',
                'jg_pg' => '11/11',
                'position' => 'Financial Analyst',
                'unit' => 'Finance'
            ],
            [
                'id' => 5,
                'name' => 'Budi Santoso',
                'employee_id' => '113231005',
                'gender' => 'Male',
                'jg_pg' => '15/15',
                'position' => 'Product Manager',
                'unit' => 'Product Development'
            ],
            [
                'id' => 6,
                'name' => 'Rina Wijaya',
                'employee_id' => '113231006',
                'gender' => 'Female',
                'jg_pg' => '13/13',
                'position' => 'Marketing Manager',
                'unit' => 'Marketing'
            ],
            [
                'id' => 7,
                'name' => 'Agus Setiawan',
                'employee_id' => '113231007',
                'gender' => 'Male',
                'jg_pg' => '12/12',
                'position' => 'Sales Executive',
                'unit' => 'Sales'
            ],
            [
                'id' => 8,
                'name' => 'Maya Sari',
                'employee_id' => '113231008',
                'gender' => 'Female',
                'jg_pg' => '14/14',
                'position' => 'Operations Manager',
                'unit' => 'Operations'
            ],
            [
                'id' => 9,
                'name' => 'Rudi Hartono',
                'employee_id' => '113231009',
                'gender' => 'Male',
                'jg_pg' => '11/11',
                'position' => 'Quality Assurance',
                'unit' => 'Information Technology'
            ],
            [
                'id' => 10,
                'name' => 'Lina Oktavia',
                'employee_id' => '113231010',
                'gender' => 'Female',
                'jg_pg' => '15/15',
                'position' => 'Senior Accountant',
                'unit' => 'Finance'
            ],
            [
                'id' => 11,
                'name' => 'Hendra Wijaya',
                'employee_id' => '113231011',
                'gender' => 'Male',
                'jg_pg' => '13/13',
                'position' => 'Business Analyst',
                'unit' => 'Product Development'
            ],
            [
                'id' => 12,
                'name' => 'Putri Handayani',
                'employee_id' => '113231012',
                'gender' => 'Female',
                'jg_pg' => '12/12',
                'position' => 'Content Specialist',
                'unit' => 'Marketing'
            ],
        ]);

        // Total sebelum filter
        $total = $allEmployees->count();

        // Filter berdasarkan search, gender, dan unit
        $filteredEmployees = $allEmployees->filter(function ($employee) {
            $matchSearch = empty($this->search) ||
                stripos($employee['name'], $this->search) !== false ||
                stripos($employee['employee_id'], $this->search) !== false;

            $matchGender = empty($this->filterGender) ||
                $employee['gender'] === $this->filterGender;

            $matchUnit = empty($this->filterUnit) ||
                $employee['unit'] === $this->filterUnit;

            return $matchSearch && $matchGender && $matchUnit;
        });

        // Get unique units untuk dropdown
        $units = $allEmployees->pluck('unit')->unique()->sort()->values();

        return view('livewire.pages.employees-profile.index', [
            'employees' => $filteredEmployees->values(),
            'total' => $total,
            'units' => $units,
        ]);
    }
}
