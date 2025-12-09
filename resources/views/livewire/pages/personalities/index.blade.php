{{-- resources/views/livewire/pages/personalities/index.blade.php --}}
<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Personality Assessment</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-7 w-7 text-white"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 9h.01M8.99 9H9m12 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM6.6 13a5.5 5.5 0 0 0 10.81 0H6.6Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Personality Assessment</h1>
                    <p class="mt-1 text-sm text-gray-500">View employee personality assessment data</p>
                </div>
            </div>

            <button
                wire:click="syncFromApi"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Personality::count() }}</p>
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
                        <p class="text-sm font-medium text-gray-500">MBTI Types</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Personality::whereNotNull('mbti')->distinct()->count('mbti') }}
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
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">DISC Types</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Personality::whereNotNull('disc')->distinct()->count('disc') }}
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
                                d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">PAPI Types</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Personality::whereNotNull('papi')->distinct()->count('papi') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync Status Panel (sama seperti innovations) -->
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
            <div class="grid gap-4 md:grid-cols-4">
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
                            placeholder="Search by employee, MBTI, DISC, or PAPI..."
                            class="focus:ring-opacity-50 block w-full rounded-lg border border-gray-300 bg-white py-2.5 pr-3 pl-10 text-sm placeholder-gray-500 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Sort By Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Most Recent</option>
                            <option value="oldest">Oldest</option>
                            <option value="mbti_asc">MBTI (A-Z)</option>
                            <option value="mbti_desc">MBTI (Z-A)</option>
                            <option value="disc_asc">DISC (A-Z)</option>
                            <option value="disc_desc">DISC (Z-A)</option>
                            <option value="papi_asc">PAPI (A-Z)</option>
                            <option value="papi_desc">PAPI (Z-A)</option>
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
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 pr-10 text-sm text-gray-500"
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

            <div class="grid gap-4 md:grid-cols-3">
                <!-- MBTI Filter Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">MBTI Type</label>
                    <div class="relative">
                        <select
                            wire:model.live="mbtiFilter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
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

                <!-- DISC Filter Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">DISC Type</label>
                    <div class="relative">
                        <select
                            wire:model.live="discFilter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All DISC Types</option>
                            @foreach ($discTypes as $disc)
                                <option value="{{ $disc }}">{{ $disc }}</option>
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

                <!-- PAPI Filter Dropdown -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">PAPI Type</label>
                    <div class="relative">
                        <select
                            wire:model.live="papiFilter"
                            class="focus:ring-opacity-50 block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All PAPI Types</option>
                            @foreach ($papiTypes as $papi)
                                <option value="{{ $papi }}">{{ $papi }}</option>
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

        <!-- Personalities Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Employee</div>
                <div class="col-span-2 text-sm font-semibold">MBTI</div>
                <div class="col-span-2 text-sm font-semibold">DISC</div>
                <div class="col-span-2 text-sm font-semibold">PAPI</div>
                <div class="col-span-2 text-sm font-semibold">Source</div>
                <div class="col-span-1 text-center text-sm font-semibold">Updated</div>
            </div>

            @forelse ($personalities as $personality)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 hover:shadow-md md:grid"
                >
                    <!-- Employee -->
                    <div class="col-span-3">
                        @if ($personality->employee)
                            <p class="font-medium text-gray-900">{{ $personality->employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $personality->employee_number }}</p>
                        @else
                            <p class="text-gray-900">{{ $personality->employee_number }}</p>
                            <p class="text-sm text-gray-400">Employee not found</p>
                        @endif
                    </div>

                    <!-- MBTI -->
                    <div class="col-span-2">
                        @if ($personality->mbti)
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700"
                            >
                                {{ $personality->mbti }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- DISC -->
                    <div class="col-span-2">
                        @if ($personality->disc)
                            <span
                                class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-sm font-medium text-green-700"
                            >
                                {{ $personality->disc }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- PAPI -->
                    <div class="col-span-2">
                        @if ($personality->papi)
                            <span
                                class="inline-flex items-center rounded-md bg-purple-50 px-3 py-1 text-sm font-medium text-purple-700"
                            >
                                {{ $personality->papi }}
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
                            {{ $personality->source }}
                        </span>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-1 text-center text-sm text-gray-500">
                        {{ $personality->updated_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="block rounded-lg border border-gray-200 bg-white p-4 md:hidden">
                    <div class="space-y-3">
                        <!-- Employee -->
                        <div class="border-b border-gray-100 pb-3">
                            @if ($personality->employee)
                                <h3 class="font-semibold text-gray-900">{{ $personality->employee->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $personality->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $personality->employee_number }}</p>
                            @endif
                        </div>

                        <!-- Personality Types -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">MBTI</p>
                                @if ($personality->mbti)
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700"
                                    >
                                        {{ $personality->mbti }}
                                    </span>
                                @else
                                    <p class="text-gray-400">-</p>
                                @endif
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">DISC</p>
                                @if ($personality->disc)
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-sm font-medium text-green-700"
                                    >
                                        {{ $personality->disc }}
                                    </span>
                                @else
                                    <p class="text-gray-400">-</p>
                                @endif
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500">PAPI</p>
                                @if ($personality->papi)
                                    <span
                                        class="inline-flex items-center rounded-md bg-purple-50 px-3 py-1 text-sm font-medium text-purple-700"
                                    >
                                        {{ $personality->papi }}
                                    </span>
                                @else
                                    <p class="text-gray-400">-</p>
                                @endif
                            </div>
                        </div>

                        <!-- Updated -->
                        <div class="border-t border-gray-100 pt-3 text-center">
                            <p class="text-xs text-gray-500">
                                Updated: {{ $personality->updated_at->diffForHumans() }}
                            </p>
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
                    <p class="mt-4 font-medium text-gray-500">No personality assessments found</p>
                    <p class="mt-1 text-sm text-gray-400">Click "Sync from API" to load personality assessment data</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($personalities->hasPages())
            <div class="mt-6">
                {{ $personalities->links() }}
            </div>
        @endif
    </div>
</div>
