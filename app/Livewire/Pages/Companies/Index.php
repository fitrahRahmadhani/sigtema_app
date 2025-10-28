<?php

namespace App\Livewire\Pages\Companies;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Title('Companies')]

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    public function getDummyData(): Collection
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Tech Innovators Inc',
                'cocd' => 'TII-001',
                'slug' => 'tech-innovators-inc',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-01-15',
                'admin' => [
                    'id' => 1,
                    'name' => 'John Anderson',
                    'email' => 'john.anderson@techinnovators.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 2,
                'name' => 'Global Solutions Ltd',
                'cocd' => 'GSL-002',
                'slug' => 'global-solutions-ltd',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-02-10',
                'admin' => [
                    'id' => 2,
                    'name' => 'Sarah Mitchell',
                    'email' => 'sarah.mitchell@globalsolutions.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 3,
                'name' => 'Digital Dynamics Corp',
                'cocd' => 'DDC-003',
                'slug' => 'digital-dynamics-corp',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-03-05',
                'admin' => [
                    'id' => 3,
                    'name' => 'Michael Chen',
                    'email' => 'michael.chen@digitaldynamics.com',
                    'profile_path' => null,
                    'role' => 'company_admin',
                ],
            ],
            [
                'id' => 4,
                'name' => 'Future Enterprises',
                'cocd' => 'FTE-004',
                'slug' => 'future-enterprises',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-04-12',
                'admin' => [
                    'id' => 4,
                    'name' => 'Emily Rodriguez',
                    'email' => 'emily.rodriguez@futureenterprises.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 5,
                'name' => 'Smart Systems International',
                'cocd' => 'SSI-005',
                'slug' => 'smart-systems-international',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-05-20',
                'admin' => [
                    'id' => 5,
                    'name' => 'David Thompson',
                    'email' => 'david.thompson@smartsystems.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 6,
                'name' => 'Innovative Technologies Group',
                'cocd' => 'ITG-006',
                'slug' => 'innovative-technologies-group',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-06-08',
                'admin' => [
                    'id' => 6,
                    'name' => 'Jessica Lee',
                    'email' => 'jessica.lee@innovativetech.com',
                    'profile_path' => null,
                    'role' => 'company_admin',
                ],
            ],
            [
                'id' => 7,
                'name' => 'NextGen Solutions',
                'cocd' => 'NGS-007',
                'slug' => 'nextgen-solutions',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-07-14',
                'admin' => [
                    'id' => 7,
                    'name' => 'Robert Wilson',
                    'email' => 'robert.wilson@nextgensolutions.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 8,
                'name' => 'Prime Business Services',
                'cocd' => 'PBS-008',
                'slug' => 'prime-business-services',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-08-22',
                'admin' => [
                    'id' => 8,
                    'name' => 'Amanda Brown',
                    'email' => 'amanda.brown@primebusiness.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 9,
                'name' => 'Advanced Analytics Co',
                'cocd' => 'AAC-009',
                'slug' => 'advanced-analytics-co',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-09-10',
                'admin' => [
                    'id' => 9,
                    'name' => 'Christopher Davis',
                    'email' => 'christopher.davis@advancedanalytics.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 10,
                'name' => 'Creative Solutions Hub',
                'cocd' => 'CSH-010',
                'slug' => 'creative-solutions-hub',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-10-01',
                'admin' => [
                    'id' => 10,
                    'name' => 'Jennifer Martinez',
                    'email' => 'jennifer.martinez@creativesolutions.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 11,
                'name' => 'Enterprise Resource Group',
                'cocd' => 'ERG-011',
                'slug' => 'enterprise-resource-group',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-10-15',
                'admin' => [
                    'id' => 11,
                    'name' => 'Daniel White',
                    'email' => 'daniel.white@enterpriseresource.com',
                    'profile_path' => null,
                    'role' => 'company_admin',
                ],
            ],
            [
                'id' => 12,
                'name' => 'Quantum Dynamics Inc',
                'cocd' => 'QDI-012',
                'slug' => 'quantum-dynamics-inc',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-11-05',
                'admin' => [
                    'id' => 12,
                    'name' => 'Michelle Garcia',
                    'email' => 'michelle.garcia@quantumdynamics.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 13,
                'name' => 'Strategic Partners LLC',
                'cocd' => 'SPL-013',
                'slug' => 'strategic-partners-llc',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-11-20',
                'admin' => null, // Example without admin
            ],
            [
                'id' => 14,
                'name' => 'Visionary Systems Corp',
                'cocd' => 'VSC-014',
                'slug' => 'visionary-systems-corp',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-12-01',
                'admin' => [
                    'id' => 14,
                    'name' => 'Kevin Johnson',
                    'email' => 'kevin.johnson@visionarysystems.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
            [
                'id' => 15,
                'name' => 'Apex Technologies',
                'cocd' => 'APX-015',
                'slug' => 'apex-technologies',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => '2024-12-15',
                'admin' => [
                    'id' => 15,
                    'name' => 'Lisa Turner',
                    'email' => 'lisa.turner@apextech.com',
                    'profile_path' => null,
                    'role' => 'admin',
                ],
            ],
        ])->map(function ($item) {
            return (object) array_merge($item, [
                'admin' => $item['admin'] ? (object) $item['admin'] : null
            ]);
        });
    }

    public function getCompanies()
    {
        $data = $this->getDummyData();

        // Apply search filter
        if ($this->search) {
            $data = $data->filter(function ($company) {
                $searchLower = strtolower($this->search);

                return str_contains(strtolower($company->name), $searchLower) ||
                    str_contains(strtolower($company->cocd), $searchLower) ||
                    ($company->admin && (
                        str_contains(strtolower($company->admin->name), $searchLower) ||
                        str_contains(strtolower($company->admin->email), $searchLower)
                    ));
            });
        }

        // Apply sorting
        $data = match ($this->sortBy) {
            'name_asc' => $data->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE),
            'name_desc' => $data->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE),
            'newest' => $data->sortByDesc('created_at'),
            'oldest' => $data->sortBy('created_at'),
            default => $data->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE),
        };

        // Manual pagination
        $page = request()->query('page', 1);
        $perPage = 10;
        $total = $data->count();
        $results = $data->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function edit($companyId)
    {
        // Handle edit action - untuk sementara bisa show alert atau modal
        $this->dispatch('alert', [
            'type' => 'info',
            'message' => "Edit company ID: {$companyId}"
        ]);
    }

    public function confirmDelete($companyId)
    {
        // Handle delete confirmation - untuk sementara bisa show alert atau modal
        $this->dispatch('alert', [
            'type' => 'warning',
            'message' => "Delete company ID: {$companyId}"
        ]);
    }

    public function render()
    {
        $companies = $this->getCompanies();
        return view('livewire.pages.companies.index', [
            'companies' => $companies,
        ]);
    }
}
