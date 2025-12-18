<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('psychogram-results.index') }}">
            Psychogram Results
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Add Result</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center gap-4">
            <div
                class="color-gradient flex h-12 w-12 items-center justify-center self-start rounded-xl bg-gradient-to-br shadow-sm"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-6 w-6 text-white"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Add Psychogram Result</h1>
                <p class="mt-1 text-sm text-gray-500">Record evaluation results for multiple aspects</p>

                @if ($selectedCompany)
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- COMPANY SELECTION SECTION -->
        @if ($showCompanySelection)
            <div class="mb-8 rounded-lg border border-gray-200 bg-white p-8 text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="mx-auto h-16 w-16 text-gray-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                    />
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Pilih Perusahaan</h3>
                <p class="mt-2 text-gray-500">
                    Silakan pilih perusahaan terlebih dahulu untuk menambah data hasil psikogram
                </p>

                <!-- Company List -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companiesWithCount as $item)
                        <a
                            href="{{ route('psychogram-results.index', ['company_id' => $item['company']->id]) }}"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($item['company']->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $item['company']->logo_path) }}"
                                            alt="{{ 'Logo ' . $item['company']->name }}"
                                        />
                                    @else
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6 text-gray-600"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                                            />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $item['company']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} data hasil psikogram</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <!-- MAIN CONTENT SECTION -->
            @if ($selectedCompany)
                <!-- Form -->
                <form wire:submit="save" class="space-y-8">
                    <!-- Employee Selection -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Employee Selection</h3>
                        <div class="space-y-6">
                            <!-- Company Selection (only for superadmin without session) -->
                            @if ($isSuperadmin)
                                <div>
                                    <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                                        Company
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <div class="relative">
                                        <select
                                            id="company_id"
                                            wire:model.live="company_id"
                                            class="@error('company_id') @enderror {{ $selectedCompanyId ? 'cursor-not-allowed bg-gray-100' : '' }} w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            @if($selectedCompanyId) disabled @endif
                                        >
                                            @if ($selectedCompanyId)
                                                <option value="{{ $selectedCompanyId }}">
                                                    {{ $selectedCompany->name }}
                                                </option>
                                            @else
                                                <option value="">Select a company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">
                                                        {{ $company->name }} ({{ $company->cocd }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <svg
                                            class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    @error('company_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    @if ($selectedCompanyId)
                                        <p class="mt-1 text-sm text-gray-500">
                                            Company is selected from your previous session
                                        </p>
                                    @endif
                                </div>
                            @else
                                <!-- For non-superadmin, show company name (readonly) -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Company
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <div
                                        class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                                    >
                                        <p>{{ $selectedCompany->name }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Your assigned company</p>
                                </div>
                            @endif

                            <!-- Employee Selection -->
                            <div>
                                <label for="employee_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Employee
                                    <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        id="employee_id"
                                        wire:model.live="employee_id"
                                        class="@error('employee_id') border-red-300 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        @if(!$company_id) disabled @endif
                                    >
                                        <option value="">Select an employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->name }} ({{ $employee->employee_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg
                                        class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                @error('employee_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if (! $company_id)
                                    <p class="mt-1 text-sm text-gray-500">Please select a company first</p>
                                @endif
                            </div>
                        </div>

                        <!-- Selected Employee Info -->
                        @if ($selectedEmployee)
                            <div class="mt-6 rounded-lg border border-blue-100 bg-blue-50 p-4">
                                <div class="flex items-center gap-3">
                                    @if ($selectedEmployee->profile_path)
                                        <img
                                            src="{{ Storage::url($selectedEmployee->profile_path) }}"
                                            alt="{{ $selectedEmployee->name }}"
                                            class="h-12 w-12 rounded-full border-2 border-white object-cover shadow-sm"
                                        />
                                    @else
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-800"
                                        >
                                            {{ substr($selectedEmployee->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-blue-900">{{ $selectedEmployee->name }}</p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            <span
                                                class="rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800"
                                            >
                                                {{ $selectedEmployee->employee_number }}
                                            </span>
                                            <span
                                                class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                            >
                                                {{ $selectedEmployee->company->name ?? 'No company' }}
                                            </span>
                                            <span
                                                class="rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                            >
                                                Grade: {{ $selectedEmployee->personal_grade }}
                                            </span>
                                            @if ($selectedEmployee->position)
                                                <span
                                                    class="rounded-md bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800"
                                                >
                                                    {{ $selectedEmployee->position->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Aspect & Score Input -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Aspect Evaluation</h3>
                            <button
                                type="button"
                                wire:click="addAspect"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:border-[#721d21] hover:bg-gray-50 hover:text-[#721d21]"
                                @if(!$employee_id) disabled @endif
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-4 w-4"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Aspect
                            </button>
                        </div>

                        @if (! $employee_id)
                            <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                <div class="flex items-center gap-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-5 w-5 text-yellow-600"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                                        />
                                    </svg>
                                    <p class="text-sm text-yellow-700">
                                        Please select an employee first to add aspects
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Score Legend -->
                        <div class="mb-6 rounded-lg border border-green-100 bg-green-50 p-4">
                            <h4 class="mb-2 text-sm font-semibold text-green-900">Score Rating Scale (1-5)</h4>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach ([1 => 'Very Poor', 2 => 'Poor', 3 => 'Average', 4 => 'Good', 5 => 'Excellent'] as $score => $label)
                                    <div class="text-center">
                                        <div
                                            class="@if ($score == 1) bg-red-100 text-red-800 @elseif ($score == 2) bg-orange-100 text-orange-800 @elseif ($score == 3) bg-yellow-100 text-yellow-800 @elseif ($score == 4) bg-lime-100 text-lime-800 @else bg-green-100 text-green-800 @endif mx-auto mb-1 flex h-8 w-8 items-center justify-center rounded-full"
                                        >
                                            <span class="font-bold">{{ $score }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-gray-700">{{ $label }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Aspect Input Rows -->
                        <div class="space-y-4">
                            @foreach ($aspects as $index => $aspect)
                                <div class="rounded-lg border border-gray-200 bg-white p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">
                                            Evaluation {{ $index + 1 }}
                                        </span>
                                        @if ($index > 0)
                                            <button
                                                type="button"
                                                wire:click="removeAspect({{ $index }})"
                                                class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600"
                                                title="Remove aspect"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="h-5 w-5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <!-- Aspect Selection -->
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                                Aspect
                                                <span class="text-red-600">*</span>
                                            </label>
                                            <div class="relative">
                                                <select
                                                    wire:model.live="aspects.{{ $index }}.aspect_id"
                                                    class="@error('aspects.' . $index . '.aspect_id') border-red-300 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                                    @if(!$employee_id) disabled @endif
                                                >
                                                    <option value="">Select an aspect</option>
                                                    @foreach ($availableAspects as $availableAspect)
                                                        @php
                                                            $isSelectedInOtherRow = false;
                                                            foreach ($aspects as $otherIndex => $otherAspect) {
                                                                if ($otherIndex != $index && $otherAspect['aspect_id'] == $availableAspect->id) {
                                                                    $isSelectedInOtherRow = true;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp

                                                        <option
                                                            value="{{ $availableAspect->id }}"
                                                            @if($aspect['aspect_id'] == $availableAspect->id) selected @endif
                                                            @if($isSelectedInOtherRow) disabled class="bg-gray-100 text-gray-400" @endif
                                                        >
                                                            {{ $availableAspect->aspect_code }} -
                                                            {{ $availableAspect->aspect_name }}

                                                            @if ($availableAspect->company)
                                                                ({{ $availableAspect->company->name }})
                                                            @else
                                                                    (Global)
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <svg
                                                    class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7"
                                                    />
                                                </svg>
                                            </div>
                                            @error('aspects.' . $index . '.aspect_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Score Selection -->
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                                Score (1-5)
                                                <span class="text-red-600">*</span>
                                            </label>
                                            <div class="flex items-center gap-2">
                                                <!-- Radio Buttons for Score -->
                                                <div class="flex flex-1 gap-1">
                                                    @foreach ([1, 2, 3, 4, 5] as $scoreValue)
                                                        <label class="flex-1 cursor-pointer">
                                                            <input
                                                                type="radio"
                                                                wire:model.live="aspects.{{ $index }}.score"
                                                                value="{{ $scoreValue }}"
                                                                class="peer hidden"
                                                                @if(!$employee_id) disabled @endif
                                                            />
                                                            <div
                                                                class="@if ($scoreValue == 1) hover:border-red-300 hover:bg-red-50 @elseif ($scoreValue == 2) hover:border-orange-300 hover:bg-orange-50 @elseif ($scoreValue == 3) hover:border-yellow-300 hover:bg-yellow-50 @elseif ($scoreValue == 4) hover:border-lime-300 hover:bg-lime-50 @else hover:border-green-300 hover:bg-green-50 @endif flex h-10 items-center justify-center rounded-lg border border-gray-300 text-center text-sm font-medium transition-all peer-checked:border-[#721d21] peer-checked:bg-[#721d21] peer-checked:text-white"
                                                            >
                                                                {{ $scoreValue }}
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <!-- Score Display -->
                                                <div
                                                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white"
                                                >
                                                    <span
                                                        class="@if ($aspect['score'] == 1) text-red-600 @elseif ($aspect['score'] == 2) text-orange-600 @elseif ($aspect['score'] == 3) text-yellow-600 @elseif ($aspect['score'] == 4) text-lime-600 @else text-green-600 @endif text-lg font-bold"
                                                    >
                                                        {{ $aspect['score'] }}
                                                    </span>
                                                </div>
                                            </div>
                                            @error('aspects.' . $index . '.score')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Selected Aspect Info -->
                                    @if ($aspect['aspect_id'])
                                        @php
                                            $selectedAspect = $availableAspects->firstWhere('id', $aspect['aspect_id']);
                                        @endphp

                                        @if ($selectedAspect)
                                            <div class="mt-3 rounded-lg border border-blue-100 bg-blue-50 p-3">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <p class="font-medium text-blue-900">
                                                            {{ $selectedAspect->aspect_name }}
                                                        </p>
                                                        <p class="text-sm text-blue-700">
                                                            {{ $selectedAspect->aspect_code }}
                                                        </p>
                                                        @if ($selectedAspect->description)
                                                            <p class="mt-1 text-sm text-blue-800">
                                                                {{ $selectedAspect->description }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800"
                                                    >
                                                        @if ($selectedAspect->company)
                                                            {{ $selectedAspect->company->name }}
                                                        @else
                                                                Global
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Summary Preview -->
                    @php
                        $hasSelectedAspects = false;
                        $filledAspects = [];
                        foreach ($aspects as $aspectData) {
                            if (! empty($aspectData['aspect_id'])) {
                                $hasSelectedAspects = true;
                                $filledAspects[] = $aspectData;
                            }
                        }
                        $averageScore = count($filledAspects) > 0 ? array_sum(array_column($filledAspects, 'score')) / count($filledAspects) : 0;
                    @endphp

                    @if ($selectedEmployee && $hasSelectedAspects)
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-6">
                            <h3 class="mb-4 text-lg font-semibold text-blue-900">Summary Preview</h3>
                            <div class="space-y-4">
                                <!-- Employee Info -->
                                <div class="rounded-lg bg-white p-4">
                                    <p class="text-sm font-medium text-gray-500">Employee</p>
                                    <div class="mt-2 flex items-center gap-3">
                                        @if ($selectedEmployee->profile_path)
                                            <img
                                                src="{{ Storage::url($selectedEmployee->profile_path) }}"
                                                alt="{{ $selectedEmployee->name }}"
                                                class="h-10 w-10 rounded-full border border-gray-200 object-cover"
                                            />
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-800"
                                            >
                                                {{ substr($selectedEmployee->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $selectedEmployee->name }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $selectedEmployee->employee_number }} •
                                                {{ $selectedEmployee->company->name ?? 'No company' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Aspect Scores Summary -->
                                <div class="rounded-lg bg-white p-4">
                                    <p class="text-sm font-medium text-gray-500">Aspect Scores</p>
                                    <div class="mt-3 space-y-2">
                                        @foreach ($aspects as $index => $aspectData)
                                            @if ($aspectData['aspect_id'])
                                                @php
                                                    $selectedAspect = $availableAspects->firstWhere('id', $aspectData['aspect_id']);
                                                @endphp

                                                @if ($selectedAspect)
                                                    <div
                                                        class="@if(!$loop->last) mb-2 @endif flex items-center justify-between border-b border-gray-100 pb-2"
                                                    >
                                                        <div class="flex-1">
                                                            <p class="font-medium text-gray-900">
                                                                {{ $selectedAspect->aspect_name }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $selectedAspect->aspect_code }}
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex items-center gap-1">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <div
                                                                        class="@if($i <= $aspectData['score']) bg-[#721d21] @else bg-gray-200 @endif h-2 w-2 rounded-full"
                                                                    ></div>
                                                                @endfor
                                                            </div>
                                                            <span
                                                                class="@if ($aspectData['score'] == 1) text-red-600 @elseif ($aspectData['score'] == 2) text-orange-600 @elseif ($aspectData['score'] == 3) text-yellow-600 @elseif ($aspectData['score'] == 4) text-lime-600 @else text-green-600 @endif text-lg font-bold"
                                                            >
                                                                {{ $aspectData['score'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Average Score -->
                                <div class="rounded-lg bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Average Score</p>
                                            <p class="text-2xl font-bold text-gray-900">
                                                {{ number_format($averageScore, 1) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-500">Total Aspects</p>
                                            <p class="text-xl font-bold text-[#721d21]">{{ count($filledAspects) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-gray-300 transition ring-inset hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            @if(!$selectedEmployee || !$hasSelectedAspects) disabled @endif
                        >
                            <svg
                                wire:loading.remove
                                wire:target="save"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                                />
                            </svg>
                            <svg
                                wire:loading
                                wire:target="save"
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
                            <span wire:loading.remove wire:target="save">
                                Save {{ $hasSelectedAspects ? count($filledAspects) : 0 }} Results
                            </span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            @endif
        @endif
    </div>
</div>
