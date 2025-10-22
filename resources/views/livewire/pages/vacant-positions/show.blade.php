<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('vacant-positions.index') }}">Vacant Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Candidates Match</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="relative h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="mt-2 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100">
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
                    <p class="font-semibold text-gray-600 md:mt-1">Candidates match</p>
                    <h6 class="text-2xl font-bold text-gray-900 md:text-3xl">Senior Manager Operations</h6>
                    <p class="text-sm text-gray-600 md:mt-1">{{ count($candidates) }} candidates available</p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-end gap-1 md:flex-row md:gap-2">
                <span class="block text-sm text-gray-600 md:inline">Selected:</span>
                <span class="block rounded-full bg-[#721d21] px-3 py-1 text-sm font-semibold text-white md:inline">
                    {{ count($selectedCandidates) }} / 5
                </span>
            </div>
        </div>

        <div id="candidate-match" class="grid grid-cols-1 gap-6 pb-24 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($candidates as $index => $candidate)
                @php
                    $isSelected = in_array($candidate['id'], $selectedCandidates);
                    $isDisabled = count($selectedCandidates) >= 5 && ! $isSelected;
                @endphp

                <label
                    class="group {{ $isDisabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:shadow-lg' }} {{ $isSelected ? 'ring-opacity-20 border-[#721d21] bg-[#721d210e] shadow-lg ring-1 ring-[#721d21]' : 'border-gray-200 bg-white hover:border-gray-300' }} h-full w-full rounded-xl border-2 p-4 transition-all duration-300 ease-in-out"
                >
                    <div class="flex gap-4">
                        <div class="flex w-full gap-4">
                            <div class="relative">
                                <img
                                    src="{{ $candidate['avatar'] }}"
                                    class="h-20 w-20 rounded-lg bg-gray-200 object-cover transition-transform duration-300"
                                    alt="{{ $candidate['name'] }}"
                                />
                                <div
                                    class="absolute -top-2 -left-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#721d21] shadow-md"
                                >
                                    <p class="text-sm font-semibold text-white">{{ $index + 1 }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $candidate['name'] }}</h2>
                                    <p class="text-sm text-gray-600">{{ $candidate['department'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $candidate['division'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $candidate['band'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Checkbox -->
                        <div class="relative flex items-start pt-1">
                            <input
                                type="checkbox"
                                wire:model.live="selectedCandidates"
                                value="{{ $candidate['id'] }}"
                                class="sr-only"
                                id="{{ $candidate['id'] }}"
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
                    <div class="mt-4 flex items-end justify-between border-t border-gray-200 pt-3">
                        <a
                            href="#"
                            class="{{ $isDisabled ? 'pointer-events-none' : '' }} text-sm text-[#721d21] transition-colors hover:text-[#5a1719] hover:underline"
                            onclick="event.preventDefault(); event.stopPropagation();"
                        >
                            View Profile
                        </a>
                        <div class="text-right">
                            <p class="font-semibold text-[#721d21]">{{ $candidate['match'] }}% Match</p>
                            <p class="text-xs text-gray-500">Candidate Comparison</p>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <!-- Action Buttons Fixed at Bottom with Smooth Animation -->
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
                        wire:target="compareSelected"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-md transition-all hover:bg-gray-50 hover:shadow-lg active:scale-95 disabled:opacity-50"
                    >
                        Clear Selection
                    </button>
                    <button
                        type="button"
                        wire:click="compareSelected"
                        wire:loading.attr="disabled"
                        wire:target="compareSelected"
                        class="flex items-center justify-center gap-2 rounded-lg bg-[#721d21] px-8 py-3 text-sm font-medium text-white shadow-md transition-all hover:bg-[#5a1719] hover:shadow-lg active:scale-95 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <!-- Loading Spinner -->
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

                        <!-- Text  -->
                        <span wire:loading.remove wire:target="compareSelected">
                            Compare {{ count($selectedCandidates) }} Selected
                            Candidate{{ count($selectedCandidates) > 1 ? 's' : '' }}
                        </span>

                        <span wire:loading wire:target="compareSelected">Comparing...</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
