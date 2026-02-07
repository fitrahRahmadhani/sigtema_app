<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('position-histories.index') }}">
            Position Histories
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Add Position History</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-4">
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
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Add Position History</h1>
                    <p class="mt-1 text-sm text-gray-500">Add new position history for employee</p>

                    @if ($selectedCompany)
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-6">
            {{-- Employee Selection --}}
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Employee Information</h2>
                <div class="space-y-2">
                    {{-- Company (Read-only) --}}
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
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($isSuperadmin)
                                Company from your current session
                            @else
                                Your assigned company
                            @endif
                        </p>
                    </div>

                    {{-- Employee Selection with Search --}}
                    <div
                        x-data="{
                            open: @entangle('showEmployeeDropdown').live,
                            searchLength: @entangle('employeeSearch').live.length,
                        }"
                        @click.outside="$wire.closeDropdown()"
                    >
                        <label for="employee_search" class="mb-2 block text-sm font-medium text-gray-700">
                            Employee
                            <span class="text-red-600">*</span>
                        </label>

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
                                    placeholder="Type at least 5 characters to search..."
                                    class="@error('employee_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    autocomplete="off"
                                />

                                {{-- Icons --}}
                                <div class="absolute top-1/2 right-3 flex -translate-y-1/2 items-center gap-2">
                                    {{-- Loading Indicator --}}
                                    <div wire:loading wire:target="employeeSearch" class="text-gray-400">
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
                                    @if ($selectedEmployee)
                                        <button
                                            type="button"
                                            wire:click="clearEmployeeSelection"
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
                                        wire:target="employeeSearch"
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

                                {{-- Character Counter --}}
                                @if (! $selectedEmployee && strlen($employeeSearch) > 0 && strlen($employeeSearch) < 5)
                                    <div class="absolute top-full mt-1 text-xs text-gray-500">
                                        {{ 5 - strlen($employeeSearch) }} more
                                        character{{ 5 - strlen($employeeSearch) > 1 ? 's' : '' }} needed
                                    </div>
                                @endif
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
                                    @if ($this->filteredEmployees->count() > 0)
                                        <ul class="py-1">
                                            @foreach ($this->filteredEmployees as $index => $employee)
                                                <li>
                                                    <button
                                                        type="button"
                                                        wire:click="selectEmployee({{ $employee->id }})"
                                                        class="{{ $highlightedIndex === $index ? 'bg-[#721d21] text-white' : 'text-gray-700 hover:bg-gray-100' }} flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition"
                                                    >
                                                        {{-- Avatar --}}
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
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                                />
                                                            </svg>
                                                        </div>

                                                        {{-- Employee Info --}}
                                                        <div class="min-w-0 flex-1">
                                                            <div class="font-medium">{{ $employee->name }}</div>
                                                            <div
                                                                class="{{ $highlightedIndex === $index ? 'text-white/80' : 'text-gray-500' }} text-xs"
                                                            >
                                                                {{ $employee->employee_number }}
                                                                @if ($employee->position)
                                                                        • {{ $employee->position }}
                                                                @endif
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
                                            <p class="mt-2 text-sm font-medium text-gray-700">No employees found</p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                No results matching "{{ $employeeSearch }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer Hint --}}
                                @if ($this->filteredEmployees->count() > 0)
                                    <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium">{{ $this->filteredEmployees->count() }}</span>
                                            result{{ $this->filteredEmployees->count() > 1 ? 's' : '' }} found • Use ↑
                                            ↓ to navigate, Enter to select
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Search Hint --}}
                        @if (! $selectedEmployee && strlen($employeeSearch) == 0)
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
                                Type at least 5 characters to search for employees
                            </p>
                        @endif

                        {{-- Selected Employee Display --}}
                        @if ($selectedEmployee)
                            <div
                                class="mt-2 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 p-3"
                            >
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
                                    <p class="text-sm font-medium text-green-900">{{ $selectedEmployee->name }}</p>
                                    <p class="text-xs text-green-700">{{ $selectedEmployee->employee_number }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Position Details --}}
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Position Details</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Position Name --}}
                    <div class="md:col-span-2">
                        <label for="position_name" class="mb-2 block text-sm font-medium text-gray-700">
                            Position Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="position_name"
                            wire:model="position_name"
                            class="@error('position_name') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter position name"
                        />
                        @error('position_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Work Unit --}}
                    <div>
                        <label for="work_unit" class="mb-2 block text-sm font-medium text-gray-700">
                            Work Unit
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="work_unit"
                            wire:model="work_unit"
                            class="@error('work_unit') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter work unit/department"
                        />
                        @error('work_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Company Name --}}
                    <div>
                        <label for="company" class="mb-2 block text-sm font-medium text-gray-700">
                            Company Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="company"
                            wire:model="company"
                            class="@error('company') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter company name"
                        />
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Auto-filled from {{ $selectedCompany->name }}</p>
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">
                            Start Date
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            wire:model="start_date"
                            class="@error('start_date') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">End Date</label>
                        <input
                            type="date"
                            id="end_date"
                            wire:model="end_date"
                            class="@error('end_date') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Leave empty if current position</p>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <button
                    type="button"
                    wire:click="cancel"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:opacity-70"
                >
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
                        s
                    </svg>
                    <span wire:loading.remove wire:target="save">Add Position History</span>
                    <span wire:loading wire:target="save">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
