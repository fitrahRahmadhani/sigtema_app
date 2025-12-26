<?php

namespace App\Services;

use App\Models\Position;
use App\Models\Employee;
use HosseinHezami\LaravelGemini\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class FileBasedAIRecommendationService
{
    private const MAX_EMPLOYEES = 1000;

    public function analyzeWithFileUpload(Position $position, $progressCallback = null)
    {
        try {
            if ($progressCallback) {
                $progressCallback(10, "Preparing analysis...");
            }

            // Step 1: Get eligible employees
            $employees = $this->getEligibleEmployees($position);

            if ($progressCallback) {
                $progressCallback(30, "Found {$employees->count()} eligible candidates");
            }

            if ($employees->isEmpty()) {
                Log::warning('No eligible employees found for position: ' . $position->id);
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
                $progressCallback(90, "Processing AI results...");
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

            // Fallback to simple scoring jika API gagal
            if ($progressCallback) {
                $progressCallback(100, "Using alternative analysis...");
            }

            return $this->fallbackAnalysis($position, $employees ?? collect([]));
        }
    }

    private function prepareAnalysisData(Position $position, $employees)
    {
        // Format data untuk dikirim ke Gemini
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
            'name' => $employee->name,
            'employee_number' => $employee->employee_number,
            'band' => $employee->band,
            'technical_skills' => $employee->technicalCompetencies->pluck('name')->toArray(),
            'education' => $employee->educations->map(function ($edu) {
                return [
                    'degree' => $edu->degree,
                    'major' => $edu->major,
                    'institution' => $edu->institution
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
                    'achievement' => $inn->achievement
                ];
            })->toArray(),
            'medical_checkup' => $employee->mcuResults->map(function ($mcu) {
                return [
                    'year' => $mcu->year,
                    'result' => $mcu->result
                ];
            })->toArray(),
            'burnout_status' => $employee->burnout?->result,
            'position_history' => $employee->positionHistories->map(function ($hist) {
                return [
                    'position' => $hist->position_name,
                    'work_unit' => $hist->work_unit,
                    'start_date' => $hist->start_date,
                    'end_date' => $hist->end_date
                ];
            })->toArray()
        ];
    }

    private function callGeminiForAnalysis(Position $position, $analysisData)
    {
        // Konversi data ke JSON string untuk prompt
        $dataJson = json_encode($analysisData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Buat prompt yang jelas untuk Gemini
        $prompt = $this->createAnalysisPrompt($position, $dataJson);

        Log::info('Calling Gemini API for position analysis', [
            'position_id' => $position->id,
            'candidate_count' => count($analysisData['candidates']),
            'prompt_length' => strlen($prompt)
        ]);

        try {
            $response = Gemini::text()
                ->model('gemini-2.5-flash-lite') // Model yang support context panjang
                ->prompt($prompt)
                ->temperature(0.3) // Sedikit kreatifitas
                ->maxTokens(4000) // Token cukup untuk response
                ->structuredSchema([
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => ['type' => 'integer'],
                            'match_score' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100],
                            'reason' => ['type' => 'string', 'maxLength' => 500],
                            'strengths' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'minItems' => 1,
                                'maxItems' => 3
                            ],
                            'considerations' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'minItems' => 0,
                                'maxItems' => 2
                            ]
                        ],
                        'required' => ['employee_id', 'match_score', 'reason']
                    ]
                ])
                ->generate();

            $content = $response->content();
            Log::info('Gemini API Response received', [
                'content_length' => strlen($content),
                'model_used' => $response->model()
            ]);

            $results = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini response', [
                    'content' => $content,
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
You are an expert HR analyst specializing in candidate-job matching. Analyze the following candidates for the specified position and provide your top 10 recommendations.

POSITION DETAILS:
{$position->name} (Band: {$position->band})

CANDIDATES DATA (in JSON format):
{$dataJson}

ANALYSIS INSTRUCTIONS:
1. For EACH candidate, analyze their suitability based on:
   - Technical skills alignment with position requirements
   - Educational background relevance
   - Psychogram strengths matching position needs
   - Assessment results (MBTI, DISC, PAPI personality fit)
   - Innovation track record and achievements
   - Medical fitness for the role
   - Burnout risk assessment
   - Career progression and relevant experience

