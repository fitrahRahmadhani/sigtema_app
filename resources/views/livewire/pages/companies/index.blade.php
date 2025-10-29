<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Companies</flux:breadcrumbs.item>
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
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Companies</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage your company information</p>
                </div>
            </div>

            <!-- Add Company Button -->
            <a
                href="{{ route('companies.create') }}"
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
                Add Company
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Desktop Layout: Search & Filter -->
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search Company</label>
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
                            placeholder="Search by name, code, or admin..."
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

            <!-- Results Info -->
            <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                Showing
                <span class="font-semibold text-gray-900">{{ $companies->count() }}</span>
                of
                <span class="font-semibold text-gray-900">{{ $companies->total() }}</span>
                companies
            </div>
        </div>

        <!-- Companies List Container -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Hidden on mobile -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-1 text-sm font-semibold">Logo</div>
                <div class="col-span-3 text-sm font-semibold">Company Name</div>
                <div class="col-span-2 text-sm font-semibold">Company Code</div>
                <div class="col-span-3 text-sm font-semibold">Admin</div>
                <div class="col-span-2 text-sm font-semibold">Status</div>
                <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
            </div>

            <!-- List Items -->
            @forelse ($companies as $company)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    <!-- Logo -->
                    <div class="col-span-1 flex items-center">
                        @if ($company->logo_path)
                            <img
                                src="{{ asset($company->logo_path) }}"
                                alt="{{ $company->name }}"
                                class="h-12 w-12 rounded-lg border border-gray-200 object-cover"
                            />
                        @else
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-lg border border-gray-200 bg-gray-100"
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
                    </div>

                    <!-- Company Name -->
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $company->name }}</p>
                    </div>

                    <!-- Company Code -->
                    <div class="col-span-2 flex flex-col justify-center">
                        <span
                            class="inline-flex w-fit items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ $company->cocd }}
                        </span>
                    </div>

                    <!-- Admin Info -->
                    <div class="col-span-3 flex items-center gap-3 overflow-hidden">
                        @if ($company->admin)
                            <div class="flex items-center gap-2">
                                @if ($company->admin->profile_path)
                                    <img
                                        src="{{ Storage::url($company->admin->profile_path) }}"
                                        alt="{{ $company->admin->name }}"
                                        class="h-8 w-8 rounded-full border-2 border-white shadow-sm"
                                    />
                                @else
                                    <div
                                        class="color-gradient flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br text-xs font-semibold text-white shadow-sm"
                                    >
                                        {{ substr($company->admin->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $company->admin->name }}
                                    </p>
                                    <p class="truncate text-xs text-gray-500">{{ $company->admin->email }}</p>
                                </div>
                            </div>
                        @else
                            <span class="text-sm text-gray-400 italic">No admin assigned</span>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="col-span-2 flex items-center">
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800"
                        >
                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-600"></span>
                            Active
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1 flex items-center justify-center gap-2">
                        <button
                            wire:click="edit({{ $company->id }})"
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
                            wire:click="confirmDelete({{ $company->id }})"
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
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
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
                        <!-- Company Header -->
                        <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                            @if ($company->logo_path)
                                <img
                                    src="{{ Storage::url($company->logo_path) }}"
                                    alt="{{ $company->name }}"
                                    class="h-16 w-16 flex-shrink-0 rounded-lg border border-gray-200 object-cover"
                                />
                            @else
                                <div
                                    class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-100"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-8 w-8 text-gray-400"
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
                                <p class="text-xs font-medium text-gray-500 uppercase">Company Name</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $company->name }}</p>
                                <span
                                    class="mt-2 inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800"
                                >
                                    {{ $company->cocd }}
                                </span>
                            </div>
                        </div>

                        <!-- Admin Info -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Administrator</p>
                            @if ($company->admin)
                                <div class="mt-2 flex items-center gap-3">
                                    @if ($company->admin->profile_path)
                                        <img
                                            src="{{ Storage::url($company->admin->profile_path) }}"
                                            alt="{{ $company->admin->name }}"
                                            class="h-10 w-10 rounded-full border-2 border-white shadow-sm"
                                        />
                                    @else
                                        <div
                                            class="color-gradient flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br text-sm font-semibold text-white shadow-sm"
                                        >
                                            {{ substr($company->admin->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-medium text-gray-900">{{ $company->admin->name }}</p>
                                        <p class="truncate text-sm text-gray-500">{{ $company->admin->email }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="mt-2 text-sm text-gray-400 italic">No admin assigned</p>
                            @endif
                        </div>

                        <!-- Status & Actions -->
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <span
                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800"
                            >
                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                Active
                            </span>
                            <div class="flex items-center gap-2">
                                <button
                                    wire:click="edit({{ $company->id }})"
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
                                    wire:click="confirmDelete({{ $company->id }})"
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
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                        />
                                    </svg>
                                </button>
                            </div>
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
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No companies found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your search or add a new company</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($companies->hasPages())
            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
