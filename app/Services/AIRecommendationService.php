<?php

namespace App\Services;

use App\Models\Position;
use App\Models\Employee;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    public function analyzeWithAI(Position $position, array $employeeIds, $progressCallback = null)
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

        // MBTI
        if ($employee->mbti?->mbti) {
            $data['mbti'] = $employee->mbti->mbti;
        }

        // DISC - only include if at least one interpretation is valid
        if ($employee->disc) {
            $validInterpretations = array_values(array_filter([
                $employee->disc->interpretation1,
                $employee->disc->interpretation2,
                $employee->disc->interpretation3,
            ], fn($i) => $i && $i !== '(Skor tidak valid)'));

            if (!empty($validInterpretations)) {
                $data['disc'] = $validInterpretations;
            }
        }

        // PAPI Kostick - grouped by categories with averages
        if ($employee->papi) {
            $papi = $employee->papi;
            $papiData = [];

            // Arah Kerja: N, G, A
            $arahKerjaScores = array_filter([
                $papi->N, $papi->G, $papi->A
            ], fn($s) => $s !== null);
            if (!empty($arahKerjaScores)) {
                $papiData['arah_kerja'] = round(array_sum($arahKerjaScores) / count($arahKerjaScores), 1);
            }

            // Kepemimpinan: L, P, I
            $kepemimpinanScores = array_filter([
                $papi->L, $papi->P, $papi->I
            ], fn($s) => $s !== null);
            if (!empty($kepemimpinanScores)) {
                $papiData['kepemimpinan'] = round(array_sum($kepemimpinanScores) / count($kepemimpinanScores), 1);
            }

            // Aktivitas: T, V
            $aktivitasScores = array_filter([$papi->T, $papi->V], fn($s) => $s !== null);
            if (!empty($aktivitasScores)) {
                $papiData['aktivitas'] = round(array_sum($aktivitasScores) / count($aktivitasScores), 1);
            }

            // Pergaulan: O, B, S, X
            $pergaulanScores = array_filter([
                $papi->O, $papi->B, $papi->S, $papi->X
            ], fn($s) => $s !== null);
            if (!empty($pergaulanScores)) {
                $papiData['pergaulan'] = round(array_sum($pergaulanScores) / count($pergaulanScores), 1);
            }

            // Gaya Kerja: C, D, R
            $gayaKerjaScores = array_filter([
                $papi->C, $papi->D, $papi->R
            ], fn($s) => $s !== null);
            if (!empty($gayaKerjaScores)) {
                $papiData['gaya_kerja'] = round(array_sum($gayaKerjaScores) / count($gayaKerjaScores), 1);
            }

            // Sifat: Z, E, K
            $sifatScores = array_filter([
                $papi->Z, $papi->E, $papi->K
            ], fn($s) => $s !== null);
            if (!empty($sifatScores)) {
                $papiData['sifat'] = round(array_sum($sifatScores) / count($sifatScores), 1);
            }

            // Ketaatan: F, W
            $ketaatanScores = array_filter([$papi->F, $papi->W], fn($s) => $s !== null);
            if (!empty($ketaatanScores)) {
                $papiData['ketaatan'] = round(array_sum($ketaatanScores) / count($ketaatanScores), 1);
            }

            if (!empty($papiData)) {
                $data['papi'] = $papiData;
            }
        }

        if (!empty($data['assessments']) || isset($data['mbti']) || isset($data['disc']) || isset($data['papi'])) {
            $assessments = array_filter([
                'mbti' => $data['mbti'] ?? null,
                'disc' => $data['disc'] ?? null,
                'papi' => $data['papi'] ?? null,
            ]);
            if (!empty($assessments)) {
                $data['assessments'] = $assessments;
            }
            // Clean up individual fields
            unset($data['mbti'], $data['disc'], $data['papi']);
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

        // Burnout - send only level
        if ($employee->burnout?->level) {
            $data['burnout_level'] = $employee->burnout->level;
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
            Expert HR consultant di industri semen dengan latar belakang psikologi mendalam. Role: {$position->name} (Band {$position->band}).
            {$requirementsText}
            BANDS: 1=TopMgmt (High) 2=SrMgr 3=Mgr 4=JrOfficer 5=Staff (Low)
            SCORING: 0-30=Unfit 31-50=Marginal 51-70=Moderate 71-85=Good 86-100=Excellent
            PENALIZE: skill gaps, low experience, burnout level (lower is better), irrelevant bg, band gap >1 without potential
            REWARD: skill match, relevant exp, progression, certs, innovation, achievements, cross-functional exp, current_position (if relevant)
            NOTE: 1-level band gap allowed if strong potential shown. MCU is minor factor, if it falls under a strength point, display the score only

            PAPI KOSTICK CATEGORIES (rerata dari skor individual):
            - arah_kerja: rerata N, G, A (Menyelesaikan Tugas Pribadi, Pekerja Keras, Hasrat Berprestasi)
            - kepemimpinan: rerata L, P, I (Peran sbg Pemimpin, Mengendalikan Orang Lain, Mudah Memutuskan)
            - aktivitas: rerata T, V (Tempo Kerja, Stamina)
            - pergaulan: rerata O, B, S, X (Kebutuhan utk Mendekati & Menyayangi, Kebutuhan thd Kelompok, Pergaulan Sosial, Mendapatkan Perhatian)
            - gaya_kerja: rerata C, D, R (Suka Pekerjaan Teratur, Suka Pekerjaan Terperinci, Tipe Teoritis)
            - sifat: rerata Z, E, K (Hasrat Berubah, Pengendalian Emosi, Agresivitas)
            - ketaatan: rerata F, W (Dukungan utk Arahan, Kebutuhan Taat Aturan)

            DATA: {$dataJson}

            OUTPUT: JSON array, each item:
            - "employee_id": int
            - "match_score": int
            - "reason": string (3-4 sentences in Bahasa Indonesia)
            - "strengths": 0 - 3 strings, WHY each fits THIS role
            - "weaknesses": 0 - 3 strings, WHY each is a gap FOR THIS role
            - "psychological_insight": {
                "summary": string (comprehensive psychological profile analysis in Bahasa Indonesia, 3-4 sentences covering personality traits, work style, interpersonal approach, and overall fit for this role),
                "personality_fit": string (detailed analysis of how PAPI categories, MBTI, and DISC results align with the role requirements in Bahasa Indonesia)
              }

            RULES: sort desc, [] if none >50, be honest. psychological_insight should be deeply analyzed based on PAPI categories (arah_kerja, kepemimpinan, aktivitas, pergaulan, gaya_kerja, sifat, ketaatan), MBTI type, and DISC patterns if available.
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
                'employee_id'         => intval($result['employee_id']),
                'match_score'         => $score,
                'reason'              => $result['reason']              ?? 'Analisis tidak tersedia',
                'strengths'           => $result['strengths']           ?? [],
                'weaknesses'          => $result['weaknesses']          ?? [],
                'psychological_insight' => $result['psychological_insight'] ?? null,
                'recommendation'     => $this->getRecommendationLabel($score)
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
