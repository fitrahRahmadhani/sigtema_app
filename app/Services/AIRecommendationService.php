<?php

namespace App\Services;

use App\Models\Position;
use App\Models\Employee;
use HosseinHezami\LaravelGemini\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    public function analyzeWithFileUpload(Position $position, array $employeeIds, $progressCallback = null)
    {
        try {
            if ($progressCallback) {
                $progressCallback(10, "Preparing candidate data...");
            }

            // Step 1: Get selected employees
            $employees = Employee::with([
                'technicalCompetencies',
                'educations',
                'innovations',
                'mcuResults',
                'burnout',
                'mbti',
                'disc',
                'papi',
                'positionHistories'
            ])
                ->whereIn('id', $employeeIds)
                ->get();

            if ($progressCallback) {
                $progressCallback(30, "Processing {$employees->count()} selected candidates");
            }

            if ($employees->isEmpty()) {
                Log::warning('No employees selected for analysis');
                return [];
            }

            // Step 2: Prepare data for Gemini
            $analysisData = $this->prepareAnalysisData($position, $employees);

            if ($progressCallback) {
                $progressCallback(50, "Sending data to AI for analysis...");
            }

            // Step 3: Call Gemini API
            $results = $this->callGeminiForAnalysis($position, $analysisData);

            if ($progressCallback) {
                $progressCallback(90, "Processing AI analysis results...");
            }

            // Step 4: Validate and format results
            $validatedResults = $this->validateAndFormatResults($results, $employees);

            if ($progressCallback) {
                $progressCallback(100, "Analysis complete!");
            }

            return $validatedResults;
        } catch (\Exception $e) {
            Log::error('AI Analysis Failed: ' . $e->getMessage(), [
                'position_id' => $position->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    private function prepareAnalysisData(Position $position, $employees)
    {
        $positionData = [
            'position_name' => $position->name,
            'band' => $position->band,
            'requirements' => $position->requirements->map(function ($req) {
                return [
                    'requirement' => $req->name,
                    'value' => $req->value
                ];
            })->toArray()
        ];

        $candidatesData = [];
        foreach ($employees as $employee) {
            $candidatesData[] = $this->formatEmployeeData($employee);
        }

        return [
            'position' => $positionData,
            'candidates' => $candidatesData
        ];
    }

    private function formatEmployeeData(Employee $employee)
    {
        $psychogram = $employee->getScores();

        return [
            'employee_id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'band' => $employee->band,
            'technical_skills' => $employee->technicalCompetencies->pluck('name')->toArray(),
            'education' => $employee->educations->map(function ($edu) {
                return [
                    'degree' => $edu->degree,
                    'major' => $edu->major,
                    'institution' => $edu->institution,
                    'graduation_year' => $edu->graduation_year
                ];
            })->toArray(),
            'psychogram' => [
                'strengths' => $psychogram['strengths']->pluck('aspect.name')->toArray(),
                'weaknesses' => $psychogram['weaknesses']->pluck('aspect.name')->toArray()
            ],
            'assessments' => [
                'mbti' => $employee->mbti?->mbti,
                'disc' => $employee->disc ? [
                    'interpretation1' => $employee->disc->interpretation1,
                    'interpretation2' => $employee->disc->interpretation2,
                    'interpretation3' => $employee->disc->interpretation3
                ] : null,
                'papi' => $employee->papi ? $employee->papi->only([
                    'N',
                    'G',
                    'A',
                    'L',
                    'P',
                    'I',
                    'T',
                    'V',
                    'S',
                    'B',
                    'O',
                    'X',
                    'C',
                    'D',
                    'R',
                    'Z',
                    'E',
                    'K',
                    'F',
                    'W'
                ]) : null
            ],
            'innovations' => $employee->innovations->map(function ($inn) {
                return [
                    'title' => $inn->title,
                    'status' => $inn->status,
                    'achievement' => $inn->achievement,
                    'year' => $inn->year
                ];
            })->toArray(),
            'medical_checkup' => $employee->mcuResults->map(function ($mcu) {
                return [
                    'year'   => $mcu->year,
                    'result' => $mcu->result . ' /5 star',
                ];
            })->toArray(),
            'burnout_status' => $employee->burnout?->result,
            'current_position' => $employee->position?->name,
            'position_history' => $employee->positionHistories->map(function ($hist) {
                return [
                    'position' => $hist->position_name,
                    'work_unit' => $hist->work_unit,
                    'start_date' => $hist->start_date,
                    'end_date' => $hist->end_date,
                    'duration_months' => $hist->duration_months
                ];
            })->toArray(),
            'total_experience_years' => $employee->positionHistories->sum('duration_months') / 12
        ];
    }

    private function callGeminiForAnalysis(Position $position, $analysisData)
    {
        $dataJson = json_encode($analysisData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = $this->createAnalysisPrompt($position, $dataJson);

        Log::info('Calling Gemini API for position analysis', [
            'position_id' => $position->id,
            'candidate_count' => count($analysisData['candidates'])
        ]);

        try {
            $response = Gemini::text()
                ->model('gemini-2.5-flash-lite')
                ->prompt($prompt)
                ->temperature(0.2)
                ->maxTokens(5000)
                ->structuredSchema([
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => ['type' => 'integer'],
                            'match_score' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100],
                            'reason' => ['type' => 'string', 'maxLength' => 300],
                            'strengths' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'minItems' => 0,
                                'maxItems' => 3
                            ],
                            'weaknesses' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'minItems' => 0,
                                'maxItems' => 2
                            ],
                            'recommendation' => ['type' => 'string', 'maxLength' => 200]
                        ],
                        'required' => ['employee_id', 'match_score', 'reason']
                    ]
                ])
                ->generate();

            $content = $response->content();

            $results = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini response', [
                    'error' => json_last_error_msg()
                ]);
                throw new \Exception('Invalid JSON response from AI');
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Gemini API call failed', [
                'error' => $e->getMessage(),
                'position_id' => $position->id
            ]);
            throw $e;
        }
    }

    private function createAnalysisPrompt(Position $position, $dataJson)
    {
        return <<<PROMPT
You are an expert HR consultant and organizational psychologist with 20+ years experience in talent assessment and job matching. Your analysis must be CRITICAL and OBJECTIVE.
TASK: Analyze candidates for this position and provide HONEST assessment.
POSITION: {$position->name} (Band: {$position->band})
CRITICAL EVALUATION GUIDELINES:
1. Be STRICT and REALISTIC - if candidates are not suitable, give LOW scores (0-40)
2. Consider MINIMUM requirements vs NICE-TO-HAVE
3. Penalize significantly for:
   - Major skill gaps
   - Insufficient experience
   - Health/burnout concerns
   - Personality-job misfit
   - Band level mismatch
   - Band 1 for top management
   - Band 2 equal to senior manager
   - Band 3 equal to manager or officer
   - Band 4 equal to jr officer
   - Band 5 equal to staff

4. Reward appropriately for:
   - Exact skill match
   - Relevant achievements
   - Career progression
   - Assessment alignment
   - Innovation record

ANALYSIS FRAMEWORK:
- Score 0-30: Not suitable (major gaps)
- Score 31-50: Marginally suitable (needs significant development)
- Score 51-70: Moderately suitable (meets basic requirements)
- Score 71-85: Well suited (exceeds some requirements)
- Score 86-100: Excellent fit (exceeds most requirements)

DATA FOR ANALYSIS:
{$dataJson}

OUTPUT REQUIREMENTS:
1. For EACH candidate, provide:
   - match_score: 0-100 based on CRITICAL assessment
   - reason: BRIEF explanation in English (2-3 sentences max)
   - strengths: 0-5 key strengths relevant to THIS position
   - weaknesses: 0-5 critical gaps/concerns
   - recommendation: "Highly Recommended", "Recommended", or "Not Recommended" based on score

2. Sort by match_score descending
3. If NO candidate scores above 50, return empty array
4. Be BRUTALLY HONEST - better empty results than false positives

RESPONSE LANGUAGE: INDONESIAN (professional business Indonesian)
JSON FORMAT ONLY - no additional text.
PROMPT;
    }

    private function validateAndFormatResults($results, $employees)
    {
        if (!is_array($results)) {
            return [];
        }

        $employeeIds = $employees->pluck('id')->toArray();
        $validResults = [];

        foreach ($results as $result) {
            if (!isset($result['employee_id']) || !isset($result['match_score'])) {
                continue;
            }

            if (!in_array($result['employee_id'], $employeeIds)) {
                continue;
            }

            $score = intval($result['match_score']);
            if ($score < 0) $score = 0;
            if ($score > 100) $score = 100;

            // Filter out very low scores
            if ($score < 30) {
                continue;
            }

            $validResults[] = [
                'employee_id' => intval($result['employee_id']),
                'match_score' => $score,
                'reason' => $result['reason'] ?? 'Analysis not available',
                'strengths' => $result['strengths'] ?? [],
                'weaknesses' => $result['weaknesses'] ?? [],
                'recommendation' => $this->getRecommendationLabel($score)
            ];
        }

        // Sort by score descending
        usort($validResults, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return array_slice($validResults, 0, 10);
    }

    private function getRecommendationLabel($score)
    {
        if ($score >= 80) return 'Highly Recommended';
        if ($score >= 60) return 'Recommended';
        return 'Not Recommended';
    }
}
