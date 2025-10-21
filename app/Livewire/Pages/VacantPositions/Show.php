<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    #[Title('Candidates Match')]

    public $selectedCandidates = [];

    // Validasi maksimal 5 kandidat
    public function updatedSelectedCandidates($value)
    {
        if (count($this->selectedCandidates) > 5) {
            // Ambil hanya 5 kandidat pertama
            $this->selectedCandidates = array_slice($this->selectedCandidates, 0, 5);
        }
    }

    // Optimize rendering dengan computed property
    public function getCandidatesProperty()
    {
        return [
            [
                'id' => 'candidate_1',
                'name' => 'M. Habib Sulaiman',
                'department' => 'Human Capital',
                'division' => 'Talent Acquisition & Mobility',
                'band' => 'Band 4',
                'match' => 100,
                'avatar' => 'https://avatar.iran.liara.run/public/22'
            ],
            [
                'id' => 'candidate_2',
                'name' => 'Siti Nurhaliza',
                'department' => 'Finance',
                'division' => 'Financial Planning & Analysis',
                'band' => 'Band 5',
                'match' => 98,
                'avatar' => 'https://avatar.iran.liara.run/public/23'
            ],
            [
                'id' => 'candidate_3',
                'name' => 'Ahmad Fauzi',
                'department' => 'IT',
                'division' => 'Software Development',
                'band' => 'Band 4',
                'match' => 95,
                'avatar' => 'https://avatar.iran.liara.run/public/24'
            ],
            [
                'id' => 'candidate_4',
                'name' => 'Rina Wijaya',
                'department' => 'Marketing',
                'division' => 'Digital Marketing',
                'band' => 'Band 3',
                'match' => 93,
                'avatar' => 'https://avatar.iran.liara.run/public/25'
            ],
            [
                'id' => 'candidate_5',
                'name' => 'Budi Santoso',
                'department' => 'Operations',
                'division' => 'Supply Chain Management',
                'band' => 'Band 4',
                'match' => 92,
                'avatar' => 'https://avatar.iran.liara.run/public/26'
            ],
            [
                'id' => 'candidate_6',
                'name' => 'Dewi Lestari',
                'department' => 'Human Capital',
                'division' => 'Learning & Development',
                'band' => 'Band 5',
                'match' => 90,
                'avatar' => 'https://avatar.iran.liara.run/public/27'
            ],
            [
                'id' => 'candidate_7',
                'name' => 'Rudi Hermawan',
                'department' => 'Sales',
                'division' => 'Enterprise Sales',
                'band' => 'Band 4',
                'match' => 89,
                'avatar' => 'https://avatar.iran.liara.run/public/28'
            ],
            [
                'id' => 'candidate_8',
                'name' => 'Maya Kartika',
                'department' => 'IT',
                'division' => 'Data Analytics',
                'band' => 'Band 3',
                'match' => 88,
                'avatar' => 'https://avatar.iran.liara.run/public/29'
            ],
            [
                'id' => 'candidate_9',
                'name' => 'Andi Pratama',
                'department' => 'Finance',
                'division' => 'Treasury & Risk Management',
                'band' => 'Band 5',
                'match' => 87,
                'avatar' => 'https://avatar.iran.liara.run/public/30'
            ],
            [
                'id' => 'candidate_10',
                'name' => 'Fitri Handayani',
                'department' => 'Marketing',
                'division' => 'Brand Management',
                'band' => 'Band 4',
                'match' => 86,
                'avatar' => 'https://avatar.iran.liara.run/public/31'
            ],
            [
                'id' => 'candidate_11',
                'name' => 'Doni Setiawan',
                'department' => 'Operations',
                'division' => 'Logistics & Distribution',
                'band' => 'Band 3',
                'match' => 85,
                'avatar' => 'https://avatar.iran.liara.run/public/32'
            ],
            [
                'id' => 'candidate_12',
                'name' => 'Linda Susanti',
                'department' => 'Human Capital',
                'division' => 'Compensation & Benefits',
                'band' => 'Band 5',
                'match' => 84,
                'avatar' => 'https://avatar.iran.liara.run/public/33'
            ],
            [
                'id' => 'candidate_13',
                'name' => 'Hendra Gunawan',
                'department' => 'IT',
                'division' => 'Infrastructure & Security',
                'band' => 'Band 4',
                'match' => 83,
                'avatar' => 'https://avatar.iran.liara.run/public/34'
            ],
            [
                'id' => 'candidate_14',
                'name' => 'Ratna Sari',
                'department' => 'Finance',
                'division' => 'Accounting & Tax',
                'band' => 'Band 3',
                'match' => 82,
                'avatar' => 'https://avatar.iran.liara.run/public/35'
            ],
            [
                'id' => 'candidate_15',
                'name' => 'Yoga Prasetyo',
                'department' => 'Sales',
                'division' => 'Business Development',
                'band' => 'Band 5',
                'match' => 81,
                'avatar' => 'https://avatar.iran.liara.run/public/36'
            ],
            [
                'id' => 'candidate_16',
                'name' => 'Novi Rahmawati',
                'department' => 'Marketing',
                'division' => 'Customer Experience',
                'band' => 'Band 4',
                'match' => 80,
                'avatar' => 'https://avatar.iran.liara.run/public/37'
            ],
            [
                'id' => 'candidate_17',
                'name' => 'Irfan Hakim',
                'department' => 'Operations',
                'division' => 'Quality Assurance',
                'band' => 'Band 3',
                'match' => 79,
                'avatar' => 'https://avatar.iran.liara.run/public/38'
            ],
            [
                'id' => 'candidate_18',
                'name' => 'Tuti Wulandari',
                'department' => 'Human Capital',
                'division' => 'Employee Relations',
                'band' => 'Band 4',
                'match' => 78,
                'avatar' => 'https://avatar.iran.liara.run/public/39'
            ],
            [
                'id' => 'candidate_19',
                'name' => 'Eko Prasetyo',
                'department' => 'IT',
                'division' => 'Product Management',
                'band' => 'Band 5',
                'match' => 77,
                'avatar' => 'https://avatar.iran.liara.run/public/40'
            ],
            [
                'id' => 'candidate_20',
                'name' => 'Sari Indah',
                'department' => 'Finance',
                'division' => 'Internal Audit',
                'band' => 'Band 4',
                'match' => 76,
                'avatar' => 'https://avatar.iran.liara.run/public/41'
            ],
        ];
    }

    public function render()
    {
        return view('livewire.pages.vacant-positions.show', [
            'candidates' => $this->candidates
        ]);
    }
}
