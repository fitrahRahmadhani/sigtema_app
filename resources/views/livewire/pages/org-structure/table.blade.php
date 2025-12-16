<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organization-structure.index') }}">
            Organization Structure (Chart)
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Organization Structure (Table)</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-8">
        <!-- Header Section -->
        <div class="mb-8">
            <!-- Title Row -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl shadow-sm">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            fill="currentColor"
                            class="h-6 w-6 text-white"
                            viewBox="0 0 16 16"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 9 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl font-bold text-gray-900 sm:text-2xl lg:text-3xl">Organization Structure</h1>
                        <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                            Manage organizational structure and reporting lines
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <a
                        href="{{ route('organization-structure.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                                d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"
                            />
                        </svg>
                        <span>Chart View</span>
                    </a>
                    <a
                        href="{{ route('organization-structure.create') }}"
                        class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:opacity-90 focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                        <span>Add Hierarchy</span>
                    </a>
                </div>
            </div>

            <!-- Company Viewing Info (Superadmin Only) -->
            @if ($isSuperadmin && $selectedCompanyId)
                <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg
                            class="h-5 w-5 flex-shrink-0 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-blue-900">
                                Viewing data for:
                                <strong>
                                    {{ \App\Models\Company::find($selectedCompanyId)->name ?? 'Selected Company' }}
                                </strong>
                            </p>
                            @if ($companyFilter != $selectedCompanyId)
                                <p class="mt-2 flex items-start gap-2 text-xs text-orange-700">
                                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>
                                        Currently filtered by different company.
                                        <button
                                            wire:click="resetCompanyFilter"
                                            class="ml-1 font-medium text-blue-700 underline hover:text-blue-800"
                                        >
                                            Show data for active session company
                                        </button>
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4 md:p-6">
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Search Input -->
                <div class="md:col-span-1">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search Hierarchy</label>
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
                            placeholder="Search position or relation..."
                            class="w-full rounded-lg border border-gray-300 py-2.5 pr-4 pl-10 text-sm transition-colors focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Company Filter -->
                @role('superadmin')
                    <div class="relative">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div class="relative">
                            <select
                                wire:model.live="companyFilter"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
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
                @endrole

                @role('admin')
                    <div class="flex flex-col">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 pr-10 text-sm text-gray-700"
                        >
                            <p>{{ $company }}</p>
                        </div>
                    </div>
                @endrole

                <!-- Relation Type Filter -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Relation Type</label>
                    <div class="relative">
                        <select
                            wire:model.live="relationTypeFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Types</option>
                            @foreach ($relationTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
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
            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                <div class="text-sm text-gray-600">
                    Showing
                    <span class="font-semibold text-gray-900">{{ $hierarchies->count() }}</span>
                    of
                    <span class="font-semibold text-gray-900">{{ $hierarchies->total() }}</span>
                    hierarchies
                </div>
                @if ($search || $companyFilter || $relationTypeFilter)
                    <button
                        wire:click="resetFilters"
                        class="text-sm font-medium text-[#721d21] transition-colors hover:text-[#8b252a]"
                    >
                        Clear filters
                    </button>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Hidden on mobile -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Company</div>
                <div class="col-span-3 text-sm font-semibold">Position</div>
                <div class="col-span-3 text-sm font-semibold">Reports To</div>
                <div class="col-span-2 text-sm font-semibold">Type</div>
                <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
            </div>

            <!-- List Items -->
            @forelse ($hierarchies as $hierarchy)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-all duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    <!-- Company -->
                    <div class="col-span-3 flex items-center gap-3">
                        @if ($hierarchy->company->logo_path)
                            <img
                                src="{{ \Storage::url($hierarchy->company->logo_path) }}"
                                alt="{{ $hierarchy->company->name }}"
                                class="h-10 w-10 flex-shrink-0 rounded-lg border border-gray-200 object-contain p-1"
                            />
                        @else
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-5 w-5 text-gray-400"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                                    />
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="truncate text-sm font-medium text-gray-900">
                                {{ $hierarchy->company->name }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $hierarchy->company->cocd }}</div>
                        </div>
                    </div>

                    <!-- Position -->
                    <div class="col-span-3 flex min-w-0 flex-col justify-center">
                        <a
                            href="{{ route('positions.edit', $hierarchy->position->slug) }}"
                            class="truncate text-sm font-medium text-gray-900 hover:text-[#721d21] hover:underline"
                        >
                            {{ $hierarchy->position->name }}
                        </a>
                        <div class="text-xs text-gray-500">{{ $hierarchy->position->position_code }}</div>
                        <span
                            class="bg-{{ $hierarchy->position->employee ? 'blue' : 'pink' }}-100 text-{{ $hierarchy->position->employee ? 'blue' : 'pink' }}-800 mt-1 line-clamp-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                        >
                            {{ $hierarchy->position->employee->name ?? 'Vacant Position' }}
                        </span>
                    </div>

                    <!-- Reports To -->
                    <div class="col-span-3 flex min-w-0 flex-col justify-center">
                        <a
                            href="{{ route('positions.edit', $hierarchy->superior->slug) }}"
                            class="truncate text-sm font-medium text-gray-900 hover:text-[#721d21] hover:underline"
                        >
                            {{ $hierarchy->superior->name }}
                        </a>
                        <div class="text-xs text-gray-500">{{ $hierarchy->superior->position_code }}</div>
                        <span
                            class="bg-{{ $hierarchy->superior->employee ? 'blue' : 'pink' }}-100 text-{{ $hierarchy->superior->employee ? 'blue' : 'pink' }}-800 mt-1 line-clamp-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                        >
                            {{ $hierarchy->superior->employee->name ?? 'Vacant Position' }}
                        </span>
                    </div>

                    <!-- Relation Type -->
                    <div class="col-span-2 flex items-center">
                        <span
                            class="{{ $hierarchy->relation_type === 'direct' ? 'bg-green-100 text-green-800' : ($hierarchy->relation_type === 'indirect' ? 'bg-blue-100 text-blue-800' : ($hierarchy->relation_type === 'dotted' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }} inline-flex rounded-full px-3 py-1 text-xs font-medium"
                        >
                            {{ ucfirst($hierarchy->relation_type) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1 flex items-center justify-center gap-2">
                        <a
                            href="{{ route('organization-structure.edit', $hierarchy) }}"
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
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                />
                            </svg>
                        </a>
                        <button
                            wire:click="confirmDelete({{ $hierarchy->id }}, '{{ $hierarchy->position->name . ' - ' . $hierarchy->superior->name }}')"
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
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile View -->
                <div
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-all duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-4">
                        <!-- Company Header -->
                        <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                            @if ($hierarchy->company->logo_path)
                                <img
                                    src="{{ \Storage::url($hierarchy->company->logo_path) }}"
                                    alt="{{ $hierarchy->company->name }}"
                                    class="h-12 w-12 flex-shrink-0 rounded-lg border border-gray-200 object-contain p-1"
                                />
                            @else
                                <div
                                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-6 w-6 text-gray-400"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                                        />
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium tracking-wide text-gray-500 uppercase">Company</p>
                                <p class="mt-1 truncate font-semibold text-gray-900">
                                    {{ $hierarchy->company->name }}
                                </p>
                                <span class="mt-1 inline-flex text-xs text-gray-500">
                                    {{ $hierarchy->company->cocd }}
                                </span>
                            </div>
                        </div>

                        <!-- Position Info -->
                        <div>
                            <p class="text-xs font-medium tracking-wide text-gray-500 uppercase">Position</p>
                            <div class="mt-2">
                                <a
                                    href="{{ route('positions.edit', $hierarchy->position->slug) }}"
                                    class="text-sm font-medium text-gray-900 hover:text-[#721d21] hover:underline"
                                >
                                    {{ $hierarchy->position->name }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $hierarchy->position->position_code }}</p>
                                <span
                                    class="bg-{{ $hierarchy->position->employee ? 'blue' : 'pink' }}-100 text-{{ $hierarchy->position->employee ? 'blue' : 'pink' }}-800 mt-1 line-clamp-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                >
                                    {{ $hierarchy->position->employee->name ?? 'Vacant Position' }}
                                </span>
                            </div>
                        </div>

                        <!-- Reports To Info -->
                        <div>
                            <p class="text-xs font-medium tracking-wide text-gray-500 uppercase">Reports To</p>
                            <div class="mt-2">
                                <a
                                    href="{{ route('positions.edit', $hierarchy->superior->slug) }}"
                                    class="text-sm font-medium text-gray-900 hover:text-[#721d21] hover:underline"
                                >
                                    {{ $hierarchy->superior->name }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $hierarchy->superior->position_code }}</p>
                                <span
                                    class="bg-{{ $hierarchy->superior->employee ? 'blue' : 'pink' }}-100 text-{{ $hierarchy->superior->employee ? 'blue' : 'pink' }}-800 mt-1 line-clamp-1 inline-flex w-fit items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                >
                                    {{ $hierarchy->superior->employee->name ?? 'Vacant Position' }}
                                </span>
                            </div>
                        </div>

                        <!-- Type & Actions -->
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <span
                                class="{{ $hierarchy->relation_type === 'direct' ? 'bg-green-100 text-green-800' : ($hierarchy->relation_type === 'indirect' ? 'bg-blue-100 text-blue-800' : ($hierarchy->relation_type === 'dotted' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }} inline-flex rounded-full px-3 py-1 text-xs font-medium"
                            >
                                {{ ucfirst($hierarchy->relation_type) }}
                            </span>
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('organization-structure.edit', $hierarchy) }}"
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
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                        />
                                    </svg>
                                </a>
                                <button
                                    wire:click="confirmDelete({{ $hierarchy->id }}, '{{ $hierarchy->position->name . ' - ' . $hierarchy->superior->name }}')"
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
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-12 text-center">
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
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"
                        />
                    </svg>
                    <p class="mt-4 text-lg font-medium text-gray-900">No hierarchies found</p>
                    <p class="mt-2 text-sm text-gray-500">Get started by creating a new hierarchy relationship</p>
                    <a
                        href="{{ route('organization-structure.create') }}"
                        class="color-gradient mt-6 inline-flex items-center gap-2 rounded-lg px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:opacity-90"
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
                        Add First Hierarchy
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($hierarchies->hasPages())
            <div class="mt-6">
                {{ $hierarchies->links() }}
            </div>
        @endif
    </div>
    <x-ui.modals.delete-confirmation />
</div>
