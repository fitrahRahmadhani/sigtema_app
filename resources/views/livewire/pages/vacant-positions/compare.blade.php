<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('vacant-positions.index') }}">Vacant Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('vacant-positions.show', ['position' => $position->slug]) }}">
            {{ $position->name }} - AI Match
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>AI Candidates Comparison</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl text-white">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">AI Candidates Comparison</h1>
                    <p class="mt-1 text-gray-600">
                        Position:
                        <span class="font-semibold">{{ $position->name }}</span>
                    </p>
                    <div class="mt-2 flex items-center gap-2">
                        <p class="font-semibold text-gray-600">Powered by</p>
                        <img src="{{ asset('assets/logo/logo-gemini.png') }}" class="h-6" alt="Gemini AI" />
                    </div>
                </div>
            </div>

            <button
                wire:click="goBack"
                class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                ← Back to AI Match
            </button>
        </div>

        {{-- AI Comparison Summary --}}
        @if (count($aiRecommendations) > 0)
            <div class="mb-4 rounded-lg border border-gray-200 bg-gradient-to-r from-[#721d21]/5 to-[#8a252a]/5 p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-full text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">AI Analysis Summary</h3>
                            <p class="text-sm text-gray-600">
                                Comparing {{ count($selectedEmployees) }} AI-recommended candidates
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Info Refresh Chart --}}
            <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                <div class="flex items-center gap-2 text-sm text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path
                            fill-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    <p>
                        <span class="font-semibold">Jika chart (bagan) tidak muncul,</span>
                        mohon untuk refresh halaman
                    </p>
                </div>
            </div>
        @endif

        {{-- Comparison Table --}}
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="left-0 w-64 bg-gray-50 px-6 py-4 text-left text-sm font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Comparison Criteria
                            </th>
                            @foreach ($selectedEmployees as $employee)
                                <th class="w-[400px] bg-gray-50 px-6 py-4">
                                    <div class="flex gap-3 rounded-lg border border-gray-200 bg-white p-3">
                                        <div
                                            class="bg-primary-text/20 text-primary-text flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg"
                                        >
                                            @if ($employee->profile_path)
                                                <img
                                                    src="{{ asset('storage/' . $employee->profile_path) }}"
                                                    alt="{{ $employee->name }}"
                                                    class="h-12 w-12 object-cover"
                                                />
                                            @else
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="h-6 w-6"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 text-start">
                                            <p class="truncate font-semibold text-gray-900">{{ $employee->name }}</p>
                                            <p class="truncate text-sm text-gray-600">
                                                {{ $employee->position?->name ?? 'No Position' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $employee->employee_number }}</p>

                                            @php
                                                $aiInfo = $this->getAIInfoForEmployee($employee->id);
                                            @endphp

                                            @if ($aiInfo)
                                                <div class="mt-2 flex items-center gap-2">
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-[#721d21]/10 px-2 py-1 text-xs font-medium text-[#721d21]"
                                                    >
                                                        <svg
                                                            class="h-3 w-3"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                            />
                                                        </svg>
                                                        AI Score: {{ $aiInfo['match_score'] ?? 0 }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        {{-- AI RECOMMENDATION SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                AI Recommendation Analysis
                            </td>
                        </tr>

                        {{-- AI Match Score --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">AI Match Score</td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $aiInfo = $this->getAIInfoForEmployee($employee->id);
                                    $matchScore = $aiInfo['match_score'] ?? 0;
                                @endphp

                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-full">
                                            <div class="mb-2 flex items-center justify-between">
                                                <span class="font-semibold text-[#721d21]">{{ $matchScore }}%</span>
                                                <span class="text-sm text-gray-500">Match</span>
                                            </div>
                                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                                <div
                                                    class="h-full rounded-full bg-gradient-to-r from-[#721d21] to-[#8a252a]"
                                                    style="width: {{ $matchScore }}%"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- AI Strengths --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                AI Strengths Analysis
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $aiInfo = $this->getAIInfoForEmployee($employee->id);
                                    $strengths = $aiInfo['strengths'] ?? [];
                                @endphp

                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    @if (! empty($strengths))
                                        <ul class="space-y-1">
                                            @foreach ($strengths as $strength)
                                                <li class="flex items-start gap-2">
                                                    <svg
                                                        class="mt-1 h-4 w-4 flex-shrink-0 text-green-500"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 13l4 4L19 7"
                                                        />
                                                    </svg>
                                                    <span>{{ $strength }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- AI Development Areas --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                AI Development Areas
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $aiInfo = $this->getAIInfoForEmployee($employee->id);
                                    $weaknesses = $aiInfo['weaknesses'] ?? [];
                                @endphp

                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    @if (! empty($weaknesses))
                                        <ul class="space-y-1">
                                            @foreach ($weaknesses as $weakness)
                                                <li class="flex items-start gap-2">
                                                    <svg
                                                        class="mt-1 h-4 w-4 flex-shrink-0 text-amber-500"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"
                                                        />
                                                    </svg>
                                                    <span>{{ $weakness }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- BASIC INFORMATION SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                Basic Information
                            </td>
                        </tr>

                        {{-- Email --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Email</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    <span class="font-medium">{{ $employee->email ?? '-' }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Phone --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Phone</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    <span class="font-medium">{{ $employee->phone ?? '-' }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Personal Grade --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Personal Grade</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    <span class="font-medium">{{ $employee->personal_grade ?? '-' }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Band --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Band</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    <span class="font-medium">{{ $employee->band ?? '-' }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Hire Date --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Hire Date</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 text-gray-700">
                                    <span class="text-gray-400 italic">
                                        {{ $employee->hire_date?->format('d F Y') ?? 'N/A' }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- PERSONALITY INFORMATION SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">Personality Information</p>
                                <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                <img
                                    src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                    class="mb-1 ml-2 inline h-6 self-end"
                                    alt="Logo SIGMATIC"
                                />
                            </td>
                        </tr>

                        {{-- MBTI --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">MBTI</td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    @if (! empty($employee->mbti->mbti))
                                        <span class="font-medium">{{ $employee->mbti->mbti }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- PAPI KOSTICK SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">PAPI Kostick</p>
                                <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                <img
                                    src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                    class="mb-1 ml-2 inline h-6 self-end"
                                    alt="Logo SIGMATIC"
                                />
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $index => $employee)
                                <td class="px-6 py-4 align-top">
                                    <div class="flex w-[400px] flex-col items-center justify-start">
                                        <div
                                            class="relative aspect-square w-[400px] rounded-full border-2 border-dashed border-gray-300"
                                        >
                                            @php
                                                $papiData = $this->getEmployeePAPIData($employee);
                                            @endphp

                                            <canvas
                                                id="papiChart-{{ $index }}"
                                                class="h-full w-full"
                                                wire:ignore
                                                data-papi-data="{{ json_encode($papiData) }}"
                                                data-employee-name="{{ $employee->name ?? 'Employee' }}"
                                            ></canvas>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- DISC SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">DISC</p>
                                <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                <img
                                    src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                    class="mb-1 ml-2 inline h-6 self-end"
                                    alt="Logo SIGMATIC"
                                />
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $index => $employee)
                                <td class="px-6 py-4 align-top">
                                    @php
                                        $discData = $this->getEmployeeDISCData($employee);
                                    @endphp

                                    @if (! empty($discData))
                                        <div class="flex w-[400px] flex-col space-y-6">
                                            {{-- DISC 1 --}}
                                            <div class="overflow-hidden rounded-lg border border-gray-300">
                                                <div class="bg-red-500 px-3 py-2 text-center">
                                                    <p class="text-sm font-semibold text-white">DISC 1</p>
                                                </div>
                                                <div
                                                    class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                >
                                                    <canvas
                                                        id="disc1Chart-{{ $index }}"
                                                        class="h-full w-full"
                                                        wire:ignore
                                                        data-disc-data="{{
                                                            json_encode([
                                                                'D' => $discData['D1'],
                                                                'I' => $discData['I1'],
                                                                'S' => $discData['S1'],
                                                                'C' => $discData['C1'],
                                                            ])
                                                        }}"
                                                    ></canvas>
                                                </div>
                                                <div
                                                    class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                >
                                                    <p class="text-center text-sm leading-relaxed text-gray-700">
                                                        {{ $discData['interpretation1'] }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- DISC 2 --}}
                                            <div class="overflow-hidden rounded-lg border border-gray-300">
                                                <div class="bg-yellow-500 px-3 py-2 text-center">
                                                    <p class="text-sm font-semibold text-white">DISC 2</p>
                                                </div>
                                                <div
                                                    class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                >
                                                    <canvas
                                                        id="disc2Chart-{{ $index }}"
                                                        class="h-full w-full"
                                                        wire:ignore
                                                        data-disc-data="{{
                                                            json_encode([
                                                                'D' => $discData['D2'],
                                                                'I' => $discData['I2'],
                                                                'S' => $discData['S2'],
                                                                'C' => $discData['C2'],
                                                            ])
                                                        }}"
                                                    ></canvas>
                                                </div>
                                                <div
                                                    class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                >
                                                    <p class="text-center text-sm leading-relaxed text-gray-700">
                                                        {{ $discData['interpretation2'] }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- DISC 3 --}}
                                            <div class="overflow-hidden rounded-lg border border-gray-300">
                                                <div class="bg-green-500 px-3 py-2 text-center">
                                                    <p class="text-sm font-semibold text-white">DISC 3</p>
                                                </div>
                                                <div
                                                    class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                >
                                                    <canvas
                                                        id="disc3Chart-{{ $index }}"
                                                        class="h-full w-full"
                                                        wire:ignore
                                                        data-disc-data="{{
                                                            json_encode([
                                                                'D' => $discData['D3'],
                                                                'I' => $discData['I3'],
                                                                'S' => $discData['S3'],
                                                                'C' => $discData['C3'],
                                                            ])
                                                        }}"
                                                    ></canvas>
                                                </div>
                                                <div
                                                    class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                >
                                                    <p class="text-center text-sm leading-relaxed text-gray-700">
                                                        {{ $discData['interpretation3'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex w-[400px] flex-col items-center justify-center py-8">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-12 w-12 text-gray-300"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"
                                                />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-300">No DISC data</p>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- SOFT COMPETENCIES SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                Soft Competencies
                            </td>
                        </tr>

                        {{-- Strengths --}}
                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                Strengths
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 text-gray-700">
                                    @php
                                        $softCompetencies = $this->getEmployeeSoftCompetencies($employee);
                                    @endphp

                                    <div class="flex items-center gap-2">
                                        <ol class="ml-4 list-disc">
                                            @forelse ($softCompetencies['strengths'] as $strength)
                                                <li>{{ $strength }}</li>
                                            @empty
                                                <li class="text-gray-400">-</li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Weaknesses --}}
                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                Weaknesses
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 text-gray-700">
                                    @php
                                        $softCompetencies = $this->getEmployeeSoftCompetencies($employee);
                                    @endphp

                                    <div class="flex items-center gap-2">
                                        <ol class="ml-4 list-disc">
                                            @forelse ($softCompetencies['weaknesses'] as $weakness)
                                                <li>{{ $weakness }}</li>
                                            @empty
                                                <li class="text-gray-400">-</li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- TECHNICAL COMPETENCY SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">Technical Competency</p>
                                <p class="ml-2 inline text-sm text-white italic">
                                    data powered by
                                    <span class="font-bold">LSP SIG</span>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if ($employee->technicalCompetencies && $employee->technicalCompetencies->count() > 0)
                                        <ul class="space-y-3">
                                            @foreach ($employee->technicalCompetencies as $skill)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="font-medium text-gray-900">{{ $skill->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $skill->issued_by }}</p>
                                                    <p class="text-sm text-gray-500">{{ $skill->year }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- INNOVATION SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">Innovation</p>
                                <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                <img
                                    src="{{ asset('assets/logo/logo-portal-inovasi.png') }}"
                                    class="mb-1 ml-2 inline h-6 self-end"
                                    alt="Logo Portal Inovasi"
                                />
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if ($employee->innovations && $employee->innovations->count() > 0)
                                        <ul class="w-[400px] space-y-3">
                                            @foreach ($employee->innovations as $innovation)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <div class="mb-1 flex items-start justify-between gap-2">
                                                        <p class="flex-1 font-medium text-gray-900">
                                                            {{ $innovation->title }}
                                                        </p>
                                                        @if ($innovation->status)
                                                            <span
                                                                class="{{ $innovation->status === 'Peserta' ? 'bg-gray-200 text-gray-800' : '' }} {{ preg_match('/^Juara\s.+/i', $innovation->status) ? 'bg-yellow-100 text-yellow-800' : '' }} inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                                            >
                                                                {{ ucfirst($innovation->status) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($innovation->event)
                                                        <p class="text-sm text-gray-600">{{ $innovation->event }}</p>
                                                    @endif

                                                    @if ($innovation->achievement)
                                                        <p class="mt-1 text-sm font-medium text-amber-700">
                                                            🏆 {{ $innovation->achievement }}
                                                        </p>
                                                    @endif

                                                    @if ($innovation->category || $innovation->theme)
                                                        <p class="mt-1 text-sm text-gray-500">
                                                            {{ $innovation->category ? $innovation->category : '' }}
                                                            {{ $innovation->category && $innovation->theme ? ' • ' : '' }}
                                                            {{ $innovation->theme ? $innovation->theme : '' }}
                                                        </p>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- POSITION HISTORY SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                Position History
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if ($employee->positionHistories && $employee->positionHistories->count() > 0)
                                        <ul class="space-y-3">
                                            @if ($employee->position)
                                                <li class="border-l-2 border-gray-500 pl-3">
                                                    <p class="font-medium text-gray-900">
                                                        {{ $employee->position->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $employee->position->company->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-400">Current</p>
                                                </li>
                                            @endif

                                            @foreach ($employee->positionHistories as $position)
                                                <li class="border-l-2 border-gray-500 pl-3">
                                                    <p class="font-medium text-gray-900">
                                                        {{ $position->position_name }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">{{ $position->company }}</p>
                                                    <p class="text-sm text-gray-400">
                                                        {{ $position->formatted_period }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- EDUCATION SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                Education
                            </td>
                        </tr>

                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if ($employee->educations && $employee->educations->count() > 0)
                                        <ul class="space-y-3">
                                            @foreach ($employee->educations as $education)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="font-medium text-gray-900">
                                                        {{ $education->degree }} - {{ $education->major }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $education->institution_name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $education->formatted_period }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- MCU HISTORY SECTION --}}
                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-3 font-semibold text-white"
                            >
                                <p class="inline">MCU History</p>
                                <p class="ml-2 inline text-sm text-white italic">
                                    data powered by
                                    <span class="font-bold">Unit of Tuban & Gresik SHE</span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @foreach ($selectedEmployees as $employee)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if ($employee->mcuResults && $employee->mcuResults->count() > 0)
                                        <div class="flex flex-col gap-1">
                                            @foreach ($employee->mcuResults as $mcu)
                                                @php
                                                    $maxStars = 5;
                                                    $filledStars = min($mcu->result ?? 0, $maxStars);
                                                @endphp

                                                <div class="flex items-center justify-start gap-2">
                                                    {{-- Tahun --}}
                                                    <span
                                                        class="rounded-full bg-gray-100 px-2 py-1 text-sm text-gray-800"
                                                    >
                                                        {{ $mcu->year }}
                                                    </span>

                                                    {{-- Bintang --}}
                                                    <div class="flex gap-1">
                                                        @for ($s = 0; $s < $filledStars; $s++)
                                                            <span class="text-xl text-yellow-500">★</span>
                                                        @endfor

                                                        @for ($s = $filledStars; $s < $maxStars; $s++)
                                                            <span class="text-xl text-gray-300">★</span>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No MCU data</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- BURNOUT SECTION --}}
                        </tr>

                        <tr class="color-gradient">
                            <td
                                colspan="{{ count($selectedEmployees) + 1 }}"
                                class="left-0 px-6 py-4 font-semibold text-white"
                            >
                                <p class="inline">Burnout Test Results</p>
                                <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                <img
                                    src="{{ asset('assets/logo/logo-sighap-white.png') }}"
                                    class="mb-1 ml-2 inline h-5 self-end"
                                    alt="Logo SIGHAP"
                                />
                            </td>
                        </tr>

                        {{-- Kelalahan Emosional --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                Kelalahan Emosional
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $burnoutData = $this->getEmployeeBurnoutData($employee);
                                @endphp

                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    <span class="font-medium">{{ $burnoutData['emotional_exhaustion'] }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Depersonalisasi --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Depersonalisasi</td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $burnoutData = $this->getEmployeeBurnoutData($employee);
                                @endphp

                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    <span class="font-medium">{{ $burnoutData['depersonalization'] }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Penurunan Prestasi --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                Penurunan Prestasi
                            </td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $burnoutData = $this->getEmployeeBurnoutData($employee);
                                @endphp

                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    <span class="font-medium">{{ $burnoutData['achievement_decline'] }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Penafsiran --}}
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Penafsiran</td>
                            @foreach ($selectedEmployees as $employee)
                                @php
                                    $burnoutData = $this->getEmployeeBurnoutData($employee);
                                @endphp

                                <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                    <span class="font-medium">
                                        {!! strip_tags($burnoutData['result'], '<i><b>') !!}
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Store untuk chart instances
        const chartInstances = {
            papi: {},
            disc1: {},
            disc2: {},
            disc3: {},
        };

        // Fungsi untuk menghapus semua chart
        function destroyAllCharts() {
            Object.values(chartInstances.papi).forEach((chart) => {
                if (chart && typeof chart.destroy === 'function') {
                    chart.destroy();
                }
            });
            chartInstances.papi = {};

            ['disc1', 'disc2', 'disc3'].forEach((type) => {
                Object.values(chartInstances[type]).forEach((chart) => {
                    if (chart && typeof chart.destroy === 'function') {
                        chart.destroy();
                    }
                });
                chartInstances[type] = {};
            });
        }

        // Konfigurasi PAPI
        const papiSegments = [
            {
                code: 'N',
                angle: 0,
                cat: 'ARAH KERJA',
                desc: 'Menyelesaikan\nTugas\nPribadi',
                color: '#FFB7B7',
                colorSolid: '#ef4444',
            },
            {
                code: 'G',
                angle: 18,
                cat: 'ARAH KERJA',
                desc: 'Peranan sbg\nPekerja\nKeras',
                color: '#FFB7B7',
                colorSolid: '#ef4444',
            },
            {
                code: 'A',
                angle: 36,
                cat: 'ARAH KERJA',
                desc: 'Hasrat utk\nBerprestasi',
                color: '#FFB7B7',
                colorSolid: '#ef4444',
            },
            {
                code: 'L',
                angle: 54,
                cat: 'KEPEMIMPINAN',
                desc: 'Peranan\nSbg\nPemimpin',
                color: '#d1d5db',
                colorSolid: '#6b7280',
            },
            {
                code: 'P',
                angle: 72,
                cat: 'KEPEMIMPINAN',
                desc: 'Mengendalikan\nOrang\nLain',
                color: '#d1d5db',
                colorSolid: '#6b7280',
            },
            {
                code: 'I',
                angle: 90,
                cat: 'KEPEMIMPINAN',
                desc: 'Mudah\nMembuat\nMemutuskan',
                color: '#d1d5db',
                colorSolid: '#6b7280',
            },
            { code: 'T', angle: 108, cat: 'AKTIVITAS', desc: 'Tempo Kerja', color: '#bfdbfe', colorSolid: '#3b82f6' },
            { code: 'V', angle: 126, cat: 'AKTIVITAS', desc: 'Stamina', color: '#bfdbfe', colorSolid: '#3b82f6' },
            {
                code: 'S',
                angle: 180,
                cat: 'PERGAULAN',
                desc: 'Pergaulan\nSosial',
                color: '#cffafe',
                colorSolid: '#06b6d4',
            },
            {
                code: 'B',
                angle: 162,
                cat: 'PERGAULAN',
                desc: 'Kebutuhan\nTerhadap\nKelompok',
                color: '#cffafe',
                colorSolid: '#06b6d4',
            },
            {
                code: 'O',
                angle: 144,
                cat: 'PERGAULAN',
                desc: 'Kebutuhan\nutk mendekati\n& menyayangi',
                color: '#cffafe',
                colorSolid: '#06b6d4',
            },
            {
                code: 'X',
                angle: 198,
                cat: 'PERGAULAN',
                desc: 'Mendapatkan\nPerhatian',
                color: '#cffafe',
                colorSolid: '#06b6d4',
            },
            {
                code: 'C',
                angle: 216,
                cat: 'GAYA KERJA',
                desc: 'Suka\nPekerjaan\nyg Teratur',
                color: '#f0fdf4',
                colorSolid: '#16A34A',
            },
            {
                code: 'D',
                angle: 234,
                cat: 'GAYA KERJA',
                desc: 'Suka\nPekerjaan\nyg Terperinci',
                color: '#f0fdf4',
                colorSolid: '#16A34A',
            },
            {
                code: 'R',
                angle: 252,
                cat: 'GAYA KERJA',
                desc: 'Tipe\nTeoritis',
                color: '#f0fdf4',
                colorSolid: '#16A34A',
            },
            {
                code: 'Z',
                angle: 270,
                cat: 'SIFAT',
                desc: 'Hasrat utk\nBerubah',
                color: '#fefce8',
                colorSolid: '#f59e0b',
            },
            {
                code: 'E',
                angle: 288,
                cat: 'SIFAT',
                desc: 'Pengendalian\nEmosi',
                color: '#fefce8',
                colorSolid: '#f59e0b',
            },
            { code: 'K', angle: 306, cat: 'SIFAT', desc: 'Agresivitas', color: '#fefce8', colorSolid: '#f59e0b' },
            {
                code: 'F',
                angle: 324,
                cat: 'KETAATAN',
                desc: 'Dukungan\nutk\nArahan',
                color: '#ffedd5',
                colorSolid: '#f97316',
            },
            {
                code: 'W',
                angle: 342,
                cat: 'KETAATAN',
                desc: 'Kebutuhan utk\nTaat Aturan\nArahan',
                color: '#ffedd5',
                colorSolid: '#f97316',
            },
        ];

        const papiCategories = {
            KETAATAN: { start: 315, end: 351, color: '#f97316' },
            'ARAH KERJA': { start: 351, end: 45, color: '#ef4444' },
            KEPEMIMPINAN: { start: 45, end: 99, color: '#6b7280' },
            AKTIVITAS: { start: 99, end: 135, color: '#3b82f6' },
            PERGAULAN: { start: 135, end: 207, color: '#06b6d4' },
            'GAYA KERJA': { start: 207, end: 261, color: '#16A34A' },
            SIFAT: { start: 261, end: 315, color: '#FACC15' },
        };

        // Fungsi untuk menggambar teks melengkung
        function drawArcText(ctx, text, cx, cy, radius, centerDeg, letterSpacing = 0.6) {
            ctx.save();
            ctx.font = 'bold 12px Arial';
            ctx.fillStyle = '#fff';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            const left = centerDeg > 90 && centerDeg < 270;
            const chars = left ? [...text].reverse() : [...text];
            const widths = chars.map((c) => ctx.measureText(c).width);
            const totalWidth = widths.reduce((a, b) => a + b, 0) + letterSpacing * (chars.length - 1);
            let angle = ((centerDeg - 90) * Math.PI) / 180 - totalWidth / (2 * radius);

            chars.forEach((ch, i) => {
                const w = widths[i] / radius;
                const mid = angle + w / 2;
                const x = cx + radius * Math.cos(mid);
                const y = cy + radius * Math.sin(mid);

                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(mid + Math.PI / 2 + (left ? Math.PI : 0));
                ctx.fillText(ch, 0, 0);
                ctx.restore();

                angle += w + letterSpacing / radius;
            });

            ctx.restore();
        }

        // Fungsi menggambar chart PAPI
        function drawPAPIChart(canvasId, papiData) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return null;

            if (chartInstances.papi[canvasId]) {
                chartInstances.papi[canvasId].destroy();
                delete chartInstances.papi[canvasId];
            }

            const ctx = canvas.getContext('2d');
            const container = canvas.parentElement;
            const size = Math.min(container.offsetWidth, 400);

            canvas.width = size;
            canvas.height = size;
            canvas.style.width = size + 'px';
            canvas.style.height = size + 'px';

            const cx = size / 2;
            const cy = size / 2;
            const outerR = size * 0.48;
            const innerR = size * 0.42;
            const boxOuter = size * 0.41;
            const boxInner = size * 0.33;
            const descR = size * 0.37;
            const subDescOuter = size * 0.32;
            const subDescInner = size * 0.28;
            const radarR = size * 0.28;

            ctx.clearRect(0, 0, size, size);

            function getCategoryCenter(start, end) {
                if (start <= end) return (start + end) / 2;
                const span = 360 - start + end;
                let center = start + span / 2;
                if (center >= 360) center -= 360;
                return center;
            }

            function getCategoryAverage(categoryName) {
                const segs = papiSegments.filter((s) => s.cat === categoryName);
                if (!segs.length) return 0;
                const scores = segs
                    .map((s) => {
                        const d = papiData.find((w) => w.code === s.code);
                        return d ? parseFloat(d.score) : null;
                    })
                    .filter((v) => v !== null);
                if (!scores.length) return 0;
                return (scores.reduce((a, b) => a + b, 0) / scores.length).toFixed(1);
            }

            function arc(start, end, color) {
                ctx.beginPath();
                ctx.arc(cx, cy, outerR, ((start - 90) * Math.PI) / 180, ((end - 90) * Math.PI) / 180);
                ctx.arc(cx, cy, innerR, ((end - 90) * Math.PI) / 180, ((start - 90) * Math.PI) / 180, true);
                ctx.closePath();
                ctx.fillStyle = color;
                ctx.fill();
            }

            // Draw categories
            Object.entries(papiCategories).forEach(([n, c]) => {
                if (n === 'KETAATAN') {
                    arc(315, 360, c.color);
                    arc(0, 45, c.color);
                } else arc(c.start, c.end, c.color);
            });

            // Draw category labels
            Object.entries(papiCategories).forEach(([name, c]) => {
                const r = (outerR + innerR) / 2;
                const center = getCategoryCenter(c.start, c.end);
                const avg = getCategoryAverage(name);
                const label = `${name} | ${avg}`;
                drawArcText(ctx, label, cx, cy, r, center, 0.6);
            });

            // Draw segment backgrounds
            papiSegments.forEach((s) => {
                const sa = ((s.angle - 9 - 90) * Math.PI) / 180;
                const ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                ctx.beginPath();
                ctx.arc(cx, cy, subDescOuter, sa, ea);
                ctx.arc(cx, cy, subDescInner, ea, sa, true);
                ctx.closePath();
                ctx.fillStyle = s.colorSolid;
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 1;
                ctx.stroke();
            });

            // Draw segment codes
            papiSegments.forEach((s) => {
                const rad = ((s.angle - 90) * Math.PI) / 180;
                const r = (subDescOuter + subDescInner) / 2;
                const x = cx + r * Math.cos(rad);
                const y = cy + r * Math.sin(rad);
                ctx.save();
                ctx.translate(x, y);
                let rotate = rad + Math.PI / 2;
                if (s.angle > 90 && s.angle < 270) rotate += Math.PI;
                ctx.rotate(rotate);
                ctx.font = 'bold 10px Arial';
                ctx.fillStyle = '#fff';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(s.code, 0, 0);
                ctx.restore();
            });

            // Draw description boxes
            papiSegments.forEach((s) => {
                const sa = ((s.angle - 9 - 90) * Math.PI) / 180;
                const ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                ctx.beginPath();
                ctx.arc(cx, cy, boxOuter, sa, ea);
                ctx.arc(cx, cy, boxInner, ea, sa, true);
                ctx.closePath();
                ctx.fillStyle = s.color;
                ctx.fill();
                ctx.strokeStyle = s.colorSolid;
                ctx.lineWidth = 1;
                ctx.stroke();
            });

            // Draw descriptions
            papiSegments.forEach((s) => {
                const rad = ((s.angle - 90) * Math.PI) / 180;
                const x = cx + descR * Math.cos(rad);
                const y = cy + descR * Math.sin(rad);
                ctx.save();
                ctx.translate(x, y);
                let rotate = rad + Math.PI / 2;
                if (s.angle > 90 && s.angle < 270) rotate += Math.PI;
                ctx.rotate(rotate);
                ctx.font = '8px Arial';
                ctx.fillStyle = '#000';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const lines = s.desc.split('\n');
                lines.forEach((line, i) => {
                    ctx.fillText(line, 0, (i - (lines.length - 1) / 2) * 8);
                });
                ctx.restore();
            });

            // Draw radar chart
            drawRadarChart(canvas, papiData, cx, cy, radarR, canvasId);
        }

        // Fungsi untuk menggambar radar chart
        function drawRadarChart(canvas, papiData, cx, cy, r, canvasId) {
            const parent = canvas.parentElement;
            const existingRadar = parent.querySelector('.radar-chart-container');
            if (existingRadar) existingRadar.remove();

            const radarDiv = document.createElement('div');
            radarDiv.className = 'radar-chart-container';
            radarDiv.style.position = 'absolute';
            radarDiv.style.left = cx - r + 'px';
            radarDiv.style.top = cy - r + 'px';
            radarDiv.style.width = r * 2 + 'px';
            radarDiv.style.height = r * 2 + 'px';

            const radarCanvas = document.createElement('canvas');
            radarCanvas.width = r * 2;
            radarCanvas.height = r * 2;
            radarDiv.appendChild(radarCanvas);
            parent.appendChild(radarDiv);

            if (typeof Chart === 'undefined') {
                console.error('Chart.js not loaded');
                return null;
            }

            try {
                const chart = new Chart(radarCanvas, {
                    type: 'radar',
                    data: {
                        labels: papiSegments.map((s) => s.code),
                        datasets: [
                            {
                                label: 'PAPI Score',
                                data: papiSegments.map((s) => {
                                    const data = papiData.find((d) => d.code === s.code);
                                    return data ? parseFloat(data.score) : 0;
                                }),
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: '#2563EB',
                                borderWidth: 2,
                                pointRadius: 2,
                                pointBackgroundColor: '#2563EB',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1,
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function (context) {
                                        const segment = papiSegments[context[0].dataIndex];
                                        return `${segment.code} - ${segment.cat}`;
                                    },
                                    label: function (context) {
                                        const segment = papiSegments[context.dataIndex];
                                        return `${segment.desc.replace(/\n/g, ' ')}: ${context.raw}`;
                                    },
                                },
                            },
                        },
                        scales: {
                            r: {
                                min: 0,
                                max: 9,
                                pointLabels: { display: false },
                                ticks: { stepSize: 1 },
                            },
                        },
                        maintainAspectRatio: false,
                        responsive: true,
                    },
                });

                chartInstances.papi[canvasId] = chart;
                return chart;
            } catch (error) {
                console.error('Error creating radar chart:', error);
                return null;
            }
        }

        // Fungsi untuk menggambar chart DISC
        function drawDISCChart(canvasId, discData, chartType = 1) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return null;

            if (typeof Chart === 'undefined') {
                console.error('Chart.js not loaded');
                return null;
            }

            const existingChart = Chart.getChart(canvas);
            if (existingChart) existingChart.destroy();

            const colorMap = {
                1: { border: '#ef4444', background: 'rgba(239, 68, 68, 0.1)' },
                2: { border: '#eab308', background: 'rgba(234, 179, 8, 0.1)' },
                3: { border: '#22c55e', background: 'rgba(34, 197, 94, 0.1)' },
            };

            const colors = colorMap[chartType] || colorMap[1];

            try {
                const chart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: ['D', 'I', 'S', 'C'],
                        datasets: [
                            {
                                label: `DISC ${chartType}`,
                                data: [
                                    parseFloat(discData.D) || 0,
                                    parseFloat(discData.I) || 0,
                                    parseFloat(discData.S) || 0,
                                    parseFloat(discData.C) || 0,
                                ],
                                borderColor: colors.border,
                                backgroundColor: colors.background,
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 3,
                                pointBorderColor: colors.border,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: { padding: { top: 10, bottom: 10, left: 5, right: 5 } },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function (context) {
                                        return `Score: ${context.parsed.y}`;
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                ticks: { stepSize: 20, font: { size: 11 } },
                                grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: true },
                            },
                            x: {
                                ticks: { font: { size: 13, weight: 'bold' }, color: '#374151' },
                                grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: true },
                            },
                        },
                    },
                });

                const chartKey = canvasId;
                if (chartType === 1) chartInstances.disc1[chartKey] = chart;
                else if (chartType === 2) chartInstances.disc2[chartKey] = chart;
                else chartInstances.disc3[chartKey] = chart;

                return chart;
            } catch (error) {
                console.error('Error creating DISC chart:', error);
                return null;
            }
        }

        // Fungsi untuk menginisialisasi semua charts
        function initializeCharts() {
            console.log('Initializing charts...');

            // Initialize PAPI charts
            const columnCount = document.querySelectorAll('[id^="papiChart-"]').length;
            for (let i = 0; i < columnCount; i++) {
                const canvasId = `papiChart-${i}`;
                const canvas = document.getElementById(canvasId);

                if (canvas) {
                    try {
                        const papiDataAttr = canvas.getAttribute('data-papi-data');
                        if (papiDataAttr && papiDataAttr !== '[]' && papiDataAttr !== 'null') {
                            const papiData = JSON.parse(papiDataAttr);
                            drawPAPIChart(canvasId, papiData);
                        }
                    } catch (error) {
                        console.error(`Error parsing PAPI data for ${canvasId}:`, error);
                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    }
                }
            }

            // Initialize DISC charts
            for (let i = 0; i < columnCount; i++) {
                [1, 2, 3].forEach((discType) => {
                    const discCanvas = document.getElementById(`disc${discType}Chart-${i}`);
                    if (discCanvas) {
                        try {
                            const discDataAttr = discCanvas.getAttribute('data-disc-data');
                            if (discDataAttr && discDataAttr !== '{}' && discDataAttr !== 'null') {
                                const discData = JSON.parse(discDataAttr);
                                if (discData && typeof discData === 'object' && Object.keys(discData).length > 0) {
                                    drawDISCChart(`disc${discType}Chart-${i}`, discData, discType);
                                }
                            }
                        } catch (error) {
                            console.error(`Error parsing DISC ${discType} data:`, error);
                        }
                    }
                });
            }
        }

        // Load Chart.js jika belum dimuat
        function loadChartJS() {
            return new Promise((resolve) => {
                if (typeof Chart !== 'undefined') {
                    resolve();
                    return;
                }

                const chartScript = document.createElement('script');
                chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                chartScript.onload = resolve;
                document.head.appendChild(chartScript);
            });
        }

        // Event listener untuk DOM ready
        document.addEventListener('DOMContentLoaded', async function () {
            console.log('DOM loaded, initializing charts...');

            try {
                await loadChartJS();
                console.log('Chart.js loaded');

                setTimeout(() => {
                    initializeCharts();
                }, 100);

                let resizeTimer;
                window.addEventListener('resize', function () {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        console.log('Window resized, reinitializing charts');
                        initializeCharts();
                    }, 250);
                });
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        });

        // Livewire navigation event
        document.addEventListener('livewire:navigated', () => {
            console.log('Livewire navigated, reinitializing charts');
            setTimeout(() => {
                initializeCharts();
            }, 500);
        });

        // Cleanup ketika komponen di-unmount
        document.addEventListener('livewire:before-unload', function () {
            console.log('Cleaning up charts before unmount');
            destroyAllCharts();
        });
    </script>
</div>
