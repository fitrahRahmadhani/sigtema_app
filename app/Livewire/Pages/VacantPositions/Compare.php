<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use App\Models\Position;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('AI Candidates Comparison')]
class Compare extends Component
{
    public Position $position;
    public string $positionSlug;
    public array $candidateIds = [];
    public $selectedEmployees;
    public array $aiRecommendations = [];

    public function mount(Position $position)
    {
        $this->position = $position;
        $this->positionSlug = $position->slug;

        // Get candidate IDs from query parameter
        $candidatesParam = request()->query('candidates');

        if (!$candidatesParam) {
            Toaster::warning('No candidates selected for comparison');
            return redirect()->route('vacant-positions.show', ['position' => $position->slug]);
        }

        $this->candidateIds = explode(',', $candidatesParam);

        // Validate that we have between 2-5 candidates
        if (count($this->candidateIds) < 2 || count($this->candidateIds) > 5) {
            Toaster::error('Please select 2-5 candidates to compare');
            return redirect()->route('vacant-positions.show', ['position' => $position->slug]);
        }

        // Load employees dengan semua relasi (tanpa softCompetencies eager loading)
        $this->selectedEmployees = Employee::with([
            'position',
            'educations',
            'technicalCompetencies',
            'mcuResults',
            'positionHistories',
            'innovations',
            'company',
            'papi',
            'disc',
            'mbti',
            'burnout',
        ])->whereIn('id', $this->candidateIds)->get();

        // Load AI recommendations from cache
        $this->loadAIRecommendations();
    }

    private function loadAIRecommendations()
    {
        $cacheKey = "ai_analysis_{$this->position->id}";
        $cachedResults = Cache::get($cacheKey);

        if ($cachedResults) {
            $this->aiRecommendations = array_values(array_filter($cachedResults, function ($rec) {
                return in_array($rec['employee_id'], $this->candidateIds);
            }));
        }
    }

    // Helper method untuk mendapatkan AI info per employee
    public function getAIInfoForEmployee($employeeId)
    {
        foreach ($this->aiRecommendations as $recommendation) {
            if ($recommendation['employee_id'] == $employeeId) {
                return $recommendation;
            }
        }
        return null;
    }

    // Helper untuk mendapatkan data soft competencies - FIXED
    public function getEmployeeSoftCompetencies($employee)
    {
        try {
            $scores = $employee->getScores();

            return [
                'strengths' => collect($scores['strengths'] ?? [])->map(function ($item) {
                    return $item->aspect->aspect_name ?? $item;
                })->toArray(),
                'weaknesses' => collect($scores['weaknesses'] ?? [])->map(function ($item) {
                    return $item->aspect->aspect_name ?? $item;
                })->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'strengths' => [],
                'weaknesses' => []
            ];
        }
    }

    // Helper untuk mendapatkan data PAPI yang diformat
    public function getEmployeePAPIData($employee)
    {
        if ($employee->papi) {
            return $employee->papi->getFormattedData();
        }

        // Default data jika tidak ada PAPI
        return array_map(function ($code) {
            return ['code' => $code, 'score' => 0];
        }, ['N', 'G', 'A', 'L', 'P', 'I', 'T', 'V', 'O', 'B', 'S', 'X', 'C', 'D', 'R', 'Z', 'E', 'K', 'F', 'W']);
    }

    // Helper untuk mendapatkan data DISC
    public function getEmployeeDISCData($employee)
    {
        if (!$employee->disc) {
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

        return [
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
    }

    // Helper untuk mendapatkan data burnout
    public function getEmployeeBurnoutData($employee)
    {
        if (!$employee->burnout) {
            return [
                'emotional_exhaustion' => '-',
                'depersonalization' => '-',
                'achievement_decline' => '-',
                'result' => '-',
            ];
        }

        return [
            'emotional_exhaustion' => $employee->burnout->emotional_exhaustion ?? '-',
            'depersonalization' => $employee->burnout->depersonalization ?? '-',
            'achievement_decline' => $employee->burnout->achievement_decline ?? '-',
            'result' => $employee->burnout->result ?? '-',
        ];
    }

    public function goBack()
    {
        return redirect()->route('vacant-positions.show', $this->positionSlug);
    }

    public function render()
    {
        return view('livewire.pages.vacant-positions.compare');
    }
}
