<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employees</flux:breadcrumbs.item>
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
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employees</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage employee information and assignments</p>
                </div>
            </div>

            <!-- Add Employee Button -->
            <a
                href="{{ route('employees.create') }}"
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
                Add Employee
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Search Row -->
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Search Input -->
                <div class="md:col-span-2">
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
                            placeholder="Search by name, employee number, email, phone, or company..."
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
                            <option value="employee_number_asc">Employee Number (A-Z)</option>
                            <option value="employee_number_desc">Employee Number (Z-A)</option>
                            <option value="hire_date_newest">Hire Date (Newest)</option>
                            <option value="hire_date_oldest">Hire Date (Oldest)</option>
                            <option value="newest">Recently Added</option>
                            <option value="oldest">Oldest Added</option>
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
            <div class="grid gap-4 md:grid-cols-3">
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

                <!-- Position Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Position</label>
                    <div class="relative">
                        <select
                            wire:model.live="positionFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            @if (!$companyFilter) disabled @endif
                        >
                            <option value="">{{ $companyFilter ? 'All Positions' : 'Select company first' }}</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
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

                <!-- Gender Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                    <div class="relative">
                        <select
                            wire:model.live="genderFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
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
                <span class="font-semibold text-gray-900">{{ $employees->count() }}</span>
                of
                <span class="font-semibold text-gray-900">{{ $employees->total() }}</span>
                employees
            </div>
        </div>

        <!-- Employees List Container -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Hidden on mobile -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Employee</div>
                <div class="col-span-2 text-sm font-semibold">Contact</div>
                <div class="col-span-3 text-sm font-semibold">Position</div>
                <div class="col-span-2 text-sm font-semibold">Company</div>
                <div class="col-span-2 text-center text-sm font-semibold">Actions</div>
            </div>

            <!-- List Items -->
            @forelse ($employees as $employee)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    <!-- Employee Info -->
                    <div class="col-span-3 flex gap-2">
                        <!-- Photo -->
                        <div class="flex flex-shrink-0 items-center">
                            @if ($employee->profile_path)
                                <img
                                    src="{{ \Storage::url($employee->profile_path) }}"
                                    alt="{{ $employee->name }}"
                                    class="h-12 w-12 rounded-lg border-2 border-gray-200 object-cover"
                                />
                            @else
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-50 bg-gradient-to-br text-lg font-semibold text-gray-300 shadow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="size-8"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $employee->name }}</p>
                            <span
                                class="mt-1 inline-flex w-fit items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                            >
                                {{ $employee->employee_number }}
                            </span>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="col-span-2 flex flex-col justify-center">
                        <p class="truncate text-sm text-gray-900">{{ $employee->email }}</p>
                        <p class="truncate text-xs text-gray-500">{{ $employee->phone }}</p>
                        <span
                            class="bg-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-100 text-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-800 mt-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                        >
                            {{ ucfirst($employee->gender) }}
                        </span>
                    </div>

                    <!-- Position -->
                    <div class="col-span-3 flex flex-col justify-center">
                        @if ($employee->position)
                            <p class="truncate text-sm font-medium text-gray-900">{{ $employee->position->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $employee->position->work_unit }}</p>
                        @else
                            <span class="text-sm text-gray-400 italic">No position</span>
                        @endif
                    </div>

                    <!-- Company -->
                    <div class="col-span-2 flex flex-col justify-center">
                        @if ($employee->company)
                            <p class="truncate text-sm text-gray-600">{{ $employee->company->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $employee->company->cocd }}</p>
                        @else
                            <span class="text-sm text-gray-400 italic">No company</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2 flex items-center justify-center gap-2">
                        <button
                            wire:click="show({{ $employee->id }})"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#721d21]"
                            title="Show employee details"
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
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                />
                            </svg>
                        </button>
                        <button
                            wire:click="edit({{ $employee->id }})"
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
                            wire:click="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')"
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
                            @if ($employee->profile_path)
                                <img
                                    src="{{ \Storage::url($employee->profile_path) }}"
                                    alt="{{ $employee->name }}"
                                    class="h-16 w-16 flex-shrink-0 rounded-lg border-2 border-gray-200 object-cover"
                                />
                            @else
                                <div
                                    class="color-gradient flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br text-xl font-semibold text-white shadow-sm"
                                >
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase">Employee</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $employee->name }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                    >
                                        {{ $employee->employee_number }}
                                    </span>
                                    <span
                                        class="bg-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-100 text-{{ $employee->gender === 'male' ? 'blue' : 'pink' }}-800 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                    >
                                        {{ ucfirst($employee->gender) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Contact</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->email }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->phone }}</p>
                        </div>

                        <!-- Position & Company -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Position</p>
                                @if ($employee->position)
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $employee->position->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $employee->position->work_unit }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">No position</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Company</p>

                                @if ($employee->company)
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $employee->company->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $employee->company->cocd }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">No company</p>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">
                            <button
                                wire:click="show({{ $employee->id }})"
                                class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#721d21]"
                                title="Show employee details"
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
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                    />
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')"
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
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No employees found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your search or add a new employee</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($employees->hasPages())
            <div class="mt-6">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
    <x-ui.modals.delete-confirmation />
</div>
