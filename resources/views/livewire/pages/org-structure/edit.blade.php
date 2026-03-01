<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organization-structure.index') }}">
            Organization Structure (Chart)
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organization-structure.table') }}">
            Organization Structure (Table)
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Hierarchy</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="currentColor"
                        class="h-6 w-6 text-white"
                        viewBox="0 0 16 16"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 9 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Hierarchy</h1>
                    <p class="mt-1 text-sm text-gray-500">Update reporting relationship</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-6">
            {{-- Company (Read-only dari relasi hierarchy) --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Company
                    <span class="text-red-600">*</span>
                </label>
                <div
                    class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                >
                    <p>{{ $company->name }} ({{ $company->cocd }})</p>
                </div>
                <p class="mt-1 text-sm text-gray-500">Company from this hierarchy record</p>
            </div>

            {{-- Position Search --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-1 text-lg font-semibold text-gray-900">Position</h3>
                <p class="mb-4 text-xs text-gray-500">The subordinate position in this relationship</p>

                <div
                    x-data="{ open: @entangle('showPositionDropdown').live }"
                    @click.outside="$wire.closePositionDropdown()"
                >
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="positionSearch"
                            @focus="$wire.openPositionDropdown()"
                            @keydown.arrow-down.prevent="$wire.incrementPositionHighlight()"
                            @keydown.arrow-up.prevent="$wire.decrementPositionHighlight()"
                            @keydown.enter.prevent="$wire.selectHighlightedPosition()"
                            @keydown.escape="$wire.closePositionDropdown()"
                            placeholder="Type at least 3 characters to search..."
                            class="@error('position_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            autocomplete="off"
                        />
                        <div class="absolute top-5 right-3 flex -translate-y-1/2 items-center gap-2">
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
                        @if (! $selectedPosition && strlen($positionSearch) > 0 && strlen($positionSearch) < 3)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ 3 - strlen($positionSearch) }} more
                                character{{ 3 - strlen($positionSearch) > 1 ? 's' : '' }} needed
                            </div>
                        @endif
                    </div>

                    <div
                        x-show="open"
                        x-transition:enter="transition duration-100 ease-out"
                        x-transition:enter-start="scale-95 transform opacity-0"
                        x-transition:enter-end="scale-100 transform opacity-100"
                        x-transition:leave="transition duration-75 ease-in"
                        x-transition:leave-start="scale-100 transform opacity-100"
                        x-transition:leave-end="scale-95 transform opacity-0"
                        class="relative z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg"
                        style="display: none"
                    >
                        <div class="max-h-60 overflow-y-auto">
                            @if ($this->filteredPositions->count() > 0)
                                <ul class="py-1">
                                    @foreach ($this->filteredPositions as $index => $pos)
                                        <li>
                                            <button
                                                type="button"
                                                wire:click="selectPosition({{ $pos->id }})"
                                                class="{{ $positionHighlightedIndex === $index ? 'bg-[#721d21] text-white' : 'text-gray-700 hover:bg-gray-100' }} flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition"
                                            >
                                                <div
                                                    class="{{ $positionHighlightedIndex === $index ? 'bg-white/20' : 'bg-gray-200' }} flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                                >
                                                    <svg
                                                        class="{{ $positionHighlightedIndex === $index ? 'text-white' : 'text-gray-600' }} h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="font-medium">{{ $pos->name }}</div>
                                                    <div
                                                        class="{{ $positionHighlightedIndex === $index ? 'text-white/80' : 'text-gray-500' }} text-xs"
                                                    >
                                                        {{ $pos->position_code }}
                                                    </div>
                                                </div>
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
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                        />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-700">No positions found</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        No results matching "{{ $positionSearch }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                        @if ($this->filteredPositions->count() > 0)
                            <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">{{ $this->filteredPositions->count() }}</span>
                                    result{{ $this->filteredPositions->count() > 1 ? 's' : '' }} found • Use ↑ ↓ to
                                    navigate, Enter to select
                                </p>
                            </div>
                        @endif
                    </div>

                    @error('position_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if (! $selectedPosition && strlen($positionSearch) == 0)
                        <p class="mt-1 text-sm text-gray-500">
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
                            Type at least 3 characters to search for positions
                        </p>
                    @endif

                    @if ($selectedPosition)
                        <div class="mt-2 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 p-3">
                            <svg
                                class="h-5 w-5 flex-shrink-0 text-green-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-green-900">{{ $selectedPosition->name }}</p>
                                <p class="text-xs text-green-700">{{ $selectedPosition->position_code }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Superior Search --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-1 text-lg font-semibold text-gray-900">Reports To (Superior)</h3>
                <p class="mb-4 text-xs text-gray-500">The manager or supervisor for this position</p>

                <div
                    x-data="{ open: @entangle('showSuperiorDropdown').live }"
                    @click.outside="$wire.closeSuperiorDropdown()"
                >
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="superiorSearch"
                            @focus="$wire.openSuperiorDropdown()"
                            @keydown.arrow-down.prevent="$wire.incrementSuperiorHighlight()"
                            @keydown.arrow-up.prevent="$wire.decrementSuperiorHighlight()"
                            @keydown.enter.prevent="$wire.selectHighlightedSuperior()"
                            @keydown.escape="$wire.closeSuperiorDropdown()"
                            placeholder="{{ ! $position_id ? 'Select a position first...' : 'Type at least 3 characters to search...' }}"
                            @if(!$position_id) disabled @endif
                            class="@error('superior_id') border-red-500 @else border-gray-300 @enderror {{ ! $position_id ? 'cursor-not-allowed opacity-50' : '' }} w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            autocomplete="off"
                        />
                        <div class="absolute top-5 right-3 flex -translate-y-1/2 items-center gap-2">
                            <div wire:loading wire:target="superiorSearch" class="text-gray-400">
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
                            @if ($selectedSuperior)
                                <button
                                    type="button"
                                    wire:click="clearSuperiorSelection"
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

                            <svg
                                wire:loading.remove
                                wire:target="superiorSearch"
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
                        @if (! $selectedSuperior && strlen($superiorSearch) > 0 && strlen($superiorSearch) < 3)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ 3 - strlen($superiorSearch) }} more
                                character{{ 3 - strlen($superiorSearch) > 1 ? 's' : '' }} needed
                            </div>
                        @endif
                    </div>

                    <div
                        x-show="open"
                        x-transition:enter="transition duration-100 ease-out"
                        x-transition:enter-start="scale-95 transform opacity-0"
                        x-transition:enter-end="scale-100 transform opacity-100"
                        x-transition:leave="transition duration-75 ease-in"
                        x-transition:leave-start="scale-100 transform opacity-100"
                        x-transition:leave-end="scale-95 transform opacity-0"
                        class="relative z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg"
                        style="display: none"
                    >
                        <div class="max-h-60 overflow-y-auto">
                            @if ($this->filteredSuperiors->count() > 0)
                                <ul class="py-1">
                                    @foreach ($this->filteredSuperiors as $index => $sup)
                                        <li>
                                            <button
                                                type="button"
                                                wire:click="selectSuperior({{ $sup->id }})"
                                                class="{{ $superiorHighlightedIndex === $index ? 'bg-[#721d21] text-white' : 'text-gray-700 hover:bg-gray-100' }} flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition"
                                            >
                                                <div
                                                    class="{{ $superiorHighlightedIndex === $index ? 'bg-white/20' : 'bg-gray-200' }} flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                                >
                                                    <svg
                                                        class="{{ $superiorHighlightedIndex === $index ? 'text-white' : 'text-gray-600' }} h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="font-medium">{{ $sup->name }}</div>
                                                    <div
                                                        class="{{ $superiorHighlightedIndex === $index ? 'text-white/80' : 'text-gray-500' }} text-xs"
                                                    >
                                                        {{ $sup->position_code }}
                                                    </div>
                                                </div>
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
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                        />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-700">No positions found</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        No results matching "{{ $superiorSearch }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                        @if ($this->filteredSuperiors->count() > 0)
                            <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">{{ $this->filteredSuperiors->count() }}</span>
                                    result{{ $this->filteredSuperiors->count() > 1 ? 's' : '' }} found • Use ↑ ↓ to
                                    navigate, Enter to select
                                </p>
                            </div>
                        @endif
                    </div>

                    @error('superior_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if (! $selectedSuperior && strlen($superiorSearch) == 0)
                        <p class="mt-1 text-sm text-gray-500">
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
                            {{ $position_id ? 'Type at least 3 characters to search for superior position' : 'Select a position first' }}
                        </p>
                    @endif

                    @if ($selectedSuperior)
                        <div class="mt-2 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 p-3">
                            <svg
                                class="h-5 w-5 flex-shrink-0 text-green-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-green-900">{{ $selectedSuperior->name }}</p>
                                <p class="text-xs text-green-700">{{ $selectedSuperior->position_code }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Relation Type --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Relation Type
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select
                        wire:model.live="relation_type"
                        class="@error('relation_type') border-red-500 @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                    >
                        <option value="direct">Direct Report</option>
                        <option value="indirect">Indirect Report</option>
                        <option value="dotted">Dotted Line</option>
                        <option value="functional">Functional Report</option>
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
                @error('relation_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-gray-500">Define the nature of the reporting relationship</p>
            </div>

            {{-- Hierarchy Preview --}}
            @if ($selectedPosition && $selectedSuperior)
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <h3 class="mb-4 text-sm font-medium text-gray-700">Hierarchy Preview</h3>
                    <div class="flex items-center justify-center gap-4">
                        <div class="rounded-lg border-2 border-gray-300 bg-white px-6 py-4 text-center">
                            <div class="text-sm font-medium text-gray-900">{{ $selectedPosition->name }}</div>
                            <div class="text-xs text-gray-500">{{ $selectedPosition->position_code }}</div>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-6 w-6 text-gray-400"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                                />
                            </svg>
                            <span class="text-xs font-medium text-gray-500">Reports to</span>
                        </div>
                        <div class="rounded-lg border-2 border-[#721d21] bg-red-50 px-6 py-4 text-center">
                            <div class="text-sm font-medium text-gray-900">{{ $selectedSuperior->name }}</div>
                            <div class="text-xs text-gray-500">{{ $selectedSuperior->position_code }}</div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium text-gray-700">
                            {{ ucfirst($relation_type) }} Relationship
                        </span>
                    </div>
                </div>
            @endif

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('organization-structure.table') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    @if (!$company_id || !$position_id || !$superior_id) disabled @endif
                    class="color-gradient inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg px-6 py-2.5 text-sm font-medium text-white shadow-sm transition disabled:cursor-not-allowed disabled:opacity-50"
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
                    <span wire:loading.remove wire:target="save">Update Hierarchy</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