2. Score each candidate 0-100 based on overall fit.

3. For each recommended candidate, provide:
   - A concise reason for the match score (1-2 sentences)
   - 2-3 key strengths relevant to this specific position
   - 1-2 considerations or development areas

4. Return ONLY the top 10 best matches, sorted by match score (highest first).

IMPORTANT: Your response MUST be a valid JSON array following the exact schema provided. Do not include any explanatory text outside the JSON.
PROMPT;
    }

    private function validateAndFormatResults($results, $employees)
    {
        if (!is_array($results)) {
            throw new \Exception('Invalid results format from AI');
        }

        $employeeIds = $employees->pluck('id')->toArray();
        $validResults = [];

        foreach ($results as $result) {
            // Validasi data
            if (!isset($result['employee_id']) || !isset($result['match_score'])) {
                Log::warning('Skipping invalid result item', ['item' => $result]);
                continue;
            }

            // Pastikan employee_id valid
            if (!in_array($result['employee_id'], $employeeIds)) {
                Log::warning('Skipping result with invalid employee_id', [
                    'employee_id' => $result['employee_id'],
                    'valid_ids' => $employeeIds
                ]);
                continue;
            }

            // Validasi dan format score
            $score = intval($result['match_score']);
            if ($score < 0) $score = 0;
            if ($score > 100) $score = 100;

            $validResults[] = [
                'employee_id' => intval($result['employee_id']),
                'match_score' => $score,
                'reason' => $result['reason'] ?? 'No reason provided',
                'strengths' => $result['strengths'] ?? [],
                'considerations' => $result['considerations'] ?? []
            ];

            // Batasi hanya 10 terbaik
            if (count($validResults) >= 10) {
                break;
            }
        }

        // Sort by score descending
        usort($validResults, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return $validResults;
    }

    private function fallbackAnalysis(Position $position, $employees)
    {
        Log::info('Using fallback analysis for position: ' . $position->id);

        if ($employees->isEmpty()) {
            return [];
        }

        $results = [];
        foreach ($employees as $employee) {
            $score = $this->calculateBasicMatchScore($position, $employee);

            $results[] = [
                'employee_id' => $employee->id,
                'match_score' => $score,
                'reason' => 'Basic compatibility analysis (AI analysis unavailable)',
                'strengths' => ['Eligible based on band and company criteria'],
                'considerations' => ['Full AI analysis was not available for this evaluation']
            ];
        }

        // Sort and limit to top 10
        usort($results, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return array_slice($results, 0, 10);
    }

    private function calculateBasicMatchScore(Position $position, Employee $employee)
    {
        $score = 50; // Base score

        // Band match (40 points max)
        $positionBand = $this->extractBandNumber($position->band);
        $employeeBand = $this->extractBandNumber($employee->band);

        if ($employeeBand === $positionBand) {
            $score += 40;
        } elseif ($employeeBand === ($positionBand + 1)) {
            $score += 20; // One level below
        }

        // Technical skills (30 points max)
        if ($employee->technicalCompetencies->count() > 0) {
            $score += min(30, $employee->technicalCompetencies->count() * 5);
        }

        // Education (20 points max)
        if ($employee->educations->count() > 0) {
            $score += min(20, $employee->educations->count() * 4);
        }

        return min($score, 100);
    }

    private function getEligibleEmployees(Position $position)
    {
        $positionBand = $this->extractBandNumber($position->band);

        return Employee::with([
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
            ->where('company_id', $position->company_id)
            ->where(function ($query) use ($positionBand) {
                $query->where('band', $this->formatBand($positionBand));

                if ($positionBand < 5) {
                    $nextBand = $positionBand + 1;
                    $query->orWhere('band', $this->formatBand($nextBand));
                }
            })
            ->limit(20)
            ->get();
    }

    private function extractBandNumber($bandString)
    {
        if (preg_match('/Band\s*(\d+)/i', $bandString, $matches)) {
            return (int) $matches[1];
        }
        return is_numeric($bandString) ? (int) $bandString : 5;
    }

    private function formatBand($number)
    {
        return "Band " . $number;
    }
}
