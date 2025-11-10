<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('positions.index') }}">Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Position</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create New Position</h1>
                    <p class="mt-1 text-sm text-gray-500">Add a new position with job grade and band</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="save" class="space-y-8">
            <!-- Position Information -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Position Information</h3>
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Position Name -->
                    <div class="md:col-span-3">
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Position Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model.live="name"
                            placeholder="e.g., Senior Manager, Software Engineer, HR Specialist"
                            class="@error('name') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position Code -->
                    <div>
                        <label for="position_code" class="mb-2 block text-sm font-medium text-gray-700">
                            Position Code
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="position_code"
                            wire:model.blur="position_code"
                            placeholder="e.g., 50057460, 50059476, 50059654"
                            class="@error('position_code') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('position_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Band -->
                    <div>
                        <label for="band" class="mb-2 block text-sm font-medium text-gray-700">
                            Band
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="band"
                                wire:model="band"
                                class="@error('band') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            >
                                <option value="">Select a band</option>
                                <option value="Band 1">Band 1</option>
                                <option value="Band 2">Band 2</option>
                                <option value="Band 3">Band 3</option>
                                <option value="Band 4">Band 4</option>
                                <option value="Band 5">Band 5</option>
                            </select>

                            <!-- Dropdown Icon -->
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

                        @error('band')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Career band level for this position</p>
                    </div>

                    <!-- Job Grade -->
                    <div>
                        <label for="job_grade" class="mb-2 block text-sm font-medium text-gray-700">
                            Job Grade
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="number"
                            id="job_grade"
                            wire:model.blur="job_grade"
                            placeholder="e.g., 11, 12, 13, 14, 15"
                            min="1"
                            class="@error('job_grade') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />

                        @error('job_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Job grade classification</p>
                    </div>
                </div>
            </div>

            <!-- Organization Assignment -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Organization Assignment</h3>
                <div class="space-y-6">
                    <!-- Company Selection -->
                    <div>
                        <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Company
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="company_id"
                                wire:model.live="company_id"
                                class="@error('company_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            >
                                <option value="">Select a company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }} ({{ $company->cocd }})
                                    </option>
                                @endforeach
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
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Work Unit Selection -->
                    <div>
                        <label for="work_unit_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Work Unit
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="work_unit_id"
                                wire:model="work_unit_id"
                                @if (!$company_id) disabled @endif
                                class="@error('work_unit_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
                            >
                                <option value="">
                                    {{ $company_id ? 'Select a work unit' : 'Select company first' }}
                                </option>
                                @foreach ($workUnits as $workUnit)
                                    <option value="{{ $workUnit->id }}">
                                        {{ $workUnit->name }}
                                        @if ($workUnit->departemen)
                                            ({{ $workUnit->departemen->name }})
                                        @endif
                                    </option>
                                @endforeach
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
                        @error('work_unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Select the work unit where this position is located</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    wire:click="cancel"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-gray-300 transition ring-inset hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                >
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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                        />
                    </svg>
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
                    <span wire:loading.remove wire:target="save">Create Position</span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
