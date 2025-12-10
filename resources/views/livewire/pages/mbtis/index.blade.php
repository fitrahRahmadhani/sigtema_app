{{-- resources/views/livewire/pages/mbtis/index.blade.php --}}
<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>MBTI Assessment</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
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
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">MBTI Assessment</h1>
                    <p class="mt-1 text-sm text-gray-500">View employee MBTI personality type data</p>
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

        <!-- Stats -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-full bg-blue-100 p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6 text-blue-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Assessments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Mbti::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-full bg-green-100 p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6 text-green-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Unique MBTI Types</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Mbti::whereNotNull('mbti')->distinct()->count('mbti') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-full bg-purple-100 p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6 text-purple-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Employees Matched</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Mbti::whereNotNull('employee_id')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-full bg-orange-100 p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6 text-orange-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Latest Update</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @php
                                $latest = \App\Models\Mbti::latest('updated_at')->first();
                                echo $latest ? $latest->updated_at->diffForHumans() : 'Never';
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync Status Panel -->
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
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search</label>
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
                            placeholder="Search by employee name, number, or MBTI type..."
                            class="focus:ring-opacity-50 block w-full rounded-lg border border-gray-300 bg-white py-2.5 pr-3 pl-10 text-sm placeholder-gray-500 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        />
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
                            <option value="mbti_asc">MBTI (A-Z)</option>
                            <option value="mbti_desc">MBTI (Z-A)</option>
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

            <div class="grid gap-4 md:grid-cols-2">
                <!-- MBTI Filter Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">MBTI Type</label>
                    <div class="relative">
                        <select
                            wire:model.live="mbtiFilter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All MBTI Types</option>
                            @foreach ($mbtiTypes as $mbti)
                                <option value="{{ $mbti }}">{{ $mbti }}</option>
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

                <!-- Source Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Source</label>
                    <div class="relative">
                        <select
                            disabled
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 pr-10 text-sm text-gray-500 shadow-sm"
                        >
                            <option>Eksternal</option>
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

        <!-- MBTI Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-4 text-sm font-semibold">Employee</div>
                <div class="col-span-3 text-sm font-semibold">MBTI Type</div>
                <div class="col-span-2 text-sm font-semibold">Source</div>
                <div class="col-span-3 text-center text-sm font-semibold">Updated</div>
            </div>

            @forelse ($mbtis as $mbti)
                <!-- Desktop Row -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 hover:shadow-md md:grid"
                >
                    <!-- Employee -->
                    <div class="col-span-4">
                        @if ($mbti->employee)
                            <p class="font-medium text-gray-900">{{ $mbti->employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $mbti->employee_number }}</p>
                        @else
                            <p class="text-gray-900">{{ $mbti->employee_number }}</p>
                            <p class="text-sm text-gray-400">Employee not found</p>
                        @endif
                    </div>

                    <!-- MBTI Type -->
                    <div class="col-span-3">
                        @if ($mbti->mbti)
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700"
                            >
                                {{ $mbti->mbti }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Source -->
                    <div class="col-span-2">
                        <span
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ $mbti->source }}
                        </span>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-3 text-center text-sm text-gray-500">
                        {{ $mbti->updated_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Mobile Row -->
                <div class="block rounded-lg border border-gray-200 bg-white p-4 md:hidden">
                    <div class="space-y-3">
                        <!-- Employee -->
                        <div class="border-b border-gray-100 pb-3">
                            @if ($mbti->employee)
                                <h3 class="font-semibold text-gray-900">{{ $mbti->employee->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $mbti->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $mbti->employee_number }}</p>
                            @endif
                        </div>

                        <!-- MBTI Type -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-gray-500">MBTI Type</p>
                            @if ($mbti->mbti)
                                <span
                                    class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700"
                                >
                                    {{ $mbti->mbti }}
                                </span>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>

                        <!-- Source -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-gray-500">Source</p>
                            <span
                                class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                            >
                                {{ $mbti->source }}
                            </span>
                        </div>

                        <!-- Updated -->
                        <div class="border-t border-gray-100 pt-3 text-center">
                            <p class="text-xs text-gray-500">Updated: {{ $mbti->updated_at->diffForHumans() }}</p>
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
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No MBTI data found</p>
                    <p class="mt-1 text-sm text-gray-400">Click "Sync from API" to load MBTI assessment data</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($mbtis->hasPages())
            <div class="mt-6">
                {{ $mbtis->links() }}
            </div>
        @endif
    </div>
</div>
