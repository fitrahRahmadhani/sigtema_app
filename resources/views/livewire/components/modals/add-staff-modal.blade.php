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
    <!-- Background overlay -->
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

    <!-- Modal panel -->
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
                            <!-- Icon -->
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

                            <!-- Content -->
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
                                            <!-- Company -->
                                            <div>
                                                <label
                                                    for="company_id"
                                                    class="mb-2 block text-sm font-medium text-gray-700"
                                                >
                                                    Company
                                                </label>
                                                <div class="relative">
                                                    <select
                                                        wire:model.live="form.company_id"
                                                        id="company_id"
                                                        class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                    >
                                                        <option value="">Select Company</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}">
                                                                {{ $company->name }}
                                                            </option>
                                                        @endforeach
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
                                                @error('form.company_id')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Superior Position - HIDDEN & DISPLAY ONLY -->
                                            <input type="hidden" wire:model="form.superior_id" />

                                            @if ($selectedSuperiorName)
                                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
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
                                                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <h3 class="text-sm font-medium text-gray-800">
                                                                Reporting Structure
                                                            </h3>
                                                            <div class="mt-1 text-sm text-gray-700">
                                                                <p>
                                                                    This staff will report to:
                                                                    <strong>{{ $selectedSuperiorName }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Position -->
                                            <div>
                                                <label
                                                    for="position_id"
                                                    class="mb-2 block text-sm font-medium text-gray-700"
                                                >
                                                    Staff Position
                                                </label>
                                                <div class="relative">
                                                    <select
                                                        wire:model="form.position_id"
                                                        id="position_id"
                                                        class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                    >
                                                        <option value="">Select Position</option>
                                                        @foreach ($positions as $position)
                                                            <option value="{{ $position->id }}">
                                                                {{ $position->name }} -
                                                                {{ $position->getWorkUnitAttribute() ?? 'No Department' }}
                                                            </option>
                                                        @endforeach
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
                                                @error('form.position_id')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Relation Type -->
                                            <div>
                                                <label
                                                    for="relation_type"
                                                    class="mb-2 block text-sm font-medium text-gray-700"
                                                >
                                                    Relation Type
                                                </label>
                                                <div class="relative">
                                                    <select
                                                        wire:model="form.relation_type"
                                                        id="relation_type"
                                                        class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
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

                                        <!-- Action Buttons -->
                                        <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6">
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
