{{-- resources/views/livewire/pages/papis/index.blade.php --}}
<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>PAPI Assessment</flux:breadcrumbs.item>
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
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">PAPI Assessment</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        View employee PAPI (Personality and Preference Inventory) data
                    </p>
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
                            placeholder="Search by employee name or number..."
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
                            <option value="N_high">N Score (High to Low)</option>
                            <option value="N_low">N Score (Low to High)</option>
                            <option value="G_high">G Score (High to Low)</option>
                            <option value="G_low">G Score (Low to High)</option>
                            <option value="A_high">A Score (High to Low)</option>
                            <option value="A_low">A Score (Low to High)</option>
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

        <!-- PAPIs Table -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-2 text-sm font-semibold">Employee</div>
                <div class="col-span-8 text-sm font-semibold">PAPI Scores</div>
                <div class="col-span-1 text-sm font-semibold">Source</div>
                <div class="col-span-1 text-center text-sm font-semibold">Updated</div>
            </div>

            @forelse ($papis as $papi)
                <!-- Desktop Row -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 hover:shadow-md md:grid"
                >
                    <!-- Employee -->
                    <div class="col-span-2">
                        @if ($papi->employee)
                            <p class="font-medium text-gray-900">{{ $papi->employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $papi->employee_number }}</p>
                        @else
                            <p class="text-gray-900">{{ $papi->employee_number }}</p>
                            <p class="text-sm text-gray-400">Employee not found</p>
                        @endif
                    </div>

                    <!-- PAPI Scores Grid -->
                    <div class="col-span-8">
                        <div class="grid grid-cols-10 gap-2">
                            <!-- Row 1 -->
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">N</p>
                                <p
                                    class="{{ $papi->N >= 7 ? 'text-blue-600' : 'text-gray-700' }} text-center text-xs font-medium"
                                >
                                    {{ $papi->N ?? '-' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">G</p>
                                <p
                                    class="{{ $papi->G >= 7 ? 'text-green-600' : 'text-gray-700' }} text-center text-xs font-medium"
                                >
                                    {{ $papi->G ?? '-' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">A</p>
                                <p
                                    class="{{ $papi->A >= 7 ? 'text-purple-600' : 'text-gray-700' }} text-center text-xs font-medium"
                                >
                                    {{ $papi->A ?? '-' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">L</p>
                                <p
                                    class="{{ $papi->L >= 7 ? 'text-orange-600' : 'text-gray-700' }} text-center text-xs font-medium"
                                >
                                    {{ $papi->L ?? '-' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">P</p>
                                <p
                                    class="{{ $papi->P >= 7 ? 'text-red-600' : 'text-gray-700' }} text-center text-xs font-medium"
                                >
                                    {{ $papi->P ?? '-' }}
                                </p>
                            </div>

                            <!-- Row 2 -->
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">I</p>
                                <p class="text-center text-xs font-medium">{{ $papi->I ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">T</p>
                                <p class="text-center text-xs font-medium">{{ $papi->T ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">V</p>
                                <p class="text-center text-xs font-medium">{{ $papi->V ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">S</p>
                                <p class="text-center text-xs font-medium">{{ $papi->S ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">B</p>
                                <p class="text-center text-xs font-medium">{{ $papi->B ?? '-' }}</p>
                            </div>

                            <!-- Row 3 -->
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">O</p>
                                <p class="text-center text-xs font-medium">{{ $papi->O ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">X</p>
                                <p class="text-center text-xs font-medium">{{ $papi->X ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">C</p>
                                <p class="text-center text-xs font-medium">{{ $papi->C ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">D</p>
                                <p class="text-center text-xs font-medium">{{ $papi->D ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">R</p>
                                <p class="text-center text-xs font-medium">{{ $papi->R ?? '-' }}</p>
                            </div>

                            <!-- Row 4 -->
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">Z</p>
                                <p class="text-center text-xs font-medium">{{ $papi->Z ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">E</p>
                                <p class="text-center text-xs font-medium">{{ $papi->E ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">K</p>
                                <p class="text-center text-xs font-medium">{{ $papi->K ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">F</p>
                                <p class="text-center text-xs font-medium">{{ $papi->F ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-medium text-gray-500">W</p>
                                <p class="text-center text-xs font-medium">{{ $papi->W ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Source -->
                    <div class="col-span-1">
                        <span
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ $papi->source }}
                        </span>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-1 text-center text-sm text-gray-500">
                        {{ $papi->updated_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Mobile Row -->
                <div class="block rounded-lg border border-gray-200 bg-white p-4 md:hidden">
                    <div class="space-y-3">
                        <!-- Employee -->
                        <div class="border-b border-gray-100 pb-3">
                            @if ($papi->employee)
                                <h3 class="font-semibold text-gray-900">{{ $papi->employee->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $papi->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $papi->employee_number }}</p>
                            @endif
                        </div>

                        <!-- PAPI Scores Grid -->
                        <div class="space-y-3">
                            <!-- First Row -->
                            <div class="grid grid-cols-5 gap-2">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">N</p>
                                    <p
                                        class="{{ $papi->N >= 7 ? 'text-blue-600' : 'text-gray-700' }} text-lg font-semibold"
                                    >
                                        {{ $papi->N ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">G</p>
                                    <p
                                        class="{{ $papi->G >= 7 ? 'text-green-600' : 'text-gray-700' }} text-lg font-semibold"
                                    >
                                        {{ $papi->G ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">A</p>
                                    <p
                                        class="{{ $papi->A >= 7 ? 'text-purple-600' : 'text-gray-700' }} text-lg font-semibold"
                                    >
                                        {{ $papi->A ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">L</p>
                                    <p
                                        class="{{ $papi->L >= 7 ? 'text-orange-600' : 'text-gray-700' }} text-lg font-semibold"
                                    >
                                        {{ $papi->L ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">P</p>
                                    <p
                                        class="{{ $papi->P >= 7 ? 'text-red-600' : 'text-gray-700' }} text-lg font-semibold"
                                    >
                                        {{ $papi->P ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="grid grid-cols-5 gap-2">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">I</p>
                                    <p class="text-lg font-semibold">{{ $papi->I ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">T</p>
                                    <p class="text-lg font-semibold">{{ $papi->T ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">V</p>
                                    <p class="text-lg font-semibold">{{ $papi->V ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">S</p>
                                    <p class="text-lg font-semibold">{{ $papi->S ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">B</p>
                                    <p class="text-lg font-semibold">{{ $papi->B ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Third Row -->
                            <div class="grid grid-cols-5 gap-2">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">O</p>
                                    <p class="text-lg font-semibold">{{ $papi->O ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">X</p>
                                    <p class="text-lg font-semibold">{{ $papi->X ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">C</p>
                                    <p class="text-lg font-semibold">{{ $papi->C ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">D</p>
                                    <p class="text-lg font-semibold">{{ $papi->D ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">R</p>
                                    <p class="text-lg font-semibold">{{ $papi->R ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div class="grid grid-cols-5 gap-2">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Z</p>
                                    <p class="text-lg font-semibold">{{ $papi->Z ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">E</p>
                                    <p class="text-lg font-semibold">{{ $papi->E ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">K</p>
                                    <p class="text-lg font-semibold">{{ $papi->K ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">F</p>
                                    <p class="text-lg font-semibold">{{ $papi->F ?? '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">W</p>
                                    <p class="text-lg font-semibold">{{ $papi->W ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Source & Updated -->
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                                >
                                    {{ $papi->source }}
                                </span>
                                <p class="text-xs text-gray-500">{{ $papi->updated_at->diffForHumans() }}</p>
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
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No PAPI data found</p>
                    <p class="mt-1 text-sm text-gray-400">Click "Sync from API" to load PAPI assessment data</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($papis->hasPages())
            <div class="mt-6">
                {{ $papis->links() }}
            </div>
        @endif
    </div>
</div>
