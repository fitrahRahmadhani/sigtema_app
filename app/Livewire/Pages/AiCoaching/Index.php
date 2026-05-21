<?php

namespace App\Livewire\Pages\AiCoaching;

use App\Models\Employee;
use App\Models\EmployeeHierarchy;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
#[Title('AI Coaching')]
class Index extends Component
{
    // Auth state
    public $isRegistered         = false;
    public $employee             = null;
    public $subordinates         = [];
    public $hasSubordinates      = false;

    // Permission state
    public $hasFullAccess       = false; // super_user_ai_coaching permission

    // Coaching target
    public $coachingMode        = null; // self, subordinate, human_captain
    public $targetEmployee      = null;
    public $selectedSubordinate = null;

    // Human Captain - search state
    public $searchEmployee      = '';
    public $searchResults        = [];

    // Chat state
    public $messages   = [];
    public $userInput  = '';
    public $isLoading = false;
    public $maxChars   = 200;
    public $chatActive = false;

    public function mount()
    {
        $user = Auth::user();

        // Check for super_user_ai_coaching permission
        $this->hasFullAccess = $user->hasDirectPermission('super_user_ai_coaching');

        $employee = Employee::with([
            'technicalCompetencies',
            'educations',
            'innovations',
            'mcuResults',
            'burnout',
            'mbti',
            'disc',
            'papi',
            'positionHistories',
            'position',
        ])->where('email', $user->email)->first();

        if (!$employee) {
            $this->isRegistered = false;
            return;
        }

        $this->isRegistered = true;
        $this->employee     = $employee;

        $this->loadSubordinates();

        if (!$this->hasSubordinates && !$this->hasFullAccess) {
            $this->startCoaching('self');
        }
    }

    private function loadSubordinates(): void
    {
        if (!$this->employee->position) {
            $this->hasSubordinates = false;
            $this->subordinates    = [];
            return;
        }

        $positionId = $this->employee->position->id;
        $companyId  = $this->employee->company_id;

        $subordinatePositionIds = EmployeeHierarchy::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->pluck('position_id')
            ->toArray();

        if (empty($subordinatePositionIds)) {
            $this->hasSubordinates = false;
            $this->subordinates    = [];
            return;
        }

        $subordinateEmployees = Employee::with(['position:id,name,employee_id'])
            ->whereHas('position', fn($q) => $q->whereIn('id', $subordinatePositionIds))
            ->where('company_id', $companyId)
            ->get();

        if ($subordinateEmployees->isEmpty()) {
            $this->hasSubordinates = false;
            $this->subordinates    = [];
            return;
        }

        $this->hasSubordinates = true;
        $this->subordinates    = $subordinateEmployees->map(fn($emp) => [
            'id'              => $emp->id,
            'name'            => $emp->name,
            'employee_number' => $emp->employee_number,
            'position'        => $emp->position?->name ?? 'Tanpa Posisi',
            'band'            => $emp->band,
            'initials'        => $emp->initials,
        ])->toArray();
    }

