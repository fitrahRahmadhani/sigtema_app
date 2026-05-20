<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('burnouts.index') }}">Employee Burnout</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Burnout</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4">
            <div
                class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-sm"
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
                        d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M4.5 10.5h6.75V15H4.5v-4.5ZM3.75 18h15A2.25 2.25 0 0 0 21 15.75v-6a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 1.5 9.75v6A2.25 2.25 0 0 0 3.75 18Z"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create New Burnout Record</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new burnout assessment record</p>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Employee Selection --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Employee Information</h3>

                {{-- Employee Search --}}
                <div class="mb-6">
                    <div
                        x-data="{
                            open: @entangle('showEmployeeDropdown').live,
                            searchLength: @entangle('employeeSearch').live.length,
                        }"
                        @click.outside="$wire.closeDropdown()"
                    >
                        <label for="employee_search" class="mb-2 block text-sm font-medium text-gray-700">Employee</label>

                        <div class="relative">
                            {{-- Search Input --}}
                            <div class="relative">
                                <input
                                    type="text"
                                    id="employee_search"
                                    wire:model.live.debounce.500ms="employeeSearch"
                                    @focus="$wire.openDropdown()"
                                    @keydown.arrow-down.prevent="$wire.incrementHighlight()"
                                    @keydown.arrow-up.prevent="$wire.decrementHighlight()"
                                    @keydown.enter.prevent="$wire.selectHighlighted()"
                                    @keydown.escape="$wire.closeDropdown()"
                                    placeholder="Type to search employee by name or number..."
                                    class="@error('employee_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    autocomplete="off"
                                />

                                {{-- Icons --}}
                                <div class="absolute top-5 right-3 flex -translate-y-1/2 items-center gap-2">
                                    {{-- Loading Indicator --}}
                                    <div wire:loading wire:target="employeeSearch" class="text-gray-400">
                                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>

                                    {{-- Clear Button --}}
                                    @if ($selectedEmployee)
                                        <button type="button" wire:click="clearEmployeeSelection" class="text-gray-400 transition hover:text-gray-600">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Search Icon --}}
                                    <svg wire:loading.remove wire:target="employeeSearch" class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Dropdown Results --}}
                            <div x-show="open" x-transition:enter="transition duration-100 ease-out" x-transition:enter-start="scale-95 transform opacity-0" x-transition:enter-end="scale-100 transform opacity-100" x-transition:leave="transition duration-75 ease-in" x-transition:leave-start="scale-100 transform opacity-100" x-transition:leave-end="scale-95 transform opacity-0" class="absolute z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg" style="display: none">
                                <div class="max-h-60 overflow-y-auto">
                                    @if ($this->filteredEmployees->count() > 0)
                                        <ul class="py-1">
                                            @foreach ($this->filteredEmployees as $index => $employee)
                                                <li>
                                                    <button type="button" wire:click="selectEmployee({{ $employee->id }})" class="{{ $highlightedIndex === $index ? 'bg-[#721d21] text-white' : 'text-gray-700 hover:bg-gray-100' }} flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition">
                                                        <div class="{{ $highlightedIndex === $index ? 'bg-white/20' : 'bg-gray-200' }} flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full">
                                                            <svg class="{{ $highlightedIndex === $index ? 'text-white' : 'text-gray-600' }} h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="font-medium">{{ $employee->name }}</div>
                                                            <div class="{{ $highlightedIndex === $index ? 'text-white/80' : 'text-gray-500' }} text-xs">
                                                                {{ $employee->employee_number }}
                                                            </div>
                                                        </div>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="px-4 py-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-gray-700">No employees found</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Selected Employee Display --}}
                        @if ($selectedEmployee)
                            <div class="mt-2 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 p-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-green-900">{{ $selectedEmployee->name }}</p>
                                    <p class="text-xs text-green-700">{{ $selectedEmployee->employee_number }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Burnout Scores --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Burnout Scores</h3>
                <div class="grid gap-6 md:grid-cols-3">
                    {{-- Emotional Exhaustion --}}
                    <div>
                        <label for="emotional_exhaustion" class="mb-2 block text-sm font-medium text-gray-700">
                            Emotional Exhaustion (EE)
                        </label>
                        <input
                            type="number"
                            id="emotional_exhaustion"
                            wire:model.live="emotional_exhaustion"
                            placeholder="0-100"
                            min="0"
                            max="100"
                            class="@error('emotional_exhaustion') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('emotional_exhaustion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Score 0-100</p>
                    </div>

                    {{-- Depersonalization --}}
                    <div>
                        <label for="depersonalization" class="mb-2 block text-sm font-medium text-gray-700">
                            Depersonalization (DP)
                        </label>
                        <input
                            type="number"
                            id="depersonalization"
                            wire:model.live="depersonalization"
                            placeholder="0-100"
                            min="0"
                            max="100"
                            class="@error('depersonalization') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('depersonalization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Score 0-100</p>
                    </div>

                    {{-- Achievement Decline --}}
                    <div>
                        <label for="achievement_decline" class="mb-2 block text-sm font-medium text-gray-700">
                            Achievement Decline (AD)
                        </label>
                        <input type="number" id="achievement_decline" wire:model.live="achievement_decline" placeholder="0-100" min="0" max="100" class="@error('achievement_decline') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('achievement_decline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Score 0-100</p>
                    </div>

                    {{-- Total --}}
                    <div>
                        <label for="total" class="mb-2 block text-sm font-medium text-gray-700">
                            Total
                        </label>
                        <input type="number" id="total" wire:model.live="total" placeholder="Total score" class="@error('total') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('total')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Total burnout score</p>
                    </div>

                    {{-- Level --}}
                    <div>
                        <label for="level" class="mb-2 block text-sm font-medium text-gray-700">
                            Level
                        </label>
                        <input type="text" id="level" wire:model.live="level" placeholder="e.g., Low, Medium, High" class="@error('level') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-6">
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" wire:model.live="description" rows="3" placeholder="Enter burnout description..." class="@error('description') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recommendation --}}
                <div class="mt-6">
                    <label for="recommendation" class="mb-2 block text-sm font-medium text-gray-700">Recommendation</label>
                    <textarea id="recommendation" wire:model.live="recommendation" rows="3" placeholder="Enter recommendation..." class="@error('recommendation') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"></textarea>
                    @error('recommendation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Result --}}
                <div class="mt-6">
                    <label for="result" class="mb-2 block text-sm font-medium text-gray-700">Result</label>
                    <input type="text" id="result" wire:model.live="result" placeholder="e.g., Normal, Burnout, Severe Burnout" class="@error('result') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                    @error('result')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Status</h3>
                <div class="flex items-center gap-3">
                    <input type="hidden" value="0" wire:model.live="is_active">
                    <input type="checkbox" wire:model.live="is_active" value="1" class="h-5 w-5 cursor-pointer rounded border-gray-300 text-green-600 focus:ring-green-500"/>
                    <span class="text-sm text-gray-600">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                <button type="button" wire:click="cancel" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-gray-300 transition ring-inset hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50">
                    <svg wire:loading.remove wire:target="save" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <svg wire:loading wire:target="save" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Create Burnout Record</span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>