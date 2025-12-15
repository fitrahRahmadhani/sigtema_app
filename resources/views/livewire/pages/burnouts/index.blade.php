{{-- resources/views/livewire/pages/burnouts/index.blade.php --}}
<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employee Burnout</flux:breadcrumbs.item>
    </flux:breadcrumbs>

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
                        class="size-6 text-white"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M4.5 10.5h6.75V15H4.5v-4.5ZM3.75 18h15A2.25 2.25 0 0 0 21 15.75v-6a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 1.5 9.75v6A2.25 2.25 0 0 0 3.75 18Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employee Burnout</h1>
                    <p class="mt-1 text-sm text-gray-500">Burnout assessment data</p>
                </div>
            </div>

            <button
                wire:click="syncFromApi"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
            >
                <svg
                    wire:loading.remove
                    wire:target="syncFromApi"
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
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                    />
                </svg>
                <svg
                    wire:loading
                    wire:target="syncFromApi"
                    class="h-5 w-5 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span wire:loading.remove wire:target="syncFromApi">Sync from API</span>
                <span wire:loading wire:target="syncFromApi">Syncing...</span>
            </button>
        </div>

        <!-- Sync Status -->
        @if ($syncStatus !== 'idle')
            <div
                class="{{
                    $syncStatus === 'processing'
                        ? 'border-blue-200 bg-blue-50'
                        : ($syncStatus === 'completed'
                            ? 'border-green-200 bg-green-50'
                            : ($syncStatus === 'failed'
                                ? 'border-red-200 bg-red-50'
                                : 'border-yellow-200 bg-yellow-50'))
                }} mb-6 rounded-lg border p-4"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if ($syncStatus === 'queued')
                            <svg
                                class="h-5 w-5 text-yellow-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span class="ml-2 font-medium text-yellow-800">Sync Queued</span>
                        @elseif ($syncStatus === 'processing')
                            <svg
                                class="h-5 w-5 animate-spin text-blue-600"
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
                            <span class="ml-2 font-medium text-blue-800">Processing...</span>
                        @elseif ($syncStatus === 'completed')
                            <svg
                                class="h-5 w-5 text-green-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span class="ml-2 font-medium text-green-800">Sync Completed</span>
                        @else
                            <svg
                                class="h-5 w-5 text-red-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                            <span class="ml-2 font-medium text-red-800">Sync Failed</span>
                        @endif
                    </div>

                    @if ($syncStatus === 'completed' || $syncStatus === 'failed')
                        <button wire:click="clearSyncStatus" class="text-sm text-gray-500 hover:text-gray-700">
                            Dismiss
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div class="grid gap-4 md:grid-cols-3">
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
                            placeholder="Search by employee name, number, or result..."
                            class="focus:ring-opacity-50 block w-full rounded-lg border border-gray-300 bg-white py-2.5 pr-3 pl-10 text-sm placeholder-gray-500 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Result Filter Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Result</label>
                    <div class="relative">
                        <select
                            wire:model.live="resultFilter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Results</option>
                            @foreach ($results as $result)
                                <option value="{{ $result }}">{{ $result }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-4 w-4 text-gray-400"
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

                <!-- Sort By Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Most Recent</option>
                            <option value="oldest">Oldest</option>
                            <option value="ee_high">EE Score (High to Low)</option>
                            <option value="ee_low">EE Score (Low to High)</option>
                            <option value="dp_high">DP Score (High to Low)</option>
                            <option value="dp_low">DP Score (Low to High)</option>
                            <option value="ad_high">AD Score (High to Low)</option>
                            <option value="ad_low">AD Score (Low to High)</option>
                            <option value="result_asc">Result (A-Z)</option>
                            <option value="result_desc">Result (Z-A)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-4 w-4 text-gray-400"
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
        </div>

        <!-- Burnouts Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Employee</div>
                <div class="col-span-1 text-sm font-semibold">EE</div>
                <div class="col-span-1 text-sm font-semibold">DP</div>
                <div class="col-span-1 text-sm font-semibold">AD</div>
                <div class="col-span-3 text-sm font-semibold">Result</div>
                <div class="col-span-1 text-sm font-semibold">Source</div>
                <div class="col-span-2 text-sm font-semibold">Updated</div>
            </div>

            @forelse ($burnouts as $burnout)
                <!-- Desktop Row -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 hover:shadow-md md:grid"
                >
                    <!-- Employee -->
                    <div class="col-span-3">
                        @if ($burnout->employee)
                            <p class="font-medium text-gray-900">{{ $burnout->employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $burnout->employee_number }}</p>
                        @else
                            <p class="text-gray-900">{{ $burnout->employee_number }}</p>
                            <p class="text-sm text-gray-400">Employee not found</p>
                        @endif
                    </div>

                    <!-- Emotional Exhaustion -->
                    <div class="col-span-1">
                        <span class="font-medium">{{ $burnout->emotional_exhaustion ?? 'N/A' }}</span>
                    </div>

                    <!-- Depersonalization -->
                    <div class="col-span-1">
                        <span class="font-medium">{{ $burnout->depersonalization ?? 'N/A' }}</span>
                    </div>

                    <!-- Achievement Decline -->
                    <div class="col-span-1">
                        <span class="font-medium">{{ $burnout->achievement_decline ?? 'N/A' }}</span>
                    </div>

                    <!-- Result -->
                    <div class="col-span-3">
                        @if ($burnout->result)
                            <span class="text-sm text-gray-800">
                                {{ $burnout->result }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Source -->
                    <div class="col-span-1">
                        <span
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ $burnout->source }}
                        </span>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-2 text-sm text-gray-500">
                        {{ $burnout->updated_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Mobile Row -->
                <div class="block rounded-lg border border-gray-200 bg-white p-4 md:hidden">
                    <div class="space-y-3">
                        <!-- Employee -->
                        <div class="border-b border-gray-100 pb-3">
                            @if ($burnout->employee)
                                <h3 class="font-semibold text-gray-900">{{ $burnout->employee->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $burnout->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $burnout->employee_number }}</p>
                            @endif
                        </div>

                        <!-- Scores Grid -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">EE</p>
                                <p class="text-lg font-semibold">{{ $burnout->emotional_exhaustion ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">DP</p>
                                <p class="text-lg font-semibold">{{ $burnout->depersonalization ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">AD</p>
                                <p class="text-lg font-semibold">{{ $burnout->achievement_decline ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Result -->
                        <div class="border-t border-gray-100 pt-3">
                            <p class="mb-1 text-xs font-medium text-gray-500">Result</p>
                            @if ($burnout->result)
                                <span
                                    class="text-sm text-gray-800"
                                >
                                    {{ $burnout->result }}
                                </span>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>

                        <!-- Updated -->
                        <div class="border-t border-gray-100 pt-3 text-center">
                            <p class="text-xs text-gray-500">Updated: {{ $burnout->updated_at->diffForHumans() }}</p>
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
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No burnout data found</p>
                    <p class="mt-1 text-sm text-gray-400">Click "Sync from API" to load burnout assessment data</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($burnouts->hasPages())
            <div class="mt-6">
                {{ $burnouts->links() }}
            </div>
        @endif
    </div>
</div>
