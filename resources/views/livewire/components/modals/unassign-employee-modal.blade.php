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
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100"
                                >
                                    <svg
                                        class="h-6 w-6 text-yellow-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900">Unassign Employee</h3>
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
                                    <!-- Warning Message -->
                                    <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg
                                                    class="h-5 w-5 text-yellow-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Attention Required</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>
                                                        You are about to unassign an employee from their position. This
                                                        action cannot be undone.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Employee Details -->
                                    <div class="mb-4 rounded-lg bg-gray-50 p-4">
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Position</label>
                                                <p class="text-sm font-semibold text-gray-900">{{ $positionName }}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Employee</label>
                                                <p class="text-sm font-semibold text-gray-900">{{ $employeeName }}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Department/Unit</label>
                                                <p class="text-sm text-gray-900">{{ $employeeDetails }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Confirmation Text -->
                                    <div class="mb-6 text-sm text-gray-600">
                                        <p>
                                            This will remove
                                            <strong>{{ $employeeName }}</strong>
                                            from the
                                            <strong>{{ $positionName }}</strong>
                                            position. The position will become vacant and available for new assignment.
                                        </p>
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
                                            wire:click="unassign()"
                                            wire:loading.attr="disabled"
                                            class="rounded-xl bg-yellow-600 px-6 py-2.5 font-medium text-white shadow-sm transition-all duration-200 hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                                                        d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"
                                                    />
                                                </svg>
                                                Unassign Employee
                                            </span>
                                            <span wire:loading class="inline-flex items-center gap-2">
                                                Processing...
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
