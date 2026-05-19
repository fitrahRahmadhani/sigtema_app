<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('innovations.index') }}">Innovations</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Innovation</flux:breadcrumbs.item>
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
                        d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Innovation</h1>
                <p class="mt-1 text-sm text-gray-500">Update innovation information</p>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Innovation Details --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Innovation Details</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
                            Innovation Title
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            wire:model.live="title"
                            placeholder="Enter innovation title"
                            class="@error('title') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Employee Search --}}
                    <div class="md:col-span-2">
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

                    {{-- Team (Select or Input) --}}
                    <div>
                        <label for="team" class="mb-2 block text-sm font-medium text-gray-700">Team</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                @if ($teamInputMode === 'input')
                                    <input type="text" id="team" wire:model.live="team" placeholder="Enter team name" class="@error('team') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                                @else
                                    <select id="team" wire:model.live="team" class="@error('team') border-red-500 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none">
                                        <option value="">Select a team</option>
                                        @foreach ($teams as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </div>
                            <button type="button" wire:click="toggleTeamInputMode" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                @if ($teamInputMode === 'select')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.007 5.25h.007v.008H3.75v-.008zm0 5.25h.007v.008H3.75v-.008z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>
                        @error('team')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category (Select or Input) --}}
                    <div>
                        <label for="category" class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                @if ($categoryInputMode === 'input')
                                    <input type="text" id="category" wire:model.live="category" placeholder="Enter category" class="@error('category') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                                @else
                                    <select id="category" wire:model.live="category" class="@error('category') border-red-500 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none">
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </div>
                            <button type="button" wire:click="toggleCategoryInputMode" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                @if ($categoryInputMode === 'select')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.007 5.25h.007v.008H3.75v-.008zm0 5.25h.007v.008H3.75v-.008z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Theme --}}
                    <div>
                        <label for="theme" class="mb-2 block text-sm font-medium text-gray-700">Theme</label>
                        <input type="text" id="theme" wire:model.live="theme" placeholder="Enter innovation theme" class="@error('theme') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('theme')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event --}}
                    <div>
                        <label for="event" class="mb-2 block text-sm font-medium text-gray-700">Event</label>
                        <input type="text" id="event" wire:model.live="event" placeholder="Enter event name" class="@error('event') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('event')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Status Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Status Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Replication --}}
                    <div>
                        <label for="replication" class="mb-2 block text-sm font-medium text-gray-700">Replication</label>
                        <div class="relative">
                            <select id="replication" wire:model.live="replication" class="@error('replication') border-red-500 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none">
                                <option value="">Select replication status</option>
                                <option value="Bisa Direplikasi">Bisa Direplikasi</option>
                                <option value="Tidak Bisa Direplikasi">Tidak Bisa Direplikasi</option>
                            </select>
                            <svg class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @error('replication')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                        <input type="text" id="status" wire:model.live="status" placeholder="e.g., approved, pending, submitted" class="@error('status') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Achievement --}}
                    <div class="md:col-span-2">
                        <label for="achievement" class="mb-2 block text-sm font-medium text-gray-700">Achievement</label>
                        <textarea id="achievement" wire:model.live="achievement" rows="3" placeholder="Enter innovation achievement (if any)" class="@error('achievement') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"></textarea>
                        @error('achievement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Active Status (Checkbox) --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Active Status</label>
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="hidden" value="0" wire:model.live="is_active">
                            <input type="checkbox" wire:model.live="is_active" value="1" class="h-5 w-5 cursor-pointer rounded border-gray-300 text-green-600 focus:ring-green-500"/>
                            <span class="text-sm text-gray-600">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                    </svg>
                    <svg wire:loading wire:target="save" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Update Innovation</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>