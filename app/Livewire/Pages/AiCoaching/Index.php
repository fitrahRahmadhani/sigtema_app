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
    public $isRegistered    = false;
    public $employee        = null;
    public $subordinates    = [];
    public $hasSubordinates = false;

    // Coaching target
    public $coachingMode        = null;
    public $targetEmployee      = null;
    public $selectedSubordinate = null;

    // Chat state
    public $messages   = [];
    public $userInput  = '';
    public $isLoading  = false;
    public $maxChars   = 200;
    public $chatActive = false;

    public function mount()
    {
        $user = Auth::user();

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

        if (!$this->hasSubordinates) {
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

    public function startCoaching(string $mode, ?int $subordinateId = null): void
    {
        $this->coachingMode = $mode;
        $this->messages     = [];
        $this->userInput    = '';
        $this->chatActive   = true;

        if ($mode === 'self') {
            $this->targetEmployee      = $this->employee;
            $this->selectedSubordinate = null;
        } elseif ($mode === 'subordinate' && $subordinateId) {
            // Security: verify the subordinate belongs to this manager
            $isValid = collect($this->subordinates)->contains('id', $subordinateId);
            if (!$isValid) {
                return;
            }

            $subordinate = Employee::with([
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
            ])->find($subordinateId);

            if (!$subordinate) {
                return;
            }

            $this->targetEmployee      = $subordinate;
            $this->selectedSubordinate = collect($this->subordinates)->firstWhere('id', $subordinateId);
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
    }

    private function getWelcomeMessage(): string
    {
        if ($this->coachingMode === 'self') {
            $name = $this->targetEmployee->name;
            return "Halo, **{$name}**! 👋 Saya adalah AI Coach Anda. Saya memiliki akses ke profil lengkap Anda — kompetensi, riwayat posisi, asesmen psikologi, dan lainnya. Silakan tanyakan apa saja seputar pengembangan karier dan potensi Anda! 🚀";
        }

        $staffName   = $this->targetEmployee->name;
        $managerName = $this->employee->name;
        return "Halo, **{$managerName}**! 👋 Anda kini dalam sesi coaching untuk anggota tim Anda, **{$staffName}**. Saya telah memuat profil lengkap {$staffName}. Silakan tanyakan tentang potensi, kompetensi, atau area pengembangan anggota tim Anda.";
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

        $this->userInput = '';
        $this->isLoading = true;

        $this->dispatch('process-ai-response', message: $message);

        Log::info('(AI Coaching) User ' . Auth::user()->name . ' sent message: ' . $message);
        activity()->event('AI Coaching')->withProperties(['actor' => Auth::user()->name,  'target' => $this->targetEmployee->name, 'message' => $message])->log('Actor sent message to AI Coach');
    }

    #[\Livewire\Attributes\On('process-ai-response')]
    public function processAiResponse(string $message)
    {
        try {
            $response = Groq::chat()->completions()->create([
                'model'       => config('groq.model'),
                'temperature' => 0.7,
                'max_tokens'  => 1000,
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
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildSystemPrompt(string $employeeContext): string
    {
        $isManagerMode = $this->coachingMode === 'subordinate';

        $role        = $isManagerMode ? "manajer yang sedang melakukan coaching untuk stafnya" : "karyawan yang ingin berkembang";
        $perspective = $isManagerMode
            ? "Jawab dari perspektif membantu manajer memahami potensi dan area pengembangan stafnya. Gunakan 'staf Anda' atau nama staf saat merujuk ke profil di bawah."
            : "Jawab dari perspektif membantu karyawan memahami dirinya sendiri. Gunakan 'Anda' saat merujuk ke profil di bawah.";

        return <<<PROMPT
        Anda adalah AI Coach profesional untuk pengembangan karier karyawan. Pengguna adalah seorang {$role}.

        PROFIL YANG DIANALISIS:
        {$employeeContext}

        PANDUAN COACHING:
        - {$perspective}
        - Berikan respons yang personal dan spesifik berdasarkan profil di atas
        - Fokus pada pengembangan karier, kompetensi, dan potensi
        - Berikan saran yang actionable dan konkret
        - Gunakan bahasa Indonesia yang profesional namun ramah
        - Maksimal 2-3 paragraf per respons
        - Jangan membuat data atau informasi yang tidak ada dalam profil

        BATASAN:
        - Hanya fokus pada topik karier, pengembangan diri, dan pekerjaan
        - Jika ada pertanyaan di luar topik tersebut, tolak, dan arahkan kembali ke pengembangan karier
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
        $context[] = "NOMOR KARYAWAN: {$e->employee_number}";
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

        if ($e->burnout?->result) {
            $context[] = "BURNOUT LEVEL: {$e->burnout->result}";
        }

        if ($e->innovations->isNotEmpty()) {
            $context[] = "INOVASI: " . $e->innovations->map(fn($i) => "{$i->title} ({$i->status})")->implode('; ');
        }

        if ($e->mcuResults->isNotEmpty()) {
            $context[] = "MCU RESULTS: " . $e->mcuResults->map(fn($m) => "{$m->year}: {$m->result}/5")->implode(', ');
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

    public function render()
    {
        return view('livewire.pages.ai-coaching.index', [
            'charsLeft' => $this->maxChars - strlen($this->userInput),
        ]);
    }
}
