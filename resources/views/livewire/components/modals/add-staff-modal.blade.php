<div
    x-data="{
        show: @entangle('show'),
        init() {
            this.$watch('show', (value) => {
                if (value) {
                    document.body.classList.add('overflow-hidden')
                } else {
                    document.body.classList.remove('overflow-hidden')
                }
            })
        },
        closeModal() {
            @this.close();
        }
    }"
    x-show="show"
    x-cloak
    class="relative z-[10000]"
    style="display: none"
>
    {{-- Background overlay --}}
    <div
        x-show="show"
        x-transition:enter="duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[10000] bg-gray-900/70 backdrop-blur-sm transition-opacity"
        aria-hidden="true"
        @click="closeModal()"
    ></div>

    {{-- Modal panel --}}
    <div class="fixed inset-0 z-[10001] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                x-transition:leave-end="translate-y-4 scale-95 opacity-0"
                class="relative z-[10002] w-full max-w-2xl transform transition-all"
                @click.outside="closeModal()"
                @keydown.escape.window="closeModal()"
            >
                <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start">
                            {{-- Icon --}}
                            <div class="flex-shrink-0">
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100"
                                >
                                    <svg
                                        class="h-6 w-6 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.5v15m7.5-7.5h-15"
                                        />
                                    </svg>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Add Staff to Organization</h3>
                                        @if ($selectedSuperiorName)
                                            <p class="mt-1 text-sm text-gray-600">
                                                Reporting to:
                                                <span class="font-medium">{{ $selectedSuperiorName }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="close()"
                                        class="rounded-lg p-2 text-gray-400 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-600"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <form wire:submit.prevent="save">
                                        <div class="grid grid-cols-1 gap-5">
                                            {{-- Company & Superior info (read-only) --}}
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="text-xs font-medium text-gray-500">Company</p>
                                                    <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                        {{ $company?->name ?? '-' }}
                                                        @if ($company?->cocd)
                                                            <span class="text-xs font-normal text-gray-500">
                                                                ({{ $company->cocd }})
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                                @if ($selectedSuperiorName)
                                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                        <p class="text-xs font-medium text-gray-500">Reports To</p>
                                                        <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                            {{ $selectedSuperiorName }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Hidden fields --}}
                                            <input type="hidden" wire:model="form.superior_id" />
                                            <input type="hidden" wire:model="form.company_id" />

                                            {{-- Position Search --}}
                                            <div
                                                x-data="{ open: @entangle('showPositionDropdown').live }"
                                                @click.outside="$wire.closePositionDropdown()"
                                            >
                                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                                    Staff Position
                                                    <span class="text-red-500">*</span>
                                                </label>

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
                                                        class="@error('form.position_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 pr-20 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                                        autocomplete="off"
                                                    />

                                                    <div
                                                        class="absolute top-5 right-3 flex -translate-y-1/2 items-center gap-2"
                                                    >
                                                        <div
                                                            wire:loading
                                                            wire:target="positionSearch"
                                                            class="text-gray-400"
                                                        >
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
                                                            character{{ 3 - strlen($positionSearch) > 1 ? 's' : '' }}
                                                            needed
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
                                                                                <div class="font-medium">
                                                                                    {{ $pos->name }}
                                                                                </div>
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
                                                                <p class="mt-2 text-sm font-medium text-gray-700">
                                                                    No positions found
                                                                </p>
                                                                <p class="mt-1 text-xs text-gray-500">
                                                                    No results matching "{{ $positionSearch }}"
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if ($this->filteredPositions->count() > 0)
                                                        <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium">
                                                                    {{ $this->filteredPositions->count() }}
                                                                </span>
                                                                result{{ $this->filteredPositions->count() > 1 ? 's' : '' }}
                                                                found • Use ↑ ↓ to navigate, Enter to select
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>

                                                @error('form.position_id')
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
                                                            <p class="text-sm font-medium text-green-900">
                                                                {{ $selectedPosition->name }}
                                                            </p>
                                                            <p class="text-xs text-green-700">
                                                                {{ $selectedPosition->position_code }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Relation Type --}}
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                                    Relation Type
                                                    <span class="text-red-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <select
                                                        wire:model="form.relation_type"
                                                        class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 text-sm shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                    >
                                                        <option value="direct">Direct Report</option>
                                                        <option value="indirect">Indirect Report</option>
                                                        <option value="dotted_line">Dotted Line</option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700"
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
                                                                d="M19 9l-7 7-7-7"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                                @error('form.relation_type')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-5">
                                            <button
                                                type="button"
                                                wire:click="close()"
                                                wire:loading.attr="disabled"
                                                class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                wire:loading.attr="disabled"
                                                @if(!$form['position_id']) disabled @endif
                                                class="w-fit transform rounded-xl bg-gradient-to-r from-[#721d21] to-[#8b252a] px-6 py-2.5 font-medium text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-[#5a1619] hover:to-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none active:scale-[0.98] disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                <span wire:loading.remove class="inline-flex items-center gap-2">
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
                                                            d="M12 4.5v15m7.5-7.5h-15"
                                                        />
                                                    </svg>
                                                    Add Staff
                                                </span>
                                                <span wire:loading class="inline-flex items-center gap-2">
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
