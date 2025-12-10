{{-- resources/views/livewire/pages/discs/index.blade.php --}}
<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>DISC Assessment</flux:breadcrumbs.item>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">DISC Assessment</h1>
                    <p class="mt-1 text-sm text-gray-500">View employee DISC personality assessment data</p>
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
                                d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Assessments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Disc::count() }}</p>
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
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Interpretation 1</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Disc::whereNotNull('interpretation1')->distinct()->count('interpretation1') }}
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
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Interpretation 2</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Disc::whereNotNull('interpretation2')->distinct()->count('interpretation2') }}
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
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Interpretation 3</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Disc::whereNotNull('interpretation3')->distinct()->count('interpretation3') }}
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
                            placeholder="Search by employee name, number, or interpretation..."
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
                            <option value="interpretation1_asc">Interpretation 1 (A-Z)</option>
                            <option value="interpretation1_desc">Interpretation 1 (Z-A)</option>
                            <option value="interpretation2_asc">Interpretation 2 (A-Z)</option>
                            <option value="interpretation2_desc">Interpretation 2 (Z-A)</option>
                            <option value="interpretation3_asc">Interpretation 3 (A-Z)</option>
                            <option value="interpretation3_desc">Interpretation 3 (Z-A)</option>
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

            <div class="grid gap-4 md:grid-cols-3">
                <!-- Interpretation 1 Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Interpretation 1</label>
                    <div class="relative">
                        <select
                            wire:model.live="interpretation1Filter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Types</option>
                            @foreach ($interpretation1s as $interpretation)
                                <option value="{{ $interpretation }}">{{ $interpretation }}</option>
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

                <!-- Interpretation 2 Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Interpretation 2</label>
                    <div class="relative">
                        <select
                            wire:model.live="interpretation2Filter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Types</option>
                            @foreach ($interpretation2s as $interpretation)
                                <option value="{{ $interpretation }}">{{ $interpretation }}</option>
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

                <!-- Interpretation 3 Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Interpretation 3</label>
                    <div class="relative">
                        <select
                            wire:model.live="interpretation3Filter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Types</option>
                            @foreach ($interpretation3s as $interpretation)
                                <option value="{{ $interpretation }}">{{ $interpretation }}</option>
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
            </div>
        </div>

        <!-- DISCs Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Employee</div>
                <div class="col-span-6 text-sm font-semibold">DISC Scores</div>
                <div class="col-span-2 text-sm font-semibold">Source</div>
                <div class="col-span-1 text-center text-sm font-semibold">Updated</div>
            </div>

            @forelse ($discs as $disc)
                <!-- Desktop Row -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 hover:shadow-md md:grid"
                >
                    <!-- Employee -->
                    <div class="col-span-3">
                        @if ($disc->employee)
                            <p class="font-medium text-gray-900">{{ $disc->employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $disc->employee_number }}</p>
                        @else
                            <p class="text-gray-900">{{ $disc->employee_number }}</p>
                            <p class="text-sm text-gray-400">Employee not found</p>
                        @endif
                    </div>

                    <!-- DISC Scores Grid -->
                    <div class="col-span-6">
                        <div class="grid grid-cols-4 gap-4">
                            <!-- Set 1 -->
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Set 1</p>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="font-medium">{{ $disc->D1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="font-medium">{{ $disc->I1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="font-medium">{{ $disc->S1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="font-medium">{{ $disc->C1 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation1)
                                    <p class="text-center text-xs font-medium text-blue-600">
                                        {{ $disc->interpretation1 }}
                                    </p>
                                @endif
                            </div>

                            <!-- Set 2 -->
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Set 2</p>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="font-medium">{{ $disc->D2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="font-medium">{{ $disc->I2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="font-medium">{{ $disc->S2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="font-medium">{{ $disc->C2 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation2)
                                    <p class="text-center text-xs font-medium text-green-600">
                                        {{ $disc->interpretation2 }}
                                    </p>
                                @endif
                            </div>

                            <!-- Set 3 -->
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Set 3</p>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="font-medium">{{ $disc->D3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="font-medium">{{ $disc->I3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="font-medium">{{ $disc->S3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="font-medium">{{ $disc->C3 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation3)
                                    <p class="text-center text-xs font-medium text-purple-600">
                                        {{ $disc->interpretation3 }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Source -->
                    <div class="col-span-2">
                        <span
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ $disc->source }}
                        </span>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-1 text-center text-sm text-gray-500">
                        {{ $disc->updated_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Mobile Row -->
                <div class="block rounded-lg border border-gray-200 bg-white p-4 md:hidden">
                    <div class="space-y-3">
                        <!-- Employee -->
                        <div class="border-b border-gray-100 pb-3">
                            @if ($disc->employee)
                                <h3 class="font-semibold text-gray-900">{{ $disc->employee->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $disc->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $disc->employee_number }}</p>
                            @endif
                        </div>

                        <!-- DISC Scores -->
                        <div class="space-y-4">
                            <!-- Set 1 -->
                            <div class="rounded border border-gray-200 p-3">
                                <p class="mb-2 text-xs font-medium text-gray-500">Set 1</p>
                                <div class="grid grid-cols-4 gap-2">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="text-lg font-semibold">{{ $disc->D1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="text-lg font-semibold">{{ $disc->I1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="text-lg font-semibold">{{ $disc->S1 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="text-lg font-semibold">{{ $disc->C1 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation1)
                                    <p class="mt-2 text-center text-xs font-medium text-blue-600">
                                        {{ $disc->interpretation1 }}
                                    </p>
                                @endif
                            </div>

                            <!-- Set 2 -->
                            <div class="rounded border border-gray-200 p-3">
                                <p class="mb-2 text-xs font-medium text-gray-500">Set 2</p>
                                <div class="grid grid-cols-4 gap-2">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="text-lg font-semibold">{{ $disc->D2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="text-lg font-semibold">{{ $disc->I2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="text-lg font-semibold">{{ $disc->S2 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="text-lg font-semibold">{{ $disc->C2 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation2)
                                    <p class="mt-2 text-center text-xs font-medium text-green-600">
                                        {{ $disc->interpretation2 }}
                                    </p>
                                @endif
                            </div>

                            <!-- Set 3 -->
                            <div class="rounded border border-gray-200 p-3">
                                <p class="mb-2 text-xs font-medium text-gray-500">Set 3</p>
                                <div class="grid grid-cols-4 gap-2">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">D</p>
                                        <p class="text-lg font-semibold">{{ $disc->D3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">I</p>
                                        <p class="text-lg font-semibold">{{ $disc->I3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">S</p>
                                        <p class="text-lg font-semibold">{{ $disc->S3 ?? '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">C</p>
                                        <p class="text-lg font-semibold">{{ $disc->C3 ?? '-' }}</p>
                                    </div>
                                </div>
                                @if ($disc->interpretation3)
                                    <p class="mt-2 text-center text-xs font-medium text-purple-600">
                                        {{ $disc->interpretation3 }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Source & Updated -->
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                                >
                                    {{ $disc->source }}
                                </span>
                                <p class="text-xs text-gray-500">{{ $disc->updated_at->diffForHumans() }}</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No DISC data found</p>
                    <p class="mt-1 text-sm text-gray-400">Click "Sync from API" to load DISC assessment data</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($discs->hasPages())
            <div class="mt-6">
                {{ $discs->links() }}
            </div>
        @endif
    </div>
</div>
