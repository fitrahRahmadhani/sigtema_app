<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('technical-competencies.index') }}">
            Technical Competencies
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Technical Competency</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
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
                            d="M9 12.75L11.25 15 15 9.75m-3.713-4.013a4.5 4.5 0 011.482-.943 4.5 4.5 0 013.74 0 4.5 4.5 0 011.482.943m-7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Technical Competency</h1>
                    <p class="mt-1 text-sm text-gray-500">Update technical competency or certification</p>
                    @if ($competency->employee)
                        <p class="mt-2 text-sm font-medium text-gray-700">
                            Employee:
                            <span class="font-semibold text-[#721d21]">
                                {{ $competency->employee->name }} ({{ $competency->employee->employee_number }})
                            </span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Employee Info (Read-only) -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
            <h2 class="text-lg font-semibold text-gray-900">Employee Information</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <!-- Company (Read-only) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                    <div class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700">
                        @if ($competency->employee && $competency->employee->company)
                            {{ $competency->employee->company->name }} ({{ $competency->employee->company->cocd }})
                        @else
                            <span class="text-gray-400">No company assigned</span>
                        @endif
                    </div>
                </div>

                <!-- Employee (Read-only) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Employee</label>
                    <div class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700">
                        @if ($competency->employee)
                            {{ $competency->employee->name }} ({{ $competency->employee->employee_number }})
                        @else
                            <span class="text-gray-400">No employee assigned</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="update" class="space-y-6">
            <!-- Competency Details -->
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Competency Details</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Competency Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Competency/Certification Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="@error('name') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter competency or certification name"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Issued By -->
                    <div class="md:col-span-2">
                        <label for="issued_by" class="mb-2 block text-sm font-medium text-gray-700">
                            Issued By
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="issued_by"
                            wire:model="issued_by"
                            class="@error('issued_by') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter issuing organization"
                        />
                        @error('issued_by')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
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
                            class="@error('year') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <a
                    href="{{ route('technical-competencies.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
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
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                        />
                    </svg>
                    <span wire:loading.remove wire:target="update">Update Competency</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
