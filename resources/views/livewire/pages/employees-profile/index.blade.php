<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Pattern</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employee Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4">
            <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-white">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employee Profile</h1>

                {{-- FIX: Hanya tampilkan jika selectedCompany ADA dan BUKAN null --}}
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

        {{-- COMPANY SELECTION SECTION --}}
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
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Select Company</h3>
                <p class="mt-2 text-gray-500">Please select a company to compare employees</p>

                {{-- Company List --}}
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
                                            alt="{{ 'Logo' . $company->name }}"
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
                                    <p class="text-sm text-gray-500">{{ $company->employees_count ?? 0 }} employees</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- MAIN CONTENT SECTION --}}
        @if (! $showCompanySelection)
            {{-- FIX: Pastikan $selectedCompany ada sebelum menggunakan --}}
            @if ($selectedCompany)
                {{-- Search and Filter Section --}}
                <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    {{-- Results Info --}}
                    <div class="text-sm text-gray-600">
                        Showing employees at
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>

                    {{-- Desktop Layout --}}
                    <div class="hidden gap-4 md:grid md:grid-cols-6">
                        {{-- Search Input --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Search Name or Employee ID
                            </label>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Type name or employee ID..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            />
                        </div>

                        {{-- Filter Band --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Band</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterBand"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">All Bands</option>
                                    @foreach ($bands as $band)
                                        <option value="{{ $band }}">Band {{ $band }}</option>
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

                        {{-- Filter Work Unit --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Work Unit</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterUnit"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">All Units</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
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

                        {{-- Filter Department --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Department</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterDepartment"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
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

                        {{-- Filter Gender --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterGender"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">All Genders</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
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

                    {{-- Mobile Layout --}}
                    <div class="space-y-4 md:hidden">
                        {{-- Search Input --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Search Name or Employee ID
                            </label>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Type name or employee ID..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            />
                        </div>

                        {{-- Filter Row 1 --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Band --}}
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Band</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterBand"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">All Bands</option>
                                        @foreach ($bands as $band)
                                            <option value="{{ $band }}">Band {{ $band }}</option>
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

                            {{-- Work Unit --}}
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Work Unit</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterUnit"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">All Units</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}">{{ $unit }}</option>
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

                        {{-- Filter Row 2 --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Department --}}
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Department</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterDepartment"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">All Depts</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept }}">{{ $dept }}</option>
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

                            {{-- Gender --}}
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterGender"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">All</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
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
                    </div>

                    {{-- Results Count & Reset Filter --}}
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <div class="text-sm text-gray-600">
                            Showing
                            <span class="font-semibold">{{ $employees->count() }}</span>
                            of
                            <span class="font-semibold">{{ $employees->total() }}</span>
                            employees
                        </div>
                        @if ($search || $filterGender || $filterUnit || $filterDepartment || $filterBand)
                            <button
                                wire:click="resetFilters"
                                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-gray-900"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filters
                            </button>
                        @endif
                    </div>
                </div>

                {{-- List Container --}}
                <div class="space-y-3 overflow-x-auto">
                    {{-- Column Headers - Hidden on mobile --}}
                    <div
                        class="color-gradient hidden place-items-center justify-items-start gap-4 rounded-lg px-4 py-3 text-white md:grid md:grid-cols-12"
                    >
                        <div class="col-span-1 text-sm font-semibold">Employee ID</div>
                        <div class="col-span-2 text-sm font-semibold">Name</div>
                        <div class="col-span-1 text-sm font-semibold">Band</div>
                        <div class="col-span-1 text-sm font-semibold">Gender</div>
                        <div class="col-span-1 text-sm font-semibold">JG/PG</div>
                        <div class="col-span-3 text-sm font-semibold">Position</div>
                        <div class="col-span-3 text-sm font-semibold">Work Unit</div>
                    </div>

                    {{-- List Items --}}
                    @forelse ($employees as $employee)
                        {{-- Desktop View --}}
                        <a
                            href="{{ route('employees-profile.show', $employee->slug) }}"
                            class="hidden rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid md:grid-cols-12 md:gap-4"
                        >
                            <div class="col-span-1 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    <span class="inline lg:hidden">{{ substr($employee->employee_number, -4) }}</span>
                                    <span class="hidden lg:inline">{{ $employee->employee_number }}</span>
                                </p>
                            </div>
                            <div class="col-span-2 flex flex-col justify-center">
                                <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $employee->name }}</p>
                            </div>
                            <div class="col-span-1 flex flex-col justify-center">
                                <span
                                    class="mt-1 inline-flex w-fit items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                >
                                    <span class="mr-1 hidden lg:inline">Band</span>
                                    {{ $employee->band }}
                                </span>
                            </div>
                            <div class="col-span-1 flex flex-col justify-center">
                                <span
                                    class="bg-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-100 text-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-800 mt-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                >
                                    {{ ucfirst($employee->gender) }}
                                </span>
                            </div>
                            <div class="col-span-1 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ optional($employee->position)->job_grade ?? '-' }}/{{ $employee->personal_grade }}
                                </p>
                            </div>
                            <div class="col-span-3 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ optional($employee->position)->name ?? 'Tidak ada posisi' }}
                                </p>
                            </div>
                            <div class="col-span-3 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ optional($employee->position)->work_unit ?? '-' }}
                                </p>
                            </div>
                        </a>

                        {{-- Mobile View --}}
                        <a
                            href="{{ route('employees-profile.show', $employee->slug) }}"
                            class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                        >
                            <div class="space-y-3">
                                <div class="border-b border-gray-100 pb-3">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Nama</p>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $employee->name }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Employee ID</p>
                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $employee->employee_number }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Gender</p>
                                        <span
                                            class="bg-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-100 text-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-800 mt-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                        >
                                            {{ ucfirst($employee->gender) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">JG/PG</p>
                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ optional($employee->position)->job_grade ?? '-' }}/{{ $employee->personal_grade }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Unit</p>
                                        <p class="mt-1 line-clamp-1 font-semibold text-gray-900">
                                            {{ optional($employee->position)->work_unit ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Posisi</p>
                                    <p class="mt-1 line-clamp-1 font-semibold text-gray-900">
                                        {{ optional($employee->position)->name ?? 'Tidak ada posisi' }}
                                    </p>
                                </div>
                            </div>
                        </a>
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
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
                                />
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">Tidak ada karyawan yang ditemukan</p>
                            <p class="mt-1 text-sm text-gray-400">Coba ubah filter pencarian Anda</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($employees->hasPages())
                    <div class="mt-6">
                        {{ $employees->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
