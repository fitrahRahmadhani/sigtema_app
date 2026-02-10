<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('requirements.position') }}">
            Position Requirements
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Requirements</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create Position Requirements</h1>
                    <p class="mt-1 text-sm text-gray-500">Add requirements for a specific position</p>
                    @if ($selectedCompany)
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Position Selection --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Select Position</h3>

                {{-- Company Display --}}
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Company
                        <span class="text-red-600">*</span>
                    </label>
                    <div
                        class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                    >
                        <p>{{ $selectedCompany->name }}</p>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $isSuperadmin ? 'Selected from your session' : 'Your assigned company' }}
                    </p>
                </div>

                {{-- Position Selection with Search --}}
                @if ($positionFromUrl)
                    {{-- Position from URL (Read-only) --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Position
                            <span class="text-red-600">*</span>
                        </label>
                        <div
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                        >
                            <p class="font-medium">{{ $selectedPosition->name }}</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $selectedPosition->position_code }}
                                @if ($selectedPosition->work_unit)
                                    • {{ $selectedPosition->work_unit }}
                                @endif
                            </p>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Position is fixed from URL parameter and cannot be changed
                        </p>

                        {{-- Display Position Details --}}
                        <div class="mt-3 rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <div class="flex items-start">
                                <svg class="mt-0.5 mr-3 h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-blue-800">Position Details</h4>
                                    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs text-gray-500">Position Code</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $selectedPosition->position_code }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Position Name</p>
                                            <p class="font-medium text-gray-900">{{ $selectedPosition->name }}</p>
                                        </div>
                                        @if ($selectedPosition->job_grade)
                                            <div>
                                                <p class="text-xs text-gray-500">Job Grade</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPosition->job_grade }}
                                                </p>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-xs text-gray-500">Company</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $selectedPosition->company->name }}
                                            </p>
                                        </div>
                                        @if ($selectedPosition->work_unit)
                                            <div>
                                                <p class="text-xs text-gray-500">Work Unit</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPosition->work_unit }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Position Search (Normal Mode) --}}
                    <div
                        x-data="{
                            open: @entangle('showPositionDropdown').live,
                            searchLength: @entangle('positionSearch').live.length,
                        }"
                        @click.outside="$wire.closeDropdown()"
                    >
                        <label for="position_search" class="mb-2 block text-sm font-medium text-gray-700">
                            Position
                            <span class="text-red-600">*</span>
                        </label>

                        <div class="relative">
                            {{-- Search Input --}}
                            <div class="relative">
                                <input
                                    type="text"
                                    id="position_search"
                                    wire:model.live.debounce.500ms="positionSearch"
                                    @focus="$wire.openDropdown()"
                                    @keydown.arrow-down.prevent="$wire.incrementHighlight()"
                                    @keydown.arrow-up.prevent="$wire.decrementHighlight()"
                                    @keydown.enter.prevent="$wire.selectHighlighted()"
                                    @keydown.escape="$wire.closeDropdown()"
                                    placeholder="Type at least 5 characters to search..."
                                    class="@error('position_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    autocomplete="off"
                                />

                                {{-- Icons --}}
                                <div class="absolute top-1/2 right-3 flex -translate-y-1/2 items-center gap-2">
                                    {{-- Loading Indicator --}}
                                    <div wire:loading wire:target="positionSearch" class="text-gray-400">
                                        <svg
                                            class="h-4 w-4 animate-spin"
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
                                    </div>

                                    {{-- Clear Button --}}
                                    @if ($selectedPosition)
                                        <button
                                            type="button"
                                            wire:click="clearPositionSelection"
                                            class="text-gray-400 transition hover:text-gray-600"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Search Icon --}}
                                    <svg
                                        wire:loading.remove
                                        wire:target="positionSearch"
                                        class="h-4 w-4 text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            {{-- Dropdown Results --}}
                            <div
                                x-show="open"
                                x-transition:enter="transition duration-100 ease-out"
                                x-transition:enter-start="scale-95 transform opacity-0"
                                x-transition:enter-end="scale-100 transform opacity-100"
                                x-transition:leave="transition duration-75 ease-in"
                                x-transition:leave-start="scale-100 transform opacity-100"
                                x-transition:leave-end="scale-95 transform opacity-0"
                                class="absolute z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg"
                                style="display: none"
                            >
                                <div class="max-h-60 overflow-y-auto">
                                    @if ($this->filteredPositions->count() > 0)
                                        <ul class="py-1">
                                            @foreach ($this->filteredPositions as $index => $position)
                                                <li>
                                                    <button
                                                        type="button"
                                                        wire:click="selectPosition({{ $position->id }})"
                                                        class="{{ $highlightedIndex === $index ? 'bg-[#721d21] text-white' : 'text-gray-700 hover:bg-gray-100' }} flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition"
                                                    >
                                                        {{-- Icon --}}
                                                        <div
                                                            class="{{ $highlightedIndex === $index ? 'bg-white/20' : 'bg-gray-200' }} flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                                        >
                                                            <svg
                                                                class="{{ $highlightedIndex === $index ? 'text-white' : 'text-gray-600' }} h-4 w-4"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                                />
                                                            </svg>
                                                        </div>

                                                        {{-- Position Info --}}
                                                        <div class="min-w-0 flex-1">
                                                            <div class="font-medium">{{ $position->name }}</div>
                                                            <div
                                                                class="{{ $highlightedIndex === $index ? 'text-white/80' : 'text-gray-500' }} text-xs"
                                                            >
                                                                {{ $position->position_code }}
                                                                @if ($position->work_unit)
                                                                        • {{ $position->work_unit }}
                                                                @endif
                                                            </div>
                                                        </div>

                                                        {{-- Grade Badge --}}
                                                        @if ($position->job_grade)
                                                            <span
                                                                class="{{ $highlightedIndex === $index ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }} rounded-full px-2 py-1 text-xs font-medium"
                                                            >
                                                                Job Grade: {{ $position->job_grade }}
                                                            </span>
                                                        @endif
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="px-4 py-8 text-center">
                                            <svg
                                                class="mx-auto h-12 w-12 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-gray-700">No positions found</p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                No results matching "{{ $positionSearch }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer Hint --}}
                                @if ($this->filteredPositions->count() > 0)
                                    <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium">{{ $this->filteredPositions->count() }}</span>
                                            result{{ $this->filteredPositions->count() > 1 ? 's' : '' }} found • Use ↑
                                            ↓ to navigate, Enter to select
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @error('position_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Search Hint --}}
                        @if (! $selectedPosition)
                            <p class="mt-1 text-sm text-gray-500">
                                @if (strlen($positionSearch) > 0 && strlen($positionSearch) < 5)
                                    <svg
                                        class="mr-1 inline-block h-4 w-4 text-yellow-500"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                        />
                                    </svg>
                                    {{ 5 - strlen($positionSearch) }} more
                                    character{{ 5 - strlen($positionSearch) > 1 ? 's' : '' }} needed
                                @else
                                    <svg
                                        class="mr-1 inline-block h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    Type at least 5 characters to search for positions
                                @endif
                            </p>
                        @endif

                        {{-- Selected Position Display --}}
                        @if ($selectedPosition)
                            <div class="mt-3 rounded-lg border border-green-200 bg-green-50 p-4">
                                <div class="flex items-start">
                                    <svg
                                        class="mt-0.5 mr-3 h-5 w-5 text-green-500"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-green-800">Selected Position</h4>
                                        <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                            <div>
                                                <p class="text-xs text-gray-500">Position Code</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPosition->position_code }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Position Name</p>
                                                <p class="font-medium text-gray-900">{{ $selectedPosition->name }}</p>
                                            </div>
                                            @if ($selectedPosition->job_grade)
                                                <div>
                                                    <p class="text-xs text-gray-500">Job Grade</p>
                                                    <p class="font-medium text-gray-900">
                                                        {{ $selectedPosition->job_grade }}
                                                    </p>
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-xs text-gray-500">Company</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPosition->company->name }}
                                                </p>
                                            </div>
                                            @if ($selectedPosition->work_unit)
                                                <div>
                                                    <p class="text-xs text-gray-500">Work Unit</p>
                                                    <p class="font-medium text-gray-900">
                                                        {{ $selectedPosition->work_unit }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Requirements Section --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Position Requirements</h3>
                    <button
                        type="button"
                        wire:click="addRequirementRow"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-200"
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
                        Add Row
                    </button>
                </div>

                <p class="mb-6 text-sm text-gray-500">
                    Add requirements for this position. You can add multiple rows before submitting.
                </p>

                @if (count($requirements) === 0)
                    <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="mx-auto h-12 w-12 text-gray-400"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <p class="mt-4 font-medium text-gray-500">Click "Add Row" to start adding requirements</p>
                    </div>
                @else
                    {{-- Requirements Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Requirement Name
                                        <span class="text-red-600">*</span>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Value
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($requirements as $index => $requirement)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <input
                                                type="text"
                                                wire:model="requirements.{{ $index }}.name"
                                                placeholder="e.g., Education, Experience, Certification"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            />
                                            @error("requirements.{$index}.name")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea
                                                wire:model="requirements.{{ $index }}.value"
                                                rows="2"
                                                placeholder="e.g., Bachelor's Degree in Computer Science, 3+ years experience, PMP Certification"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            ></textarea>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            @if (count($requirements) > 1)
                                                <button
                                                    type="button"
                                                    wire:click="removeRequirementRow({{ $index }})"
                                                    class="inline-flex items-center rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 transition-colors hover:bg-red-200"
                                                    title="Remove this row"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="mr-1 h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                        />
                                                    </svg>
                                                    Remove
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400">Cannot remove the only row</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Form Actions --}}
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
<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
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
                        Create {{ count($requirements) }} Requirement(s)
                    </span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
