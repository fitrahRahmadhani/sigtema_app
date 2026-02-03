<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Position Requirements</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
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
                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Position Requirements</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage requirements for each position</p>

                    {{-- Company Info with Change Button --}}
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

            {{-- Add Requirements Button --}}
            @if (! $showCompanySelection && $selectedCompany)
                <a
                    href="{{ route('requirements.position.create') }}"
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
                    Add Requirements
                </a>
            @endif
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
                <p class="mt-2 text-gray-500">Please select a company to manage position requirements</p>

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
                                        {{ \App\Models\Position::where('company_id', $company->id)->count() }}
                                        positions
                                    </p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- MAIN CONTENT SECTION --}}
        @if (! $showCompanySelection)
            @if ($selectedCompany)
                {{-- Search and Filter Section --}}
                <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    {{-- Results Info --}}
                    <div class="text-sm text-gray-600">
                        Managing position requirements for
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>

                    {{-- Search Input --}}
                    <div>
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
                                placeholder="Search by position name or code..."
                                class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            />
                        </div>
                    </div>

                    {{-- Results Count --}}
                    <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                        Showing
                        <span class="font-semibold text-gray-900">{{ $positions->count() }}</span>
                        of
                        <span class="font-semibold text-gray-900">{{ $positions->total() }}</span>
                        positions
                    </div>
                </div>

                {{-- Positions List Container --}}
                <div class="space-y-3 overflow-x-auto">
                    {{-- Column Headers - Hidden on mobile --}}
                    <div
                        class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
                    >
                        <div class="col-span-3 text-sm font-semibold">Position</div>
                        <div class="col-span-2 text-sm font-semibold">Employee</div>
                        <div class="col-span-2 text-sm font-semibold">Organizational Unit</div>
                        <div class="col-span-2 text-sm font-semibold">Job Grade & Band</div>
                        <div class="col-span-2 text-sm font-semibold">Requirements</div>
                        <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
                    </div>

                    {{-- List Items --}}
                    @forelse ($positions as $position)
                        {{-- Desktop View --}}
                        <div
                            class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                        >
                            {{-- Position Name & Code --}}
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

                            {{-- Employee --}}
                            <div class="col-span-2 overflow-hidden">
                                @if ($position->employee_id)
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $position->employee->name }}
                                    </p>
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

                            {{-- Organizational Unit --}}
                            <div class="col-span-2 overflow-hidden">
                                @if ($position->department)
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $position->department }}
                                    </p>
                                    @if ($position->directorate)
                                        <p class="truncate text-xs text-gray-500">{{ $position->directorate }}</p>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400 italic">No department</span>
                                @endif
                            </div>

                            {{-- Job Grade & Band --}}
                            <div class="col-span-2">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800"
                                    >
                                        Grade: {{ $position->job_grade }}
                                    </span>
                                    @if ($position->band)
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800"
                                        >
                                            {{ $position->band }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Requirements --}}
                            <div class="col-span-2">
                                @php
                                    $requirementCount = $requirementsCounts[$position->id] ?? 0;
                                @endphp

                                @if ($requirementCount > 0)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-800"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="mr-1 h-3 w-3"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {{ $requirementCount }} requirements
                                        </span>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="mr-1 h-3 w-3"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                            />
                                        </svg>
                                        No requirements
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="col-span-1 flex items-center justify-center gap-2">
                                {{-- View/Edit Requirements Button --}}

                                @if ($requirementCount > 0)
                                    <a
                                        href="{{ route('requirements.position.edit', ['position' => $position->id]) }}"
                                        class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#721d21]"
                                        title="Edit Requirements"
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
                                    </a>
                                @else
                                    <a
                                        href="{{ route('requirements.position.create', ['position_id' => $position->id]) }}"
                                        class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-green-50 hover:text-green-600"
                                        title="Add Requirements"
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
                                                d="M12 4.5v15m7.5-7.5h-15"
                                            />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Mobile View --}}
                        <div
                            class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                        >
                            <div class="space-y-4">
                                {{-- Position Header --}}
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
                                    {{-- Requirements Badge Mobile --}}
                                    <div>
                                        @php
                                            $requirementCount = $requirementsCounts[$position->id] ?? 0;
                                        @endphp

                                        @if ($requirementCount > 0)
                                            <span
                                                class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-800"
                                            >
                                                {{ $requirementCount }} req
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                                            >
                                                No req
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Employee --}}
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Employee</p>
                                    @if ($position->employee_id)
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $position->employee->name }}
                                        </p>
                                        @if ($position->employee->employee_number)
                                            <p class="text-xs text-gray-500">
                                                {{ $position->employee->employee_number }}
                                            </p>
                                        @endif
                                    @else
                                        <p
                                            class="mt-1 w-fit rounded-full bg-red-50 px-2 py-1 text-sm font-medium text-red-500 italic"
                                        >
                                            Vacant Position
                                        </p>
                                    @endif
                                </div>

                                {{-- Organizational Unit & Job Grade --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Organizational Unit</p>
                                        @if ($position->department)
                                            <p class="mt-1 text-sm font-medium text-gray-900">
                                                {{ $position->department }}
                                            </p>
                                            @if ($position->directorate)
                                                <p class="text-xs text-gray-500">{{ $position->directorate }}</p>
                                            @endif
                                        @else
                                            <p class="mt-1 text-sm text-gray-400 italic">No department</p>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Job Grade & Band</p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800"
                                            >
                                                Grade: {{ $position->job_grade }}
                                            </span>
                                            @if ($position->band)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800"
                                                >
                                                    {{ $position->band }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">
                                    {{-- View/Edit Requirements Button --}}

                                    @if ($requirementCount > 0)
                                        <a
                                            href="{{ route('requirements.position.edit', ['position' => $position->id]) }}"
                                            class="rounded-lg bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-200"
                                            title="Edit Requirements"
                                        >
                                            Edit Requirements
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('requirements.position.create', ['position_id' => $position->id]) }}"
                                            class="rounded-lg bg-green-100 px-4 py-2 text-sm font-medium text-green-700 transition-colors hover:bg-green-200"
                                            title="Add Requirements"
                                        >
                                            Add Requirements
                                        </a>
                                    @endif
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
                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                                />
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">No positions found</p>
                            <p class="mt-1 text-sm text-gray-400">No positions available in this company</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($positions->hasPages())
                    <div class="mt-6">
                        {{ $positions->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
