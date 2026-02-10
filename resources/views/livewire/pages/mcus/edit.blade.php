<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('mcus.index') }}">Medical Check Up</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit MCU Record</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8">
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
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit MCU Record</h1>
                    <p class="mt-1 text-sm text-gray-500">Update medical check up record</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="update" class="space-y-6">
            {{-- Employee Information --}}
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Employee Information</h2>
                <div class="space-y-4">
                    {{-- Company --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                        >
                            <p>{{ $selectedCompany->name }}</p>
                        </div>
                    </div>

                    {{-- Employee --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Employee</label>
                        <div
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                        >
                            <p>{{ $mcu->employee->name }} ({{ $mcu->employee->employee_number }})</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MCU Details --}}
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">MCU Details</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Year --}}
                    <div>
                        <label for="year" class="mb-2 block text-sm font-medium text-gray-700">
                            Year
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="number"
                            id="year"
                            wire:model="year"
                            min="2000"
                            max="{{ date('Y') + 1 }}"
                            class="@error('year') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter year (e.g., 2024)"
                        />
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Result --}}
                    <div>
                        <label for="result" class="mb-2 block text-sm font-medium text-gray-700">
                            MCU Result
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="result"
                                wire:model="result"
                                class="@error('result') border-red-500 @else border-gray-300 @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            >
                                <option value="">Select Result</option>
                                <option
                                    value="1"
                                    {{ $result == '1' ? 'selected' : '' }}
                                    class="text-red-700"
                                >
                                    1 (Worst)
                                </option>
                                <option
                                    value="2"
                                    {{ $result == '2' ? 'selected' : '' }}
                                    class="text-orange-700"
                                >
                                    2
                                </option>
                                <option
                                    value="3"
                                    {{ $result == '3' ? 'selected' : '' }}
                                    class="text-yellow-700"
                                >
                                    3
                                </option>
                                <option
                                    value="4"
                                    {{ $result == '4' ? 'selected' : '' }}
                                    class="text-blue-700"
                                >
                                    4
                                </option>
                                <option
                                    value="5"
                                    {{ $result == '5' ? 'selected' : '' }}
                                    class="text-green-700"
                                >
                                    5 (Best)
                                </option>
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
                        @error('result')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                    wire:target="update"
                    class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:opacity-70"
                >
                    <svg
                        wire:loading
                        wire:target="update"
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
                        wire:target="update"
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
                    <span wire:loading.remove wire:target="update">Update MCU Record</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
