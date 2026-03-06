<?php

namespace App\Services;

use App\Models\Position;
use App\Models\Employee;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    public function analyzeWithFileUpload(Position $position, array $employeeIds, $progressCallback = null)
    {

        try {
            if ($progressCallback) {
                $progressCallback(10, "Menyiapkan data kandidat...");
            }

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
                $progressCallback(30, "Memproses {$employees->count()} kandidat terpilih...");
            }

            $analysisData = $this->prepareAnalysisData($position, $employees);

            if ($progressCallback) {
                $progressCallback(50, "Mengirim data ke AI untuk dianalisis...");
            }

            $results = $this->callGroqForAnalysis($position, $analysisData);

            if ($progressCallback) {
                $progressCallback(90, "Memproses hasil analisis AI...");
            }

            $validatedResults = $this->validateAndFormatResults($results, $employees);

            if ($progressCallback) {
                $progressCallback(100, "Analisis selesai!");
            }

            return $validatedResults;
        } catch (\Exception $e) {
            Log::error('AI Analysis Failed: ' . $e->getMessage(), [
                'position_id' => $position->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    private function prepareAnalysisData(Position $position, $employees)
    {
        $positionData = [
            'position_name' => $position->name,
            'band'          => $position->band,
            'requirements'  => $position->requirements->map(function ($req) {
                return [
                    'requirement' => $req->name,
                    'value'       => $req->value
                ];
            })->toArray()
        ];

        $candidatesData = [];
        foreach ($employees as $employee) {
            $candidatesData[] = $this->formatEmployeeData($employee);
        }

        return [
            'position'   => $positionData,
            'candidates' => $candidatesData
        ];
    }

    private function formatEmployeeData(Employee $employee)
    {
        $psychogram = $employee->getScores();

        $data = [
            'id'   => $employee->id,
            'band' => $employee->band,
        ];

        if ($employee->technicalCompetencies->isNotEmpty()) {
            $data['skills'] = $employee->technicalCompetencies->pluck('name')->toArray();
        }

        if ($employee->educations->isNotEmpty()) {
            $data['edu'] = $employee->educations->map(fn($e) => array_filter([
                'degree' => $e->degree,
                'major'  => $e->major,
            ]))->values()->toArray();
        }

        $strengths  = $psychogram['strengths']->pluck('aspect.name')->toArray();
        $weaknesses = $psychogram['weaknesses']->pluck('aspect.name')->toArray();
        if (!empty($strengths) || !empty($weaknesses)) {
            $data['psychogram'] = array_filter([
                'strengths'  => $strengths  ?: null,
                'weaknesses' => $weaknesses ?: null,
            ]);
        }

        $assessments = array_filter([
            'mbti' => $employee->mbti?->mbti,
            'disc' => $employee->disc ? array_values(array_filter([
                $employee->disc->interpretation1,
                $employee->disc->interpretation2,
                $employee->disc->interpretation3,
            ])) : null,
            'papi' => $employee->papi ? array_filter(
                $employee->papi->only(['N', 'G', 'A', 'L', 'P', 'I', 'T', 'V', 'S', 'B', 'O', 'X', 'C', 'D', 'R', 'Z', 'E', 'K', 'F', 'W'])
            ) : null,
        ]);
        if (!empty($assessments)) {
            $data['assessments'] = $assessments;
        }

        if ($employee->innovations->isNotEmpty()) {
            $data['innovations'] = $employee->innovations->map(fn($i) => array_filter([
                'title'       => $i->title,
                'status'      => $i->status,
                'achievement' => $i->achievement,
            ]))->values()->toArray();
        }

        if ($employee->mcuResults->isNotEmpty()) {
            $data['mcu'] = $employee->mcuResults->map(fn($m) => [
                'year'   => $m->year,
                'result' => $m->result . ' / 5 stars',
            ])->toArray();
        }

        if ($employee->burnout?->result) {
            $data['burnout'] = $employee->burnout->result;
        }

        if ($employee->position?->name) {
            $data['current_position'] = $employee->position->name;
        }

        if ($employee->positionHistories->isNotEmpty()) {
            $data['position_history'] = $employee->positionHistories->map(fn($h) => array_filter([
                'position'    => $h->position_name,
                'unit'   => $h->work_unit,
                'start'  => $h->start_date ? substr($h->start_date, 0, 7) : null,
                'end'    => $h->end_date   ? substr($h->end_date, 0, 7)   : null,
            ]))->values()->toArray();
        }

        return $data;
    }

    private function callGroqForAnalysis(Position $position, array $analysisData): array
    {
        $model    = config('groq.model');
        $dataJson = json_encode($analysisData, JSON_UNESCAPED_UNICODE);
        $prompt   = $this->createAnalysisPrompt($position, $dataJson);

        try {
            $response = Groq::chat()->completions()->create([
                'model'       => $model,
                'temperature' => 0.2,
                'max_tokens'  => 8000,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are an expert HR consultant. Respond ONLY with a valid JSON array. No markdown, no explanation, no extra text. Just the raw JSON array.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            $content = $response['choices'][0]['message']['content'] ?? '';

            // Bersihkan jika ada markdown code block dari response
            $content = preg_replace('/^```(?:json)?\s*/i', '', trim($content));
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);

            $results = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Groq response', [
                    'error'   => json_last_error_msg(),
                    'content' => substr($content, 0, 500),
                ]);
                throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }

            // Groq kadang membungkus dalam key — coba extract array-nya
            if (is_array($results) && !isset($results[0])) {
                $results = $results['candidates']
                    ?? $results['results']
                    ?? $results['data']
                    ?? array_values($results);
            }

            return is_array($results) ? $results : [];
        } catch (\Exception $e) {
            Log::error('Groq API call failed', [
                'error'       => $e->getMessage(),
                'position_id' => $position->id,
            ]);
            throw $e;
        }
    }

    private function createAnalysisPrompt(Position $position, string $dataJson): string
    {
        $requirementsText = '';
        if ($position->requirements->isNotEmpty()) {
            $requirementsText = "\nREQ:\n";
            foreach ($position->requirements as $req) {
                $requirementsText .= "- {$req->name}: {$req->value}\n";
            }
        }

        return <<<PROMPT
            Expert HR consultant. Role: {$position->name} (Band {$position->band}).
            {$requirementsText}
            BANDS: 1=TopMgmt (High) 2=SrMgr 3=Mgr 4=JrOfficer 5=Staff (Low)
            SCORING: 0-30=Unfit 31-50=Marginal 51-70=Moderate 71-85=Good 86-100=Excellent
            PENALIZE: skill gaps, low experience, burnout (lower is better), irrelevant bg, band gap >1 without potential
            REWARD: skill match, relevant exp, progression, certs, innovation, achievements, cross-functional exp, current_position (if relevant)
            NOTE: 1-level band gap allowed if strong potential shown.  MCU is minor factor; if strength, state value only.

            DATA: {$dataJson}

            OUTPUT: JSON array, each item:
            - "employee_id": int
            - "match_score": int
            - "reason": string (2-3 sentences in Bahasa Indonesia)
            - "strengths": max 3 strings, WHY each fits THIS role
            - "weaknesses": max 3 strings, WHY each is a gap FOR THIS role

            RULES: sort desc, [] if none >50, be honest.
            JSON ONLY.
            PROMPT;
    }

    private function validateAndFormatResults($results, $employees): array
    {
        if (!is_array($results)) {
            return [];
        }

        $employeeIds  = $employees->pluck('id')->toArray();
        $validResults = [];

        foreach ($results as $result) {
            if (!isset($result['employee_id']) || !isset($result['match_score'])) {
                continue;
            }

            if (!in_array($result['employee_id'], $employeeIds)) {
                continue;
            }

            $score = intval($result['match_score']);
            $score = max(0, min(100, $score));

            if ($score < 30) {
                continue;
            }

            $validResults[] = [
                'employee_id'    => intval($result['employee_id']),
                'match_score'    => $score,
                'reason'         => $result['reason']     ?? 'Analisis tidak tersedia',
                'strengths'      => $result['strengths']  ?? [],
                'weaknesses'     => $result['weaknesses'] ?? [],
                'recommendation' => $this->getRecommendationLabel($score)
            ];
        }

        usort($validResults, fn($a, $b) => $b['match_score'] <=> $a['match_score']);

        return array_slice($validResults, 0, 10);
    }

    private function getRecommendationLabel(int $score): string
    {
        if ($score >= 80) return 'Highly Recommended';
        if ($score >= 60) return 'Recommended';
        return 'Not Recommended';
    }
}
