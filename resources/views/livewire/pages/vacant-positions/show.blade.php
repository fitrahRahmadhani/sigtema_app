<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('vacant-positions.index') }}">Vacant Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Candidates Match</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="relative h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div
                    class="color-gradient mt-2 flex h-10 w-10 items-center justify-center rounded-xl bg-[#721d21] text-white"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-600 md:mt-1">AI-Powered Candidates Match</p>
                    <h6 class="text-2xl font-bold text-gray-900 md:text-3xl">{{ $position->name }}</h6>
                    <p class="text-sm text-gray-600 md:mt-1">
                        @if ($aiProcessing)
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 animate-ping rounded-full bg-[#721d21]"></span>
                                <span wire:loading.remove>{{ $aiStatus }}</span>
                                <span wire:loading>Processing...</span>
                            </span>
                        @else
                            {{ count($recommendations) }} AI-recommended candidates
                        @endif
                    </p>
                    <div class="mt-2 flex items-center gap-2">
                        <p class="font-semibold text-gray-600">Powered by</p>
                        <img src="{{ asset('assets/logo/logo-gemini.png') }}" class="w-22" alt="Logo Gemini AI" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-end gap-1 md:flex-row md:gap-2">
                <span class="block text-sm text-gray-600 md:inline">Selected:</span>
                <span class="block rounded-full bg-[#721d21] px-3 py-1 text-sm font-semibold text-white md:inline">
                    {{ count($selectedCandidates) }} / 5
                </span>
            </div>
        </div>

        <!-- AI Processing Overlay -->
        @if ($aiProcessing)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md">
                <div class="w-full max-w-2xl p-6">
                    <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <!-- AI Header -->
                        <div class="bg-gradient-to-r from-[#721d21] to-[#8a252a] p-8 text-center">
                            <div class="relative mx-auto mb-6 h-20 w-20">
                                <div class="absolute inset-0 animate-pulse rounded-full bg-white/20"></div>
                                <div
                                    class="relative flex h-full w-full items-center justify-center rounded-full border-4 border-white/30 bg-gradient-to-r from-white/10 to-white/20"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-10 w-10 text-white"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <h3 class="text-2xl font-bold text-white">Analyzing Candidates with AI</h3>
                            <p class="mt-2 text-white/80">Processing selected candidates for optimal matches</p>
                        </div>

                        <!-- Real-time Status -->
                        <div class="p-8">
                            <div class="mb-6 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 animate-ping rounded-full bg-[#721d21]"></span>
                                    <span class="text-lg font-medium text-gray-900">Current Status:</span>
                                </div>
                                <span class="text-lg font-semibold text-[#721d21]">{{ $aiStatus }}</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-8">
                                <div class="mb-2 flex justify-between text-sm font-medium text-gray-700">
                                    <span>Processing Progress</span>
                                    <span>{{ $aiProgress }}%</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-gray-200">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-[#721d21] to-[#8a252a] transition-all duration-700 ease-out"
                                        style="width: {{ $aiProgress }}%"
                                    ></div>
                                </div>
                            </div>

                            <!-- Progress Steps -->
                            <div class="mb-8 grid grid-cols-4 gap-4">
                                @foreach ([
                                        ['step' => 1, 'label' => 'Data Preparation', 'threshold' => 25],
                                        ['step' => 2, 'label' => 'File Analysis', 'threshold' => 50],
                                        ['step' => 3, 'label' => 'AI Processing', 'threshold' => 75],
                                        ['step' => 4, 'label' => 'Final Results', 'threshold' => 100]
                                    ]
                                    as $stepData)
                                    <div class="text-center">
                                        <div
                                            class="{{ $aiProgress >= $stepData['threshold'] ? 'border-[#721d21] bg-[#721d21] text-white' : 'border-gray-300 bg-gray-50 text-gray-400' }} mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full border-2 transition-colors duration-300"
                                        >
                                            <span class="font-semibold">{{ $stepData['step'] }}</span>
                                        </div>
                                        <p
                                            class="{{ $aiProgress >= $stepData['threshold'] ? 'font-semibold text-[#721d21]' : 'text-gray-500' }} text-sm transition-colors duration-300"
                                        >
                                            {{ $stepData['label'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Live Updates -->
                            <div
                                class="rounded-xl border border-[#721d21]/20 bg-gradient-to-r from-[#721d21]/5 to-transparent p-5"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="mt-1 flex-shrink-0">
                                        <svg
                                            class="h-6 w-6 text-[#721d21]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Live Analysis Updates</p>
                                        <p class="mt-1 text-gray-700">{{ $aiStatus }}</p>
                                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span>Estimated completion: 1-2 minutes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gemini AI Logo -->
                            <div class="mt-8 flex items-center justify-center gap-3 rounded-lg bg-gray-50 py-4">
                                <p class="text-sm font-medium text-gray-600">Powered by</p>
                                <img src="{{ asset('assets/logo/logo-gemini.png') }}" class="h-8" alt="Gemini AI" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Employee Selection Modal -->
        @if ($showEmployeeModal && ! $aiProcessing)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md">
                <div class="w-full max-w-4xl p-4">
                    <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-[#721d21] to-[#8a252a] p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Select Candidates for AI Analysis</h3>
                                    <p class="mt-1 text-white/80">
                                        Select up to {{ $maxCandidates }} employees to analyze
                                    </p>
                                </div>
                                <button
                                    wire:click="$set('showEmployeeModal', false)"
                                    class="rounded-lg p-2 text-white hover:bg-white/10"
                                >
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Search Section -->
                        <div class="border-b border-gray-200 p-6">
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    wire:model.live.debounce.300ms="searchTerm"
                                    type="text"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 pl-10 text-sm focus:border-[#721d21] focus:ring-[#721d21]"
                                    placeholder="Search by name or employee number..."
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Type at least 2 characters to search</p>
                        </div>

                        <!-- Selected Candidates -->
                        @if (count($selectedForAnalysis) > 0)
                            <div class="border-b border-gray-200 p-6">
                                <div class="mb-3 flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">Selected Candidates</h4>
                                    <span class="rounded-full bg-[#721d21] px-3 py-1 text-sm font-semibold text-white">
                                        {{ count($selectedForAnalysis) }} / {{ $maxCandidates }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($selectedForAnalysis as $employeeId)
                                        @php
                                            $employee = App\Models\Employee::find($employeeId);
                                        @endphp

                                        @if ($employee)
                                            <div class="flex items-center gap-2 rounded-full bg-[#721d21]/10 px-4 py-2">
                                                <span class="font-medium text-[#721d21]">{{ $employee->name }}</span>
                                                <button
                                                    wire:click="removeFromAnalysis({{ $employeeId }})"
                                                    type="button"
                                                    class="rounded-full p-1 text-[#721d21] hover:bg-[#721d21]/20"
                                                >
                                                    <svg
                                                        class="h-4 w-4"
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
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Search Results -->
                        <div class="max-h-96 overflow-y-auto p-6">
                            @if (count($availableEmployees) > 0)
                                <h4 class="mb-4 font-semibold text-gray-900">Search Results</h4>
                                <div class="space-y-3">
                                    @foreach ($availableEmployees as $employee)
                                        <div
                                            class="flex items-center justify-between rounded-lg border border-gray-200 p-4 hover:bg-gray-50"
                                        >
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#721d21]/10 to-[#8a252a]/10"
                                                >
                                                    <span class="font-bold text-[#721d21]">
                                                        {{ substr($employee['name'], 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $employee['name'] }}</p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $employee['employee_number'] }} • {{ $employee['band'] }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">{{ $employee['position'] }}</p>
                                                </div>
                                            </div>
                                            <button
                                                wire:click="addToAnalysis({{ $employee['id'] }})"
                                                type="button"
                                                class="rounded-lg bg-[#721d21] px-4 py-2 text-sm font-semibold text-white hover:bg-[#5a1719]"
                                            >
                                                Select
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif (strlen($searchTerm) >= 2)
                                <div class="py-8 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <p class="mt-2 text-gray-500">
                                        No employees found with keyword "{{ $searchTerm }}"
                                    </p>
                                </div>
                            @else
                                <div class="py-8 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                    <p class="mt-2 text-gray-500">Type name or employee number to search</p>
                                </div>
                            @endif
                        </div>

                        <!-- Modal Footer -->
                        <div class="border-t border-gray-200 bg-gray-50 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    @if (count($selectedForAnalysis) > 0)
                                        <p class="text-sm text-gray-600">
                                            {{ count($selectedForAnalysis) }} candidates selected
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-600">No candidates selected yet</p>
                                    @endif
                                </div>
                                <div class="flex gap-3">
                                    <button
                                        wire:click="$set('showEmployeeModal', false)"
                                        type="button"
                                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        wire:click="startAIProcessing"
                                        wire:loading.attr="disabled"
                                        type="button"
                                        class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#721d21] to-[#8a252a] px-8 py-3 text-sm font-semibold text-white hover:from-[#5a1719] hover:to-[#721d21] disabled:opacity-50"
                                        {{ count($selectedForAnalysis) === 0 ? 'disabled' : '' }}
                                    >
                                        <svg
                                            wire:loading
                                            class="h-5 w-5 animate-spin"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <span wire:loading.remove>Analyze with AI</span>
                                        <span wire:loading>Preparing...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Section -->
        <div class="space-y-6">
            <!-- AI Results Header -->
            @if (! $aiProcessing && count($recommendations) > 0)
                <div class="rounded-lg border border-gray-200 bg-gradient-to-r from-[#721d21]/5 to-[#8a252a]/5 p-6">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 flex-none items-center justify-center rounded-full bg-gradient-to-r from-[#721d21] to-[#8a252a]"
                            >
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">AI Analysis Complete</h3>
                                <p class="text-sm text-gray-600">Top matches based on comprehensive data analysis</p>
                            </div>
                        </div>
                        <button
                            wire:click="retryAIProcessing"
                            type="button"
                            class="mt-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#721d21]"
                        >
                            <svg
                                class="mr-2 -ml-1 inline h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                            Re-analyze
                        </button>
                    </div>
                </div>
            @endif

            <!-- Candidate Grid -->
            <div id="candidate-match" class="grid grid-cols-1 gap-6 pb-24 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($recommendations as $index => $candidate)
                    @php
                        $employee = App\Models\Employee::find($candidate['employee_id']);
                        $isSelected = in_array($employee->id, $selectedCandidates);
                        $isDisabled = count($selectedCandidates) >= 5 && ! $isSelected;
                    @endphp

                    <label
                        class="group {{ $isDisabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:shadow-lg' }} {{ $isSelected ? 'ring-opacity-20 border-[#721d21] bg-[#721d210e] shadow-lg ring-1 ring-[#721d21]' : 'border-gray-200 bg-white hover:border-[#721d21]' }} flex h-full flex-col rounded-xl border-2 p-4 transition-all duration-300 ease-in-out"
                    >
                        <div class="flex flex-1 gap-4">
                            <div class="flex w-full gap-4">
                                <!-- Candidate Avatar & Rank -->
                                <div class="relative">
                                    <div
                                        class="flex h-20 w-20 items-center justify-center rounded-lg bg-gradient-to-br from-[#721d21]/10 to-[#8a252a]/10"
                                    >
                                        @if ($employee->profile_path)
                                            <img
                                                src="{{ asset('storage/' . $employee->profile_path) }}"
                                                alt="{{ $employee->name }}"
                                                class="h-20 w-20 rounded-lg border-2 border-gray-200 object-cover"
                                            />
                                        @else
                                            <span class="text-2xl font-bold text-[#721d21]">
                                                {{ substr($employee->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <!-- Rank Badge -->
                                    <div
                                        class="absolute -top-2 -left-2 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-[#721d21] to-[#8a252a] shadow-md"
                                    >
                                        <p class="text-sm font-semibold text-white">{{ $index + 1 }}</p>
                                    </div>
                                    <!-- AI Badge -->
                                    <div class="absolute -top-2 -right-2">
                                        <div
                                            class="flex items-center gap-1 rounded-full bg-gradient-to-r from-[#721d21] to-[#8a252a] px-2 py-1 text-xs font-semibold text-white"
                                        >
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                                />
                                            </svg>
                                            <span>AI</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Candidate Info -->
                                <div class="flex-1">
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $employee->name }}</h2>
                                    <p class="text-sm text-gray-600">
                                        {{ $employee->band }} • {{ $employee->employee_number }}
                                    </p>

                                    <!-- AI Reason -->
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">{{ $candidate['reason'] }}</p>
                                    </div>

                                    <!-- Strengths -->
                                    @if (! empty($candidate['strengths']))
                                        <div class="mt-2">
                                            <p class="text-xs font-semibold text-[#721d21]">STRENGTHS</p>
                                            <ul class="mt-1 space-y-1">
                                                @foreach (array_slice($candidate['strengths'], 0, 3) as $strength)
                                                    <li class="flex items-start gap-2 text-xs">
                                                        <svg class="h-3 w-3 mt-0.5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>{{ $strength }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Weaknesses -->
                                    @if (! empty($candidate['weaknesses']))
                                        <div class="mt-2">
                                            <p class="text-xs font-semibold text-red-600">CONSIDERATIONS</p>
                                            <ul class="mt-1 space-y-1">
                                                @foreach (array_slice($candidate['weaknesses'], 0, 2) as $weakness)
                                                    <li class="flex items-start gap-2 text-xs">
                                                        <svg class="h-3 w-3 mt-0.5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span>{{ $weakness }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Checkbox -->
                            <div class="relative flex items-start pt-1">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedCandidates"
                                    value="{{ $employee->id }}"
                                    class="sr-only"
                                    {{ $isDisabled ? 'disabled' : '' }}
                                />
                                <div
                                    class="{{ $isSelected ? 'border-[#721d21] bg-[#721d21]' : ($isDisabled ? 'cursor-not-allowed border-gray-200 bg-gray-100' : 'cursor-pointer border-gray-300 bg-white hover:border-[#721d21]') }} flex h-6 w-6 items-center justify-center rounded-md border-2 transition-all duration-300 ease-in-out"
                                >
                                    <svg
                                        class="{{ $isSelected ? 'scale-100' : 'scale-0' }} h-4 w-4 text-white transition-transform duration-300 ease-in-out"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="3"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Match Score & Footer - Selalu di bawah -->
                        <div class="mt-auto">
                            <!-- Recommendation Badge -->
                            @if (!empty($candidate['recommendation']))
                                <div class="mb-2">
                                    @php
                                        $badgeClass = match($candidate['recommendation']) {
                                            'Highly Recommended' => 'bg-green-100 text-green-800 border-green-200',
                                            'Recommended' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Not Recommended' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-block rounded-full border px-3 py-1 text-xs font-medium {{ $badgeClass }}">
                                        {{ $candidate['recommendation'] }}
                                    </span>
                                </div>
                            @endif

                            <!-- Match Score -->
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">AI Match Score</span>
                                    <span class="text-sm font-semibold text-[#721d21]">
                                        {{ $candidate['match_score'] }}%
                                    </span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-200">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-[#721d21]/70 to-[#721d21] transition-all duration-1000 ease-out"
                                        style="width: {{ $candidate['match_score'] }}%"
                                    ></div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-4 flex items-end justify-between border-t border-gray-200 pt-3">
                                <a
                                    href="{{ route('employees.show', $employee->slug) }}"
                                    class="{{ $isDisabled ? 'pointer-events-none' : '' }} text-sm text-[#721d21] transition-colors hover:text-[#5a1719] hover:underline"
                                    onclick="event.stopPropagation();"
                                >
                                    View Profile
                                </a>
                                <div class="text-right">
                                    <p class="font-semibold text-[#721d21]">{{ $candidate['match_score'] }}% Match</p>
                                    <p class="text-xs text-gray-500">AI Recommended</p>
                                </div>
                            </div>
                        </div>
                    </label>
                @empty
                    @unless ($aiProcessing)
                        <div
                            class="col-span-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No AI Recommendations</h3>
                            <p class="mt-2 text-gray-500">Click below to analyze candidates with AI</p>
                            <div class="mt-6">
                                <button
                                    wire:click="$set('showEmployeeModal', true)"
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#721d21] to-[#8a252a] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:from-[#5a1719] hover:to-[#721d21]"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                        />
                                    </svg>
                                    Choose Candidates
                                </button>
                            </div>
                        </div>
                    @endunless
                @endforelse
            </div>
        </div>

        <!-- Action Buttons -->
        @if (count($selectedCandidates) > 0)
            <div
                x-data
                x-show="true"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="translate-y-8 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition duration-200 ease-in"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-8 opacity-0"
                class="fixed right-0 bottom-0 left-0 z-10 flex items-center justify-center bg-gradient-to-t from-white via-white/95 to-transparent px-4 pt-20 pb-6"
            >
                <div class="flex max-w-7xl items-center justify-center gap-4 lg:pl-72">
                    <button
                        type="button"
                        wire:click="$set('selectedCandidates', [])"
                        wire:loading.attr="disabled"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-md transition-all hover:bg-gray-50 hover:shadow-lg active:scale-95 disabled:opacity-50"
                    >
                        Clear Selection
                    </button>
                    <button
                        type="button"
                        wire:click="compareSelected"
                        wire:loading.attr="disabled"
                        wire:target="compareSelected"
                        class="flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#721d21] to-[#8a252a] px-8 py-3 text-sm font-medium text-white shadow-md transition-all hover:from-[#5a1719] hover:to-[#721d21] hover:shadow-lg active:scale-95 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <svg
                            wire:loading
                            wire:target="compareSelected"
                            class="h-5 w-5 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <span wire:loading.remove wire:target="compareSelected">
                            Compare {{ count($selectedCandidates) }} Selected
                        </span>
                        <span wire:loading wire:target="compareSelected">Comparing...</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
