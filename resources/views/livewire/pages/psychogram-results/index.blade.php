<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Psychogram Results</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Psychogram Results</h1>
                    <p class="mt-1 text-sm text-gray-500">View and manage psychogram evaluation results</p>

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

            {{-- Add Result Button --}}
            @if (! $showCompanySelection)
                <a
                    href="{{ route('psychogram-results.create') }}"
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
                    Add Result
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
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Pilih Perusahaan</h3>
                <p class="mt-2 text-gray-500">Silakan pilih perusahaan untuk mengelola data hasil psikogram</p>

                {{-- Company List --}}
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companiesWithCount as $item)
                        <button
                            wire:click="selectCompany({{ $item['company']->id }})"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($item['company']->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $item['company']->logo_path) }}"
                                            alt="{{ 'Logo ' . $item['company']->name }}"
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
                                    <h4 class="font-semibold text-gray-900">{{ $item['company']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} data hasil psikogram</p>
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
                        Mengelola data hasil psikogram karyawan di
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>

                    {{-- Search Row --}}
                    <div class="grid gap-4 md:grid-cols-3">
                        {{-- Search Input --}}
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
                                    placeholder="Search by employee name or employee number..."
                                    class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                            </div>
                        </div>

                        {{-- Sort By --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                            <div class="relative">
                                <select
                                    wire:model.live="sortBy"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="created_desc">Recently Added</option>
                                    <option value="created_asc">Oldest Added</option>
                                    <option value="score_desc">Score (High to Low)</option>
                                    <option value="score_asc">Score (Low to High)</option>
                                    <option value="employee_name_asc">Employee Name (A-Z)</option>
                                    <option value="employee_name_desc">Employee Name (Z-A)</option>
                                    <option value="aspect_name_asc">Aspect Name (A-Z)</option>
                                    <option value="aspect_name_desc">Aspect Name (Z-A)</option>
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

                    {{-- Filters Row --}}
                    <div class="grid gap-4 md:grid-cols-3">
                        {{-- Aspect Filter --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Aspect</label>
                            <div class="relative">
                                <select
                                    wire:model.live="aspectFilter"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="">All Aspects</option>
                                    @foreach ($aspects as $aspect)
                                        <option value="{{ $aspect->id }}">
                                            {{ $aspect->aspect_code }} - {{ $aspect->aspect_name }}
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
                        </div>

                        {{-- Score Range Filter --}}
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Score Range</label>
                            <div class="flex gap-2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="scoreMin"
                                    placeholder="Min"
                                    min="0"
                                    max="100"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                <span class="flex items-center text-gray-400">-</span>
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="scoreMax"
                                    placeholder="Max"
                                    min="0"
                                    max="100"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                            </div>
                        </div>

                        {{-- Reset Filters Button --}}
                        <div class="flex items-end">
                            <button
                                type="button"
                                wire:click="resetFilters"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-4 w-4"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                    />
                                </svg>
                                Reset Filters
                            </button>
                        </div>
                    </div>

                    {{-- Results Count --}}
                    <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                        Showing
                        <span class="font-semibold text-gray-900">{{ $results->count() }}</span>
                        of
                        <span class="font-semibold text-gray-900">{{ $results->total() }}</span>
                        results
                    </div>
                </div>

                {{-- Results List Container --}}
                <div class="space-y-3 overflow-x-auto">
                    {{-- Column Headers - Hidden on mobile --}}
                    <div
                        class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
                    >
                        <div class="col-span-3 text-sm font-semibold">Employee</div>
                        <div class="col-span-2 text-sm font-semibold">Aspect</div>
                        <div class="col-span-1 text-sm font-semibold">Score</div>
                        <div class="col-span-3 text-sm font-semibold">Details</div>
                        <div class="col-span-1 text-sm font-semibold">Last Updated</div>
                        <div class="col-span-2 text-center text-sm font-semibold">Actions</div>
                    </div>

                    {{-- List Items --}}
                    @forelse ($results as $result)
                        {{-- Desktop View --}}
                        <div
                            class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                        >
                            {{-- Employee & Aspect Info --}}
                            <div class="col-span-3">
                                <div class="flex flex-col gap-2">
                                    {{-- Employee --}}
                                    <div class="flex items-center gap-2">
                                        @if ($result->employee->profile_path)
                                            <img
                                                src="{{ Storage::url($result->employee->profile_path) }}"
                                                alt="{{ $result->employee->name }}"
                                                class="h-8 w-8 rounded-full border border-gray-200 object-cover"
                                            />
                                        @else
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-600"
                                            >
                                                {{ substr($result->employee->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $result->employee->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $result->employee->employee_number }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Aspect --}}
                            <div class="col-span-2">
                                <div class="text-start">
                                    <p class="text-sm font-medium text-gray-700">{{ $result->aspect->aspect_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $result->aspect->aspect_code }}</p>
                                </div>
                            </div>

                            {{-- Score --}}
                            <div class="col-span-1">
                                <p class="text-xl font-bold text-gray-900">{{ $result->score }}</p>
                            </div>

                            {{-- Details --}}
                            <div class="col-span-3">
                                <div class="flex flex-col gap-1">
                                    @if ($result->employee->company)
                                        <div class="flex items-center gap-1 text-sm">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="h-4 w-4 text-gray-400"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                                                />
                                            </svg>
                                            <span class="text-gray-600">{{ $result->employee->company->name }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-1 text-sm">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-4 w-4 text-gray-400"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"
                                            />
                                        </svg>
                                        <span class="text-gray-600">
                                            Recorded: {{ $result->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Last Updated --}}
                            <div class="col-span-1">
                                <div class="flex items-center gap-1 text-sm">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4 text-gray-400"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                    <span class="text-gray-600">{{ $result->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="col-span-2 flex items-center justify-center gap-2">
                                <button
                                    wire:click="edit({{ $result->id }})"
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
                                    wire:click="confirmDelete({{ $result->id }}, 'Result for {{ $result->employee->name }} - {{ $result->aspect->aspect_name }}')"
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

                        {{-- Mobile View --}}
                        <div
                            class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                        >
                            <div class="space-y-4">
                                {{-- Header --}}
                                <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                                    <div class="flex items-center gap-3">
                                        @if ($result->employee->profile_path)
                                            <img
                                                src="{{ Storage::url($result->employee->profile_path) }}"
                                                alt="{{ $result->employee->name }}"
                                                class="h-12 w-12 rounded-full border border-gray-200 object-cover"
                                            />
                                        @else
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600"
                                            >
                                                {{ substr($result->employee->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $result->employee->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $result->employee->employee_number }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-gray-900">{{ $result->score }}</span>
                                    </div>
                                </div>

                                {{-- Aspect Info --}}
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Aspect</p>
                                    <p class="mt-1 font-medium text-gray-900">{{ $result->aspect->aspect_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $result->aspect->aspect_code }}</p>
                                </div>

                                {{-- Details --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Company</p>

                                        @if ($result->employee->company)
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $result->employee->company->name }}
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-gray-400 italic">No company</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Recorded</p>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $result->created_at->format('M d, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $result->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">
                                    <button
                                        wire:click="edit({{ $result->id }})"
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
                                        wire:click="confirmDelete({{ $result->id }}, 'Result for {{ $result->employee->name }} - {{ $result->aspect->aspect_name }}')"
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
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                                />
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">No psychogram results found</p>
                            <p class="mt-1 text-sm text-gray-400">Try adjusting your filters or add a new result</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($results->hasPages())
                    <div class="mt-6">
                        {{ $results->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
    <x-ui.modals.delete-confirmation />
</div>
