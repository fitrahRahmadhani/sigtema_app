<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Education</flux:breadcrumbs.item>
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
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Educations</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage employee education history</p>

                    <!-- Company Info with Change Button -->
                    @if ($isSuperadmin && $selectedCompany)
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                            <button
                                wire:click="changeCompany"
                                class="flex flex-shrink-0 items-center gap-1 rounded-lg bg-red-50 px-2 py-1 text-xs text-red-500 transition-colors hover:bg-red-100 hover:text-red-700"
                            >
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>
                                <span class="hidden sm:inline">Change</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Add Education Button -->
            @if (! $showCompanySelection)
                <a
                    href="{{ route('educations.create') }}"
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
                    Add Education
                </a>
            @endif
        </div>

        <!-- COMPANY SELECTION SECTION -->
        @if ($showCompanySelection)
            <div class="mb-8 rounded-lg border border-gray-200 bg-white p-8 text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="mx-auto h-16 w-16 text-gray-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                    />
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Pilih Perusahaan</h3>
                <p class="mt-2 text-gray-500">Silakan pilih perusahaan untuk mengelola data pendidikan</p>

                <!-- Company List -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <button
                            wire:click="selectCompany({{ $company->id }})"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($company->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $company->logo_path) }}"
                                            alt="{{ 'Logo ' . $company->name }}"
                                        />
                                    @else
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6 text-gray-600"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                                            />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $company->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            \App\Models\Education::whereHas('employee', function ($query) use ($company) {
                                                $query->where('company_id', $company->id);
                                            })->count()
                                        }}
                                        data pendidikan
                                    </p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- MAIN CONTENT SECTION -->
        @if (! $showCompanySelection)
            @if ($selectedCompany)
                <!-- Search and Filter Section -->
                <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-600">
                        Mengelola data pendidikan karyawan di
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>

                    <!-- Search Row -->
                    <div class="grid gap-4">
                        <!-- Search Input -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Search Employee</label>
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
                                    placeholder="Search by employee name or employee number..."
                                    class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Filters Row -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <!-- Institution Filter -->
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Institution</label>
                            <div class="relative">
                                <select
                                    wire:model.live="institutionFilter"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="">All Institutions</option>
                                    @foreach ($institutions as $institution)
                                        <option value="{{ $institution }}">{{ $institution }}</option>
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

                        <!-- Degree Filter -->
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Degree</label>
                            <div class="relative">
                                <select
                                    wire:model.live="degreeFilter"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="">All Degrees</option>
                                    @foreach ($degrees as $degree)
                                        <option value="{{ $degree }}">{{ $degree }}</option>
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

                        <!-- Major Filter -->
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Major</label>
                            <div class="relative">
                                <select
                                    wire:model.live="majorFilter"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="">All Majors</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major }}">{{ $major }}</option>
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

                    <!-- Results Count -->
                    <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                        Showing
                        <span class="font-semibold text-gray-900">{{ $education->count() }}</span>
                        of
                        <span class="font-semibold text-gray-900">{{ $education->total() }}</span>
                        education records
                    </div>
                </div>

                <!-- Education List Container -->
                <div class="space-y-3 overflow-x-auto">
                    <!-- Column Headers - Hidden on mobile -->
                    <div
                        class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
                    >
                        <div class="col-span-4 text-sm font-semibold">Employee</div>
                        <div class="col-span-4 text-sm font-semibold">Education</div>
                        <div class="col-span-2 text-sm font-semibold">Period</div>
                        <div class="col-span-2 text-center text-sm font-semibold">Actions</div>
                    </div>

                    <!-- List Items -->
                    @forelse ($education as $edu)
                        <!-- Desktop View -->
                        <div
                            class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                        >
                            <!-- Employee Info -->
                            <div class="col-span-4 flex gap-2 overflow-hidden">
                                <div class="flex flex-col justify-center">
                                    @if ($edu->employee)
                                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                            {{ $edu->employee->name }}
                                        </p>
                                        <span
                                            class="mt-1 inline-flex w-fit items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                        >
                                            {{ $edu->employee->employee_number }}
                                        </span>
                                        @if ($edu->employee->company)
                                            <p class="mt-1 truncate text-xs text-gray-500">
                                                {{ $edu->employee->company->name }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-400 italic">No employee data</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Education Info -->
                            <div class="col-span-4 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ $edu->institution_name }}
                                </p>
                                <p class="truncate text-sm text-gray-600">{{ $edu->major }}</p>
                                <span
                                    class="mt-1 inline-flex w-fit items-center rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800"
                                >
                                    {{ $edu->degree }}
                                </span>
                            </div>

                            <!-- Period -->
                            <div class="col-span-2 flex flex-col justify-center">
                                <p class="text-sm text-gray-900">{{ $edu->display_period }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-2 flex items-center justify-center gap-2">
                                <button
                                    wire:click="edit({{ $edu->id }})"
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
                                    wire:click="confirmDelete({{ $edu->id }}, '{{ $edu->employee ? $edu->employee->name : 'Unknown' }} - {{ $edu->institution_name }}')"
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
                                <!-- Employee Header -->
                                <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-500 uppercase">Employee</p>
                                        @if ($edu->employee)
                                            <p class="mt-1 font-semibold text-gray-900">{{ $edu->employee->name }}</p>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span
                                                    class="inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                                >
                                                    {{ $edu->employee->employee_number }}
                                                </span>
                                                @if ($edu->employee->company)
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                                    >
                                                        {{ $edu->employee->company->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="mt-1 text-sm text-gray-400 italic">No employee data</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Education Info -->
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Education</p>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $edu->institution_name }}</p>
                                    <p class="mt-1 text-sm text-gray-600">{{ $edu->major }}</p>
                                    <span
                                        class="mt-2 inline-flex items-center rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800"
                                    >
                                        {{ $edu->degree }}
                                    </span>
                                </div>

                                <!-- Period -->
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Study Period</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $edu->display_period }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">
                                    <button
                                        wire:click="edit({{ $edu->id }})"
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
                                        wire:click="confirmDelete({{ $edu->id }}, '{{ $edu->employee ? $edu->employee->name : 'Unknown' }} - {{ $edu->institution_name }}')"
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
                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
                                />
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">No education records found</p>
                            <p class="mt-1 text-sm text-gray-400">
                                Try adjusting your search or add a new education record
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($education->hasPages())
                    <div class="mt-6">
                        {{ $education->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
    <x-ui.modals.delete-confirmation />
</div>
