<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Positions</flux:breadcrumbs.item>
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
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Positions</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage organizational positions and job grades</p>
                </div>
            </div>

            <!-- Add Position Button -->
            <a
                href="{{ route('positions.create') }}"
                class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Position
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Search Row -->
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search Position</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
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
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, code, job grade, company, or organizational unit..."
                            class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Sort By -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="code_asc">Code (A-Z)</option>
                            <option value="code_desc">Code (Z-A)</option>
                            <option value="grade_asc">Grade (Low-High)</option>
                            <option value="grade_desc">Grade (High-Low)</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
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
                </div>
            </div>

            <!-- Filters Row -->
            <div class="grid gap-4 md:grid-cols-4">
                <!-- Company Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                    <div class="relative">
                        <select
                            wire:model.live="companyFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Companies</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
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
                </div>

                <!-- Job Grade Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Job Grade</label>
                    <div class="relative">
                        <select
                            wire:model.live="gradeFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Grades</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade }}">{{ $grade }}</option>
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
                </div>

                <!-- Department Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Department</label>
                    <div class="relative">
                        <select
                            wire:model.live="departmentFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
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
                </div>

                <!-- Location Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Location</label>
                    <div class="relative">
                        <select
                            wire:model.live="locationFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
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
                </div>
            </div>

            <!-- Results Info -->
            <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                Showing
                <span class="font-semibold text-gray-900">{{ $positions->count() }}</span>
                of
                <span class="font-semibold text-gray-900">{{ $positions->total() }}</span>
                positions
            </div>
        </div>

        <!-- Positions List Container -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Hidden on mobile -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Position</div>
                <div class="col-span-2 text-sm font-semibold">Employee on Duty</div>
                <div class="col-span-2 text-sm font-semibold">Organizational Unit</div>
                <div class="col-span-2 text-sm font-semibold">Company</div>
                <div class="col-span-1 text-sm font-semibold">Location</div>
                <div class="col-span-1 text-sm font-semibold">Job Grade</div>
                <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
            </div>

            <!-- List Items -->
            @forelse ($positions as $position)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    <!-- Position Name & Code -->
                    <div class="col-span-3 flex items-center gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-900">{{ $position->name }}</p>
                            <span
                                class="mt-1 inline-flex items-center rounded-md bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800"
                            >
                                {{ $position->position_code }}
                            </span>
                        </div>
                    </div>

                    <!-- Employee Unit -->
                    <div class="col-span-2 overflow-hidden">
                        @if ($position->employee_id)
                            <p class="truncate text-sm font-medium text-gray-900">{{ $position->employee->name }}</p>
                            @if ($position->employee->employee_number)
                                <p class="truncate text-xs text-gray-400">
                                    {{ $position->employee->employee_number }}
                                </p>
                            @endif
                        @else
                            <p
                                class="mt-1 w-fit rounded-full bg-red-50 px-2 py-1 text-sm font-medium text-red-500 italic"
                            >
                                Vacant
                                <span class="hidden lg:inline">Position</span>
                            </p>
                        @endif
                    </div>

                    <!-- Organizational Unit -->
                    <div class="col-span-2 overflow-hidden">
                        @if ($position->department)
                            <p class="truncate text-sm font-medium text-gray-900">{{ $position->department }}</p>
                            @if ($position->directorate)
                                <p class="truncate text-xs text-gray-500">{{ $position->directorate }}</p>
                            @endif

                            @if ($position->section)
                                <p class="truncate text-xs text-gray-400">{{ $position->section }}</p>
                            @endif
                        @else
                            <span class="text-sm text-gray-400 italic">No department</span>
                        @endif
                    </div>

                    <!-- Company -->
                    <div class="col-span-2 overflow-hidden">
                        @if ($position->company)
                            <p class="truncate text-sm text-gray-600">{{ $position->company->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $position->company->cocd }}</p>
                        @else
                            <span class="text-sm text-gray-400 italic">No company</span>
                        @endif
                    </div>

                    <!-- Location -->
                    <div class="col-span-1">
                        @if ($position->location)
                            <span
                                class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                            >
                                {{ $position->location }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400 italic">No location</span>
                        @endif
                    </div>

                    <!-- Job Grade -->
                    <div class="col-span-1">
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800"
                        >
                            {{ $position->job_grade }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1 flex items-center justify-center gap-2">
                        <button
                            wire:click="edit({{ $position->id }})"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#721d21]"
                            title="Edit"
                        >
                            <svg
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
                        </button>
                        <button
                            wire:click="confirmDelete({{ $position->id }}, '{{ $position->name }}')"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-red-50 hover:text-red-600"
                            title="Delete"
                        >
                            <svg
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
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile View -->
                <div
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-4">
                        <!-- Position Header -->
                        <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase">Position</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $position->name }}</p>
                                <span
                                    class="mt-2 inline-flex items-center rounded-md bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800"
                                >
                                    {{ $position->position_code }}
                                </span>
                            </div>
                        </div>

                        <!-- Employee -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Employee on Duty</p>
                            @if ($position->employee_id)
                                <p class="mt-1 text-sm font-medium text-gray-900">{{ $position->employee->name }}</p>
                                @if ($position->employee->employee_number)
                                    <p class="text-xs text-gray-500">{{ $position->employee->employee_number }}</p>
                                @endif
                            @else
                                <p
                                    class="mt-1 w-fit rounded-full bg-red-50 px-2 py-1 text-sm font-medium text-red-500 italic"
                                >
                                    Vacant Position
                                </p>
                            @endif
                        </div>

                        <!-- Organizational Unit & Company -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Organizational Unit</p>
                                @if ($position->department)
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $position->department }}</p>
                                    @if ($position->directorate)
                                        <p class="text-xs text-gray-500">{{ $position->directorate }}</p>
                                    @endif
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">No department</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Company</p>

                                @if ($position->company)
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $position->company->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $position->company->cocd }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">No company</p>
                                @endif
                            </div>
                        </div>

                        <!-- Location & Grade -->
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Location</p>
                                @if ($position->location)
                                    <span
                                        class="mt-1 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                                    >
                                        {{ $position->location }}
                                    </span>
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">No location</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Job Grade</p>
                                <span
                                    class="mt-1 inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800"
                                >
                                    {{ $position->job_grade }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">
                            <button
                                wire:click="edit({{ $position->id }})"
                                class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#721d21]"
                                title="Edit"
                            >
                                <svg
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
                            </button>
                            <button
                                wire:click="confirmDelete({{ $position->id }}, '{{ $position->name }}')"
                                class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-red-50 hover:text-red-600"
                                title="Delete"
                            >
                                <svg
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
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="mx-auto h-12 w-12 text-gray-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No positions found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your search or add a new position</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($positions->hasPages())
            <div class="mt-6">
                {{ $positions->links() }}
            </div>
        @endif
    </div>
    <x-ui.modals.delete-confirmation />
</div>