    public function updatedSearchEmployee($value)
    {
        if (strlen($value) < 2) {
            $this->searchResults = [];
            return;
        }

        // Human Captain can search ALL employees (excluding self)
        $this->searchResults = Employee::with(['position:id,name,employee_id'])
            ->where('id', '!=', $this->employee->id)
            ->where(function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%')
                    ->orWhere('employee_number', 'like', '%' . $value . '%');
            })
            ->limit(10)
            ->get()
            ->map(fn($emp) => [
                'id'              => $emp->id,
                'name'            => $emp->name,
                'employee_number' => $emp->employee_number,
                'position'        => $emp->position?->name ?? 'Tanpa Posisi',
                'band'            => $emp->band,
            ])
            ->toArray();
    }

    public function startCoaching(string $mode, ?int $employeeId = null): void
    {
        $this->coachingMode      = $mode;
        $this->messages         = [];
        $this->userInput        = '';
        $this->chatActive       = true;
        $this->searchEmployee   = '';
        $this->searchResults    = [];

        if ($mode === 'self') {
            $this->targetEmployee      = $this->employee;
            $this->selectedSubordinate = null;
        } elseif ($mode === 'subordinate' && $employeeId) {
            // Security: verify the subordinate belongs to this manager
            $isValid = collect($this->subordinates)->contains('id', $employeeId);
            if (!$isValid) {
                return;
            }

            $target = Employee::with([
                'technicalCompetencies',
                'educations',
                'innovations',
                'mcuResults',
                'burnout',
                'mbti',
                'disc',
                'papi',
                'positionHistories',
                'position',
            ])->find($employeeId);

            if (!$target) {
                return;
            }

            $this->targetEmployee      = $target;
            $this->selectedSubordinate = collect($this->subordinates)->firstWhere('id', $employeeId);
        } elseif ($mode === 'human_captain' && $employeeId) {
            // Human Captain mode: can coach ANY employee
            $target = Employee::with([
                'technicalCompetencies',
                'educations',
                'innovations',
                'mcuResults',
                'burnout',
                'mbti',
                'disc',
                'papi',
                'positionHistories',
                'position',
            ])->find($employeeId);

            if (!$target) {
                return;
            }

            $this->targetEmployee = $target;
            // Find in search results or create basic info
            $this->selectedSubordinate = collect($this->searchResults)->firstWhere('id', $employeeId)
                ?? [
                    'id'              => $target->id,
                    'name'            => $target->name,
                    'employee_number' => $target->employee_number,
                    'position'        => $target->position?->name ?? 'Tanpa Posisi',
                    'band'            => $target->band,
                ];
        }

        $this->messages[] = [
            'role'      => 'assistant',
            'content'   => $this->getWelcomeMessage(),
            'timestamp' => now()->format('H:i'),
        ];
    }

    public function backToSelection(): void
    {
        $this->chatActive          = false;
        $this->coachingMode        = null;
        $this->targetEmployee      = null;
        $this->selectedSubordinate = null;
        $this->messages            = [];
        $this->userInput           = '';
        $this->searchEmployee      = '';
        $this->searchResults       = [];
    }

    private function getWelcomeMessage(): string
    {
        if ($this->coachingMode === 'self') {
            $name = $this->targetEmployee->name;
            return "Halo, **{$name}**! 👋 Saya adalah AI Coach Anda. Saya memiliki akses ke profil lengkap Anda — kompetensi, riwayat posisi, asesmen psikologi, dan lainnya. Silakan tanyakan apa saja seputar pengembangan karier dan potensi Anda! 🚀";
        }

        if ($this->coachingMode === 'subordinate') {
            $staffName   = $this->targetEmployee->name;
            $managerName = $this->employee->name;
            return "Halo, **{$managerName}**! 👋 Anda kini dalam sesi coaching untuk anggota tim Anda, **{$staffName}**. Saya telah memuat profil lengkap {$staffName}. Silakan tanyakan tentang potensi, kompetensi, atau area pengembangan anggota tim Anda.";
        }

        // Human Captain mode
        $staffName    = $this->targetEmployee->name;
        $hcName       = $this->employee->name;
        return "Halo, **{$hcName}**! 👋 Anda memasuki mode **Human Captain** — sesi coaching untuk **{$staffName}**. Mode ini memberikan akses penuh untuk menganalisis profil secara mendalam. Saya siap membantu Anda memberikan coaching komprehensif untuk anggota organisasi manapun!";
    }

    public function updatedUserInput($value)
    {
        if (strlen($value) > $this->maxChars) {
            $this->userInput = substr($value, 0, $this->maxChars);
        }
    }

    public function sendMessage()
    {
        $message = trim($this->userInput);

        if (empty($message) || $this->isLoading) {
            return;
        }

        if (strlen($message) > $this->maxChars) {
            return;
        }

        $this->messages[] = [
            'role'      => 'user',
            'content'   => $message,
            'timestamp' => now()->format('H:i'),
        ];

        // Dispatch scroll event to browser
        $this->dispatch('browser-event', ['event' => 'chat-updated']);

        $this->userInput = '';
        $this->isLoading = true;

        $this->dispatch('process-ai-response', message: $message);

        Log::info('(AI Coaching) User ' . Auth::user()->name . ' sent message: ' . $message);
        activity()->event('AI Coaching')->withProperties([
            'actor'   => Auth::user()->name,
            'target'  => $this->targetEmployee->name,
            'mode'    => $this->coachingMode,
            'message' => $message
        ])->log('Actor sent message to AI Coach');
    }

    #[\Livewire\Attributes\On('process-ai-response')]
    public function processAiResponse(string $message)
    {
        try {
            $response = Groq::chat()->completions()->create([
                'model'       => config('groq.model'),
                'temperature' => 0.7,
                'max_tokens'  => 2000,
                'messages'    => array_merge(
                    [['role' => 'system', 'content' => $this->buildSystemPrompt($this->buildEmployeeContext())]],
                    $this->buildConversationHistory(),
                    [['role' => 'user', 'content' => $message]]
                ),
            ]);

            $aiReply = $response['choices'][0]['message']['content'] ?? 'Maaf, terjadi kesalahan dalam memproses respons.';

            $this->messages[] = [
                'role'      => 'assistant',
                'content'   => $aiReply,
                'timestamp' => now()->format('H:i'),
            ];

            // Dispatch scroll event to browser
            $this->dispatch('browser-event', ['event' => 'chat-updated']);
        } catch (\Exception $e) {
            Log::error('AI Coaching Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error'   => $e->getTraceAsString(),
            ]);

            $this->messages[] = [
                'role'      => 'assistant',
                'content'   => 'Maaf, terjadi kesalahan teknis. Silakan coba lagi.',
                'timestamp' => now()->format('H:i'),
            ];

            // Dispatch scroll event to browser
            $this->dispatch('browser-event', ['event' => 'chat-updated']);
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildSystemPrompt(string $employeeContext): string
    {
        $isSubordinateMode = $this->coachingMode === 'subordinate';
        $isHumanCaptain   = $this->coachingMode === 'human_captain';

        $role = match (true) {
            $isHumanCaptain => "Human Captain profesional yang sedang memberikan coaching komprehensif kepada anggota organisasi",
            $isSubordinateMode => "manajer yang sedang melakukan coaching untuk stafnya",
            default => "karyawan yang ingin berkembang"
        };

        $perspective = match (true) {
            $isHumanCaptain => "Jawab dari perspektif Human Captain yang membantu menganalisis potensi dan memberikan rekomendasi pengembangan strategis. Gunakan nama lengkap karyawan yang sedang di-coach saat merujuk ke profil. Human Captain tidak punya hubungan langsung dengan karyawan ini, jadi gunakan pendekatan netral namun konstruktif.",
            $isSubordinateMode => "Jawab dari perspektif membantu manajer memahami potensi dan area pengembangan stafnya. Gunakan 'staf Anda' atau nama staf saat merujuk ke profil di bawah.",
            default => "Jawab dari perspektif membantu karyawan memahami dirinya sendiri. Gunakan 'Anda' saat merujuk ke profil di bawah."
        };

        $modeNote = $isHumanCaptain
            ? "\n\nMODE KHUSUS - HUMAN CAPTAIN: Anda berada dalam mode Human Captain yang memberikan akses analitis penuh. Berikan insight yang lebih komprehensif dan strategis, termasuk perbandingan dengan standar industri jika relevan."
            : "";

        return <<<PROMPT
Anda adalah AI Coach profesional untuk pengembangan karier karyawan di industri semen. Pengguna adalah seorang {$role}.

PROFIL YANG DIANALISIS:
{$employeeContext}

PANDUAN COACHING:
- {$perspective}
- Berikan respons yang personal, tajam, jujur, lugas, tegas, objektif, dan spesifik berdasarkan profil di atas.
- Jika data tidak ada, jangan sebutkan di respon. Dilarang memberikan respon dalam konteks "Meskipun data spesifik tidak disediakan..."
- Jika berkaitan dengan data psikologis, tidak perlu menampilkan data mentah, tetapi kamu jelaskan tentang data tersebut dan berikan kesimpulan
- Fokus pada pengembangan karier, kompetensi, dan potensi
- Berikan saran yang actionable dan konkret
- Gunakan bahasa Indonesia yang profesional
- Maksimal 2 - 3 paragraf per respons
- Jangan membuat data atau informasi yang tidak ada dalam profil
{$modeNote}

BATASAN:
- Hanya fokus pada topik karier, pengembangan diri, dan pekerjaan
- Jika ada pertanyaan di luar topik tersebut, apapun alasannya tolak dengan tegas, dan arahkan kembali ke pengembangan karier
PROMPT;
    }

    private function buildEmployeeContext(): string
    {
        $e = $this->targetEmployee;

        if (!$e) {
            return "Data profil karyawan tidak tersedia.";
        }

        $context   = [];
        $context[] = "NAMA: {$e->name}";
        $context[] = "BAND: {$e->band}";

        if ($e->position?->name) {
            $context[] = "POSISI SAAT INI: {$e->position->name}";
        }

        if ($e->technicalCompetencies->isNotEmpty()) {
            $context[] = "KOMPETENSI TEKNIS: " . $e->technicalCompetencies->pluck('name')->implode(', ');
        }

        if ($e->educations->isNotEmpty()) {
            $edu       = $e->educations->map(fn($ed) => "{$ed->degree} - {$ed->major}")->implode('; ');
            $context[] = "PENDIDIKAN: {$edu}";
        }

        if ($e->mbti?->mbti) {
            $context[] = "MBTI: {$e->mbti->mbti}";
        }

        if ($e->disc) {
            $discItems = array_filter([
                $e->disc->interpretation1,
                $e->disc->interpretation2,
                $e->disc->interpretation3,
            ]);
            if (!empty($discItems)) {
                $context[] = "DISC: " . implode(', ', $discItems);
            }
        }

        if ($e->papi) {
            $papiData = $e->papi;

            // Calculate averages for each aspect
            $arahKerja = [];
            if ($papiData->N !== null) $arahKerja[] = $papiData->N;
            if ($papiData->G !== null) $arahKerja[] = $papiData->G;
            if ($papiData->A !== null) $arahKerja[] = $papiData->A;
            $arahKerjaAvg = count($arahKerja) > 0 ? array_sum($arahKerja) / count($arahKerja) : null;

            $kepemimpinan = [];
            if ($papiData->L !== null) $kepemimpinan[] = $papiData->L;
            if ($papiData->P !== null) $kepemimpinan[] = $papiData->P;
            if ($papiData->I !== null) $kepemimpinan[] = $papiData->I;
            $kepemimpinanAvg = count($kepemimpinan) > 0 ? array_sum($kepemimpinan) / count($kepemimpinan) : null;

            $aktivitas = [];
            if ($papiData->T !== null) $aktivitas[] = $papiData->T;
            if ($papiData->V !== null) $aktivitas[] = $papiData->V;
            $aktivitasAvg = count($aktivitas) > 0 ? array_sum($aktivitas) / count($aktivitas) : null;

            $pergaulan = [];
            if ($papiData->O !== null) $pergaulan[] = $papiData->O;
            if ($papiData->B !== null) $pergaulan[] = $papiData->B;
            if ($papiData->S !== null) $pergaulan[] = $papiData->S;
            if ($papiData->X !== null) $pergaulan[] = $papiData->X;
            $pergaulanAvg = count($pergaulan) > 0 ? array_sum($pergaulan) / count($pergaulan) : null;

            $gayaKerja = [];
            if ($papiData->C !== null) $gayaKerja[] = $papiData->C;
            if ($papiData->D !== null) $gayaKerja[] = $papiData->D;
            if ($papiData->R !== null) $gayaKerja[] = $papiData->R;
            $gayaKerjaAvg = count($gayaKerja) > 0 ? array_sum($gayaKerja) / count($gayaKerja) : null;

            $sifat = [];
            if ($papiData->Z !== null) $sifat[] = $papiData->Z;
            if ($papiData->E !== null) $sifat[] = $papiData->E;
            if ($papiData->K !== null) $sifat[] = $papiData->K;
            $sifatAvg = count($sifat) > 0 ? array_sum($sifat) / count($sifat) : null;

            $ketaatan = [];
            if ($papiData->F !== null) $ketaatan[] = $papiData->F;
            if ($papiData->W !== null) $ketaatan[] = $papiData->W;
            $ketaatanAvg = count($ketaatan) > 0 ? array_sum($ketaatan) / count($ketaatan) : null;

            // Build PAPI context with grouped aspects
            $papiAspects = [];
            if ($arahKerjaAvg !== null) $papiAspects[] = "Arah Kerja: " . number_format($arahKerjaAvg, 1);
            if ($kepemimpinanAvg !== null) $papiAspects[] = "Kepemimpinan: " . number_format($kepemimpinanAvg, 1);
            if ($aktivitasAvg !== null) $papiAspects[] = "Aktivitas: " . number_format($aktivitasAvg, 1);
            if ($pergaulanAvg !== null) $papiAspects[] = "Pergaulan: " . number_format($pergaulanAvg, 1);
            if ($gayaKerjaAvg !== null) $papiAspects[] = "Gaya Kerja: " . number_format($gayaKerjaAvg, 1);
            if ($sifatAvg !== null) $papiAspects[] = "Sifat: " . number_format($sifatAvg, 1);
            if ($ketaatanAvg !== null) $papiAspects[] = "Ketaatan: " . number_format($ketaatanAvg, 1);

            if (!empty($papiAspects)) {
                $context[] = "PAPI KOSTICK:\n" . implode("\n", $papiAspects);
            }
        }

        if ($e->burnout?->result) {
            $context[] = "BURNOUT LEVEL: {$e->burnout->level}";
        }

        if ($e->innovations->isNotEmpty()) {
            $context[] = "INOVASI: " . $e->innovations->map(fn($i) => "{$i->title} ({$i->status})")->implode('; ');
        }

        if ($e->mcuResults->isNotEmpty()) {
            $context[] = "MEDICAL CHECKUP RESULTS: " . $e->mcuResults->map(fn($m) => "{$m->year}: {$m->result}/5")->implode(', ');
        }

        if ($e->positionHistories->isNotEmpty()) {
            $context[] = "RIWAYAT POSISI: " . $e->positionHistories->map(fn($h) => "{$h->position_name} di {$h->work_unit}")->implode('; ');
        }

        try {
            $psychogram = $e->getScores();
            $strengths  = $psychogram['strengths']->pluck('aspect.name')->toArray();
            $weaknesses = $psychogram['weaknesses']->pluck('aspect.name')->toArray();

            if (!empty($strengths)) {
                $context[] = "KEKUATAN PSIKOGRAM: " . implode(', ', $strengths);
            }
            if (!empty($weaknesses)) {
                $context[] = "KELEMAHAN PSIKOGRAM: " . implode(', ', $weaknesses);
            }
        } catch (\Exception $ex) {
            // not available
        }

        return implode("\n", $context);
    }

    private function buildConversationHistory(): array
    {
        $history = array_slice($this->messages, 1, -1);

        return collect($history)->map(fn($msg) => [
            'role'    => $msg['role'],
            'content' => $msg['content'],
        ])->toArray();
    }

    public function clearChat()
    {
        $this->messages  = [[
            'role'      => 'assistant',
            'content'   => $this->getWelcomeMessage(),
            'timestamp' => now()->format('H:i'),
        ]];
        $this->userInput = '';
    }

    public function updatedMessages()
    {
        // Dispatch browser event after messages are updated
        $this->dispatch('browser-event', ['event' => 'chat-updated']);
    }

    public function updatedIsLoading($value)
    {
        // Scroll when loading finishes (AI response received)
        if ($value === false) {
            $this->dispatch('browser-event', ['event' => 'chat-updated']);
        }
    }

    public function render()
    {
        return view('livewire.pages.ai-coaching.index', [
            'charsLeft' => $this->maxChars - strlen($this->userInput),
        ]);
    }
}