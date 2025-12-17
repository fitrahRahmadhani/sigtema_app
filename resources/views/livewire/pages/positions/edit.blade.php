<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('positions.index') }}">Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Position</flux:breadcrumbs.item>
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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Position</h1>
                    <p class="mt-1 text-sm text-gray-500">Update position details and organizational information</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="save" class="space-y-8">
            <!-- Position Information -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Position Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Position Name -->
                    <div class="md:col-span-2">
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

                    <!-- Job Grade -->
                    <div>
                        <label for="job_grade" class="mb-2 block text-sm font-medium text-gray-700">
                            Job Grade
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="job_grade"
                            wire:model.blur="job_grade"
                            placeholder="e.g., 11, 12, 13, 14, 15"
                            class="@error('job_grade') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />

                        @error('job_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Job grade classification</p>
                    </div>
                </div>
            </div>

            <!-- Company Assignment -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Company Assignment</h3>
                <div class="mb-6 grid gap-6">
                    @hasrole('superadmin')
                        <!-- Company Selection -->
                        <div class="md:col-span-2">
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
                    @endhasrole
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Directorate -->
                    <div>
                        <label for="directorate" class="mb-2 block text-sm font-medium text-gray-700">
                            Directorate
                        </label>
                        <input
                            type="text"
                            id="directorate"
                            wire:model="directorate"
                            placeholder="e.g., Technology Directorate, Finance Directorate"
                            class="@error('directorate') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('directorate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="mb-2 block text-sm font-medium text-gray-700">Department</label>
                        <input
                            type="text"
                            id="department"
                            wire:model="department"
                            placeholder="e.g., IT Department, HR Department"
                            class="@error('department') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Biro -->
                    <div>
                        <label for="biro" class="mb-2 block text-sm font-medium text-gray-700">Biro</label>
                        <input
                            type="text"
                            id="biro"
                            wire:model="biro"
                            placeholder="e.g., Finance Biro, Operations Biro"
                            class="@error('biro') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('biro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section" class="mb-2 block text-sm font-medium text-gray-700">Section</label>
                        <input
                            type="text"
                            id="section"
                            wire:model="section"
                            placeholder="e.g., Development Section, Recruitment Section"
                            class="@error('section') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('section')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="mb-2 block text-sm font-medium text-gray-700">Location</label>
                        <input
                            type="text"
                            id="location"
                            wire:model="location"
                            placeholder="e.g., Jakarta Head Office, Bandung Branch"
                            class="@error('location') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Employee Assignment -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Employee Assignment</h3>
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Optional</span>
                </div>
                <div class="mb-6">
                    <!-- Employee Selection -->
                    <div class="w-full">
                        <label for="employee_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Employee
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="employee_id"
                                wire:model.live="employee_id"
                                class="@error('employee_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                @if (!$company_id) disabled @endif
                            >
                                <option value="">
                                    {{ $company_id ? 'Select employee' : 'Select company first' }}
                                </option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
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
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
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
                    <span wire:loading.remove wire:target="save">Update Position</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
