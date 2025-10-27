<?php

namespace App\Livewire\Pages\CandidatesComparison;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Title('Candidates Comparison')]

    public $selectedCandidates = [];
    public $searchTerms = ['', ''];
    public $searchResults = [[], []];
    public $showSearchDropdown = [false, false];
    public $dummyCandidates = [];
    public $columnCount = 2; // Mulai dengan 2 kolom

    public function mount()
    {
        $this->loadDummyData();

        // Set default candidates (ID 1, 2, 3)
        $this->selectedCandidates[0] = collect($this->dummyCandidates)->firstWhere('id', 1);
        $this->selectedCandidates[1] = collect($this->dummyCandidates)->firstWhere('id', 2);

        // Set search terms with candidate names
        $this->searchTerms[0] = $this->selectedCandidates[0]->name;
        $this->searchTerms[1] = $this->selectedCandidates[1]->name;
    }

    public function loadDummyData()
    {
        $this->dummyCandidates = [
            (object)[
                'id' => 1,
                'name' => 'John Anderson',
                'email' => 'john.anderson@company.com',
                'position' => 'Senior Developer',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 85],
                    (object)['type' => 'communication', 'score' => 90],
                    (object)['type' => 'leadership', 'score' => 88],
                    (object)['type' => 'problem_solving', 'score' => 92],
                    (object)['type' => 'teamwork', 'score' => 87],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'PHP', 'level' => 'Expert'],
                    (object)['name' => 'Laravel', 'level' => 'Advanced'],
                    (object)['name' => 'MySQL', 'level' => 'Advanced'],
                ],
                'innovations' => [
                    (object)['title' => 'HR Analytics Dashboard', 'description' => 'Built comprehensive analytics platform'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Senior Developer', 'company' => 'Tech Corp', 'start_date' => '2020-01', 'end_date' => null],
                    (object)['title' => 'Developer', 'company' => 'Start Inc', 'start_date' => '2018-06', 'end_date' => '2019-12'],
                ],
                'educations' => [
                    (object)['degree' => 'Bachelor of Computer Science', 'institution' => 'MIT', 'graduation_year' => '2018'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-10-15', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 2,
                'name' => 'Sarah Martinez',
                'email' => 'sarah.martinez@company.com',
                'position' => 'Tech Lead',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 78],
                    (object)['type' => 'communication', 'score' => 95],
                    (object)['type' => 'leadership', 'score' => 91],
                    (object)['type' => 'problem_solving', 'score' => 86],
                    (object)['type' => 'teamwork', 'score' => 93],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'Python', 'level' => 'Expert'],
                    (object)['name' => 'Django', 'level' => 'Advanced'],
                    (object)['name' => 'PostgreSQL', 'level' => 'Intermediate'],
                ],
                'innovations' => [
                    (object)['title' => 'AI Recruitment Tool', 'description' => 'Developed ML-based candidate screening'],
                    (object)['title' => 'Employee Engagement App', 'description' => 'Mobile app for internal communication'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Tech Lead', 'company' => 'Innovation Labs', 'start_date' => '2021-03', 'end_date' => null],
                    (object)['title' => 'Senior Developer', 'company' => 'Digital Agency', 'start_date' => '2019-01', 'end_date' => '2021-02'],
                ],
                'educations' => [
                    (object)['degree' => 'Master of Software Engineering', 'institution' => 'Stanford University', 'graduation_year' => '2019'],
                    (object)['degree' => 'Bachelor of Information Technology', 'institution' => 'UC Berkeley', 'graduation_year' => '2017'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-09-20', 'status' => 'Fit'],
                    (object)['date' => '2023-09-15', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 3,
                'name' => 'Michael Chen',
                'email' => 'michael.chen@company.com',
                'position' => 'Full Stack Developer',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 94],
                    (object)['type' => 'communication', 'score' => 82],
                    (object)['type' => 'leadership', 'score' => 79],
                    (object)['type' => 'problem_solving', 'score' => 96],
                    (object)['type' => 'teamwork', 'score' => 84],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'JavaScript', 'level' => 'Expert'],
                    (object)['name' => 'React', 'level' => 'Expert'],
                    (object)['name' => 'Node.js', 'level' => 'Advanced'],
                    (object)['name' => 'MongoDB', 'level' => 'Advanced'],
                ],
                'innovations' => [
                    (object)['title' => 'Performance Monitoring System', 'description' => 'Real-time employee performance tracking'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Full Stack Developer', 'company' => 'WebTech Solutions', 'start_date' => '2019-08', 'end_date' => null],
                ],
                'educations' => [
                    (object)['degree' => 'Bachelor of Computer Engineering', 'institution' => 'Carnegie Mellon University', 'graduation_year' => '2019'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-08-10', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 4,
                'name' => 'Emily Johnson',
                'email' => 'emily.johnson@company.com',
                'position' => 'Engineering Manager',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 88],
                    (object)['type' => 'communication', 'score' => 91],
                    (object)['type' => 'leadership', 'score' => 95],
                    (object)['type' => 'problem_solving', 'score' => 89],
                    (object)['type' => 'teamwork', 'score' => 96],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'Java', 'level' => 'Expert'],
                    (object)['name' => 'Spring Boot', 'level' => 'Advanced'],
                    (object)['name' => 'Kubernetes', 'level' => 'Intermediate'],
                ],
                'innovations' => [
                    (object)['title' => 'Microservices Architecture', 'description' => 'Redesigned monolith to microservices'],
                    (object)['title' => 'CI/CD Pipeline', 'description' => 'Automated deployment pipeline'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Engineering Manager', 'company' => 'Enterprise Tech', 'start_date' => '2022-01', 'end_date' => null],
                    (object)['title' => 'Senior Engineer', 'company' => 'Cloud Services Inc', 'start_date' => '2019-05', 'end_date' => '2021-12'],
                ],
                'educations' => [
                    (object)['degree' => 'Master of Computer Science', 'institution' => 'Georgia Tech', 'graduation_year' => '2019'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-07-25', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 5,
                'name' => 'David Kim',
                'email' => 'david.kim@company.com',
                'position' => 'Solutions Architect',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 91],
                    (object)['type' => 'communication', 'score' => 85],
                    (object)['type' => 'leadership', 'score' => 83],
                    (object)['type' => 'problem_solving', 'score' => 94],
                    (object)['type' => 'teamwork', 'score' => 88],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'C#', 'level' => 'Expert'],
                    (object)['name' => '.NET Core', 'level' => 'Expert'],
                    (object)['name' => 'Azure', 'level' => 'Advanced'],
                ],
                'innovations' => [
                    (object)['title' => 'Cloud Migration Project', 'description' => 'Led migration to Azure cloud'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Solutions Architect', 'company' => 'Cloud First Ltd', 'start_date' => '2021-06', 'end_date' => null],
                    (object)['title' => 'Senior Developer', 'company' => 'Software House', 'start_date' => '2018-03', 'end_date' => '2021-05'],
                ],
                'educations' => [
                    (object)['degree' => 'Bachelor of Software Engineering', 'institution' => 'University of Washington', 'graduation_year' => '2018'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-06-30', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 6,
                'name' => 'Lisa Wang',
                'email' => 'lisa.wang@company.com',
                'position' => 'Backend Developer',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 87],
                    (object)['type' => 'communication', 'score' => 93],
                    (object)['type' => 'leadership', 'score' => 90],
                    (object)['type' => 'problem_solving', 'score' => 88],
                    (object)['type' => 'teamwork', 'score' => 95],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'Ruby', 'level' => 'Advanced'],
                    (object)['name' => 'Rails', 'level' => 'Advanced'],
                    (object)['name' => 'Redis', 'level' => 'Intermediate'],
                ],
                'innovations' => [
                    (object)['title' => 'API Gateway', 'description' => 'Built centralized API management system'],
                ],
                'positionHistories' => [
                    (object)['title' => 'Backend Developer', 'company' => 'Fintech Startup', 'start_date' => '2020-09', 'end_date' => null],
                ],
                'educations' => [
                    (object)['degree' => 'Bachelor of Computer Science', 'institution' => 'Cornell University', 'graduation_year' => '2020'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-05-15', 'status' => 'Fit'],
                ],
            ],
            (object)[
                'id' => 7,
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@company.com',
                'position' => 'DevOps Engineer',
                'softCompetencies' => [
                    (object)['type' => 'analytical_thinking', 'score' => 89],
                    (object)['type' => 'communication', 'score' => 87],
                    (object)['type' => 'leadership', 'score' => 92],
                    (object)['type' => 'problem_solving', 'score' => 90],
                    (object)['type' => 'teamwork', 'score' => 91],
                ],
                'technicalCompetencies' => [
                    (object)['name' => 'Go', 'level' => 'Advanced'],
                    (object)['name' => 'Docker', 'level' => 'Expert'],
                    (object)['name' => 'AWS', 'level' => 'Advanced'],
                ],
                'innovations' => [
                    (object)['title' => 'Container Orchestration', 'description' => 'Implemented enterprise-wide containerization'],
                    (object)['title' => 'DevOps Automation', 'description' => 'Automated infrastructure provisioning'],
                ],
                'positionHistories' => [
                    (object)['title' => 'DevOps Engineer', 'company' => 'Scale Systems', 'start_date' => '2020-02', 'end_date' => null],
                ],
                'educations' => [
                    (object)['degree' => 'Bachelor of Information Systems', 'institution' => 'University of Texas', 'graduation_year' => '2019'],
                ],
                'mcuHistories' => [
                    (object)['date' => '2024-04-10', 'status' => 'Fit'],
                ],
            ],
        ];
    }

    public function addColumn()
    {
        if ($this->columnCount < 5) {
            $this->columnCount++;
            $this->searchTerms[] = '';
            $this->searchResults[] = [];
            $this->showSearchDropdown[] = false;
            $this->selectedCandidates[] = null;
        }
    }

    public function removeColumn($index)
    {
        if ($this->columnCount > 2) {
            array_splice($this->searchTerms, $index, 1);
            array_splice($this->searchResults, $index, 1);
            array_splice($this->showSearchDropdown, $index, 1);
            array_splice($this->selectedCandidates, $index, 1);
            $this->columnCount--;
        }
    }

    public function updatedSearchTerms($value, $index)
    {
        if (strlen($value) >= 2) {
            $selectedIds = collect($this->selectedCandidates)->pluck('id')->toArray();

            $this->searchResults[$index] = collect($this->dummyCandidates)
                ->filter(function ($candidate) use ($value, $selectedIds) {
                    $matchesSearch = stripos($candidate->name, $value) !== false ||
                        stripos($candidate->email, $value) !== false ||
                        stripos($candidate->position, $value) !== false;

                    return $matchesSearch && !in_array($candidate->id, $selectedIds);
                })
                ->values()
                ->take(5)
                ->toArray();

            $this->showSearchDropdown[$index] = true;
        } else {
            $this->searchResults[$index] = [];
            $this->showSearchDropdown[$index] = false;
        }
    }

    public function selectCandidate($candidateId, $index)
    {
        $candidate = collect($this->dummyCandidates)->firstWhere('id', $candidateId);

        if ($candidate) {
            $this->selectedCandidates[$index] = $candidate;
            $this->searchTerms[$index] = $candidate->name;
            $this->showSearchDropdown[$index] = false;
            $this->searchResults[$index] = [];
        }
    }

    public function clearCandidate($index)
    {
        $this->selectedCandidates[$index] = null;
        $this->searchTerms[$index] = '';
        $this->searchResults[$index] = [];
        $this->showSearchDropdown[$index] = false;
    }

    public function closeDropdown($index)
    {
        $this->showSearchDropdown[$index] = false;
    }

    public function render()
    {
        return view('livewire.pages.candidates-comparison.index');
    }
}
