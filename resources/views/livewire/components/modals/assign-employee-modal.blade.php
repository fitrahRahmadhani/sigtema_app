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
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100"
                                >
                                    <svg
                                        class="h-6 w-6 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900">Assign Employee</h3>
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
                                    <!-- Position Info -->
                                    <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <div class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 flex-shrink-0 text-gray-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">Position</h4>
                                                <p class="text-sm text-gray-600">{{ $positionName }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Employee Selection -->
                                    <div class="mb-6">
                                        <label
                                            for="employeeSelect"
                                            class="mb-2 block text-sm font-medium text-gray-700"
                                        >
                                            Select Employee
                                        </label>
                                        <div class="relative">
                                            <select
                                                wire:model="selectedEmployeeId"
                                                id="employeeSelect"
                                                class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 text-sm shadow-sm transition-all duration-200 hover:border-gray-400 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                            >
                                                <option value="">Choose an employee...</option>
                                                @foreach ($availableEmployees as $employee)
                                                    <option value="{{ $employee['id'] }}">
                                                        {{ $employee['name'] }} ({{ $employee['employee_number'] }}) -
                                                        {{ $employee['email'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
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

                                        @if (count($availableEmployees) === 0)
                                            <div class="mt-2 rounded-lg border border-yellow-200 bg-yellow-50 p-3">
                                                <div class="flex">
                                                    <svg
                                                        class="h-5 w-5 flex-shrink-0 text-yellow-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"
                                                        />
                                                    </svg>
                                                    <div class="ml-3">
                                                        <p class="text-sm text-yellow-700">
                                                            No available employees found. All employees may already be
                                                            assigned to positions.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex justify-end gap-3 border-t border-gray-200 pt-6">
                                        <button
                                            type="button"
                                            wire:click="close()"
                                            class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="assign()"
                                            wire:loading.attr="disabled"
                                            :disabled="!selectedEmployeeId"
                                            class="rounded-xl bg-blue-600 px-6 py-2.5 font-medium text-white shadow-sm transition-all duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                                    />
                                                </svg>
                                                Assign Employee
                                            </span>
                                            <span wire:loading class="inline-flex items-center gap-2">
                                                Assigning...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
