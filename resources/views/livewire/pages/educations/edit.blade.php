<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('educations.index') }}">Education</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Education</flux:breadcrumbs.item>
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
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Education Record</h1>
                    <p class="mt-1 text-sm text-gray-500">Update education history for employee</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="update" class="space-y-6">
            <!-- Employee Selection -->
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Employee Information</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Company Selection -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700"
                        >
                            {{ $employee->company->name }} ({{ $employee->company->cocd }})
                        </div>
                    </div>

                    <!-- Employee Selection -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700"
                        >
                            {{ $employee->name }} ({{ $employee->employee_number }})
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Details -->
            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Education Details</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Institution Name -->
                    <div class="md:col-span-2">
                        <label for="institution_name" class="mb-2 block text-sm font-medium text-gray-700">
                            Institution Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="institution_name"
                            wire:model="institution_name"
                            class="@error('institution_name') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter institution/university name"
                        />
                        @error('institution_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Major -->
                    <div class="md:col-span-2">
                        <label for="major" class="mb-2 block text-sm font-medium text-gray-700">
                            Major / Field of Study
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="major"
                            wire:model="major"
                            class="@error('major') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            placeholder="Enter major or field of study"
                        />
                        @error('major')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Degree -->
                    <div>
                        <label for="degree" class="mb-2 block text-sm font-medium text-gray-700">
                            Degree Level
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="degree"
                                wire:model="degree"
                                class="@error('degree') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            >
                                <option value="">Select Degree</option>
                                <option value="SMA/K" {{ $degree == 'SMA/K' ? 'selected' : '' }}>SMA/K</option>
                                <option value="D3" {{ $degree == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ $degree == 'D4' ? 'selected' : '' }}>D4</option>
                                <option value="S1" {{ $degree == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ $degree == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ $degree == 'S3' ? 'selected' : '' }}>S3</option>
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
                        @error('degree')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">
                            Start Date
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            wire:model="start_date"
                            max="{{ date('Y-m-d') }}"
                            class="@error('start_date') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">End Date</label>
                        <input
                            type="date"
                            id="end_date"
                            wire:model="end_date"
                            min="{{ $start_date ? date('Y-m-d', strtotime($start_date)) : '' }}"
                            max="{{ date('Y-m-d') }}"
                            class="@error('end_date') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none disabled:bg-gray-100 disabled:text-gray-500"
                        />
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <a
                    href="{{ route('educations.index') }}"
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
                    <span wire:loading.remove wire:target="update">Update Education</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
