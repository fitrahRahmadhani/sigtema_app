<div class="space-y-4 p-2">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>PAPI Assessment</flux:breadcrumbs.item>
    </flux:breadcrumbs>

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
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">PAPI Assessment</h1>
                    <p class="mt-1 text-sm text-gray-500">Personality and Preference Inventory data</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                {{-- Sync Button --}}
                <button
                    wire:click="syncFromApi"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    @if($syncStatus === 'processing' || $syncStatus === 'queued') disabled @endif
                    class="color-gradient inline-flex cursor-pointer items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                    <span wire:loading.remove wire:target="syncFromApi">
                        @if ($syncStatus === 'processing' || $syncStatus === 'queued')
                            Syncing...
                        @else
                            Sync from API
                        @endif
                    </span>
                    <span wire:loading wire:target="syncFromApi">Queueing...</span>
                </button>
            </div>
        </div>

        {{-- Stats --}}
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
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total PAPI Records</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalCount) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-green-200 bg-green-50 p-4">
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
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-700">Active</p>
                        <p class="text-2xl font-semibold text-green-700">{{ number_format($activeCount) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-full bg-red-100 p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6 text-red-600"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-700">Inactive</p>
                        <p class="text-2xl font-semibold text-red-700">{{ number_format($inactiveCount) }}</p>
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
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Latest Sync</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @php
                                $latest = \App\Models\Papi::latest('updated_at')->first();
                                echo $latest ? $latest->updated_at->diffForHumans() : 'Never';
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sync Status Panel --}}
        @if ($syncStatus !== 'idle')
            <div class="animate-fade-in mb-6">
                <div
                    class="{{ $syncStatus === 'queued' ? 'border-blue-200 bg-blue-50' : ($syncStatus === 'processing' ? 'border-blue-200 bg-blue-50' : ($syncStatus === 'completed' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50')) }} rounded-lg border p-4 shadow-sm"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if ($syncStatus === 'queued')
                                <svg
                                    class="h-5 w-5 animate-pulse text-blue-600"
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
                                <span class="ml-2 font-medium text-blue-800">
                                    PAPI Sync Queued - Waiting to start...
                                </span>
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
                                <span class="ml-2 font-medium text-blue-800">PAPI Sync Processing...</span>
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
                                <span class="ml-2 font-medium text-green-800">PAPI Sync Completed</span>
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
                                <span class="ml-2 font-medium text-red-800">PAPI Sync Failed</span>
                            @endif
                        </div>

                        @if ($syncStatus === 'completed' || $syncStatus === 'failed')
                            <button
                                wire:click="clearSyncStatus"
                                class="cursor-pointer text-sm text-gray-500 underline transition-colors hover:text-gray-700"
                            >
                                Dismiss
                            </button>
                        @endif
                    </div>

                    {{-- Progress Bar --}}
                    @if (in_array($syncStatus, ['queued', 'processing']))
                        <div class="mt-3">
                            <div class="mb-1 flex justify-between text-sm">
                                <span
                                    class="{{ $syncStatus === 'queued' ? 'text-blue-600' : 'text-blue-600' }} font-medium"
                                >
                                    {{ $syncMessage }}
                                </span>
                                @if ($syncTotal > 0)
                                    <span class="{{ $syncStatus === 'queued' ? 'text-blue-600' : 'text-blue-600' }}">
                                        {{ $syncCurrent }} / {{ $syncTotal }}
                                    </span>
                                @endif
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-blue-200">
                                <div
                                    class="{{ $syncStatus === 'queued' ? 'animate-pulse bg-blue-400' : 'bg-blue-600' }} h-2 rounded-full transition-all duration-500 ease-out"
                                    style="width: {{ $syncProgress }}%"
                                ></div>
                            </div>
                            <p class="{{ $syncStatus === 'queued' ? 'text-blue-600' : 'text-blue-600' }} mt-1 text-xs">
                                @if ($isPolling)
                                    <span class="inline-flex items-center">Live updates enabled</span>
                                @else
                                        You can close this page safely.
                                @endif
                            </p>
                        </div>
                    @endif

                    {{-- Result Details --}}
                    @if ($lastSyncResult && ($syncStatus === 'completed' || $syncStatus === 'failed'))
                        <div class="mt-3 border-t border-gray-200 pt-3">
                            @if ($syncStatus === 'completed')
                                <div class="grid grid-cols-2 gap-4 text-sm md:grid-cols-5">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-700">
                                            {{ $lastSyncResult['created'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">Created</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-700">
                                            {{ $lastSyncResult['updated'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">Updated</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-amber-700">
                                            {{ $lastSyncResult['deactivated'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">Deactivated</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-yellow-700">
                                            {{ $lastSyncResult['skipped'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">Skipped</div>
                                    </div>
                                    @if (isset($lastSyncResult['permanently_deleted']) && $lastSyncResult['permanently_deleted'] > 0)
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-red-700">
                                                {{ $lastSyncResult['permanently_deleted'] ?? 0 }}
                                            </div>
                                            <div class="mt-1 text-xs text-gray-600">Deleted</div>
                                        </div>
                                    @endif

                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">
                                            {{ $lastSyncResult['total'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">Total Processed</div>
                                    </div>
                                </div>
                                @if (isset($lastSyncResult['started_at']) && isset($lastSyncResult['completed_at']))
                                    @php
                                        $startedAt = \Carbon\Carbon::parse($lastSyncResult['started_at']);
                                        $completedAt = \Carbon\Carbon::parse($lastSyncResult['completed_at']);
                                        $duration = $startedAt->diff($completedAt);
                                    @endphp

                                    <p class="mt-4 ml-6 text-start text-xs text-gray-600">
                                        Duration: {{ $duration->format('%Hh %Im %Ss') }}
                                    </p>
                                @endif
                            @else
                                <div class="rounded-lg bg-red-50 p-3">
                                    <div class="flex">
                                        <svg
                                            class="mt-0.5 mr-2 h-5 w-5 text-red-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-red-800">PAPI Sync Error</p>
                                            <p class="mt-1 text-sm text-red-700">
                                                {{ $lastSyncResult['error'] ?? 'Unknown error occurred' }}
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        wire:click="syncFromApi"
                                        class="mt-3 inline-flex items-center rounded border border-transparent bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition-colors hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                    >
                                        <svg
                                            class="mr-1 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                            />
                                        </svg>
                                        Retry Sync
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Filters --}}
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4 shadow-sm">
            {{-- Search Row --}}
            <div class="grid gap-4 md:grid-cols-8">
                {{-- Search Input - Hanya untuk employee name/number --}}
                <div class="md:col-span-4">
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
                            class="block w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 pl-10 text-sm placeholder-gray-500 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                    <div class="relative">
                        <select
                            wire:model.live="activeFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                            <option value="all">All Status</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-4 w-4 text-gray-400"
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

                {{-- Sort By --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Most Recent</option>
                            <option value="oldest">Oldest</option>
                            <option value="N_high">N Score (High to Low)</option>
                            <option value="N_low">N Score (Low to High)</option>
                            <option value="G_high">G Score (High to Low)</option>
                            <option value="G_low">G Score (Low to High)</option>
                            <option value="A_high">A Score (High to Low)</option>
                            <option value="A_low">A Score (Low to High)</option>
                            <option value="L_high">L Score (High to Low)</option>
                            <option value="L_low">L Score (Low to High)</option>
                            <option value="P_high">P Score (High to Low)</option>
                            <option value="P_low">P Score (Low to High)</option>
                            <option value="I_high">I Score (High to Low)</option>
                            <option value="I_low">I Score (Low to High)</option>
                            <option value="T_high">T Score (High to Low)</option>
                            <option value="T_low">T Score (Low to High)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-4 w-4 text-gray-400"
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
            </div>

            {{-- Results Info --}}
            <div class="flex items-center justify-start">
                <div class="text-sm text-gray-600">
                    Showing {{ $papis->count() }} of {{ $papis->total() }} PAPI records
                    @if ($search)
                        <span class="text-gray-400">• Search: "{{ $search }}"</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- PAPI Table --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            {{-- Table Header - Desktop --}}
            <div class="color-gradient hidden grid-cols-12 gap-4 bg-gradient-to-r px-6 py-3 text-white md:grid">
                <div class="col-span-2 text-sm font-semibold">Employee</div>
                <div class="col-span-8 text-sm font-semibold">PAPI Scores</div>
                <div class="col-span-1 text-sm font-semibold">Status</div>
                <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-gray-200">
                @forelse ($papis as $papi)
                    {{-- Desktop View --}}
                    <div
                        class="{{ $papi->is_active ? 'hover:bg-gray-50' : 'bg-gray-50 opacity-90 hover:bg-gray-100' }} hidden grid-cols-12 items-center gap-4 px-6 py-4 transition-colors duration-150 md:grid"
                        wire:key="papi-{{ $papi->id }}"
                    >
                        {{-- Employee --}}
                        <div class="col-span-2">
                            @if ($papi->employee)
                                <p class="font-medium text-gray-900">{{ $papi->employee->name }}</p>
                                <p class="text-sm text-gray-500">{{ $papi->employee_number }}</p>
                            @else
                                <p class="text-gray-900">{{ $papi->employee_number }}</p>
                                <p class="text-sm text-gray-400">Employee not found</p>
                            @endif
                        </div>

                        {{-- PAPI Scores Grid --}}
                        <div class="col-span-8">
                            <div class="grid grid-cols-10 gap-2">
                                {{-- Row 1: Main Traits --}}
                                @php
                                    $mainTraits = [
                                        'N' => ['label' => 'N', 'color' => 'text-blue-600'],
                                        'G' => ['label' => 'G', 'color' => 'text-green-600'],
                                        'A' => ['label' => 'A', 'color' => 'text-purple-600'],
                                        'L' => ['label' => 'L', 'color' => 'text-orange-600'],
                                        'P' => ['label' => 'P', 'color' => 'text-red-600'],
                                        'I' => ['label' => 'I', 'color' => 'text-gray-700'],
                                        'T' => ['label' => 'T', 'color' => 'text-gray-700'],
                                        'V' => ['label' => 'V', 'color' => 'text-gray-700'],
                                        'S' => ['label' => 'S', 'color' => 'text-gray-700'],
                                        'B' => ['label' => 'B', 'color' => 'text-gray-700'],
                                    ];
                                @endphp

                                @foreach ($mainTraits as $trait => $config)
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-500">{{ $config['label'] }}</p>
                                        <p class="{{ $config['color'] }} text-sm font-semibold">
                                            {{ $papi->{$trait} ?? '-' }}
                                        </p>
                                    </div>
                                @endforeach

                                {{-- Row 2: Secondary Traits --}}
                                @php
                                    $secondaryTraits = [
                                        'O' => ['label' => 'O', 'color' => 'text-gray-700'],
                                        'X' => ['label' => 'X', 'color' => 'text-gray-700'],
                                        'C' => ['label' => 'C', 'color' => 'text-gray-700'],
                                        'D' => ['label' => 'D', 'color' => 'text-gray-700'],
                                        'R' => ['label' => 'R', 'color' => 'text-gray-700'],
                                        'Z' => ['label' => 'Z', 'color' => 'text-gray-700'],
                                        'E' => ['label' => 'E', 'color' => 'text-gray-700'],
                                        'K' => ['label' => 'K', 'color' => 'text-gray-700'],
                                        'F' => ['label' => 'F', 'color' => 'text-gray-700'],
                                        'W' => ['label' => 'W', 'color' => 'text-gray-700'],
                                    ];
                                @endphp

                                @foreach ($secondaryTraits as $trait => $config)
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-500">{{ $config['label'] }}</p>
                                        <p class="{{ $config['color'] }} text-sm font-semibold">
                                            {{ $papi->{$trait} ?? '-' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-1">
                            @if ($papi->is_active)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                >
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800"
                                >
                                    Inactive
                                </span>
                            @endif
                        </div>

                        {{-- Actions - Hanya Delete --}}
                        <div class="col-span-1 flex items-center justify-center gap-2">
                            <button
                                wire:click="confirmDelete({{ $papi->id }}, '{{ addslashes($papi->employee ? $papi->employee->name : $papi->employee_number) }}')"
                                class="rounded-lg p-1.5 text-gray-600 transition-colors hover:bg-red-50 hover:text-red-600 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                title="Delete PAPI Record"
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
                        class="{{ $papi->is_active ? '' : 'bg-gray-50 opacity-90' }} block p-4 md:hidden"
                        wire:key="papi-mobile-{{ $papi->id }}"
                    >
                        <div class="space-y-3">
                            {{-- Header --}}
                            <div class="border-b border-gray-100 pb-3">
                                <div class="flex items-start justify-between">
                                    @if ($papi->employee)
                                        <h3 class="font-semibold text-gray-900">{{ $papi->employee->name }}</h3>
                                    @else
                                        <p class="text-gray-900">{{ $papi->employee_number }}</p>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        @if ($papi->is_active)
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                            >
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800"
                                            >
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ $papi->employee_number }}</p>
                            </div>

                            {{-- PAPI Scores Grid --}}
                            <div class="space-y-3">
                                {{-- First Row - Main Traits --}}
                                <div>
                                    <p class="mb-2 text-xs font-medium text-gray-500">Main Traits</p>
                                    <div class="grid grid-cols-5 gap-2">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">N</p>
                                            <p class="text-lg font-semibold text-blue-600">{{ $papi->N ?? '-' }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">G</p>
                                            <p class="text-lg font-semibold text-green-600">{{ $papi->G ?? '-' }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">A</p>
                                            <p class="text-lg font-semibold text-purple-600">{{ $papi->A ?? '-' }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">L</p>
                                            <p class="text-lg font-semibold text-orange-600">{{ $papi->L ?? '-' }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">P</p>
                                            <p class="text-lg font-semibold text-red-600">{{ $papi->P ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Second Row --}}
                                <div>
                                    <p class="mb-2 text-xs font-medium text-gray-500">Other Traits</p>
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
                                </div>

                                {{-- Third Row --}}
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

                                {{-- Fourth Row --}}
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

                            {{-- Actions - Hanya Delete --}}
                            <div class="border-t border-gray-100 pt-3">
                                <div class="flex justify-end">
                                    <button
                                        wire:click="confirmDelete({{ $papi->id }}, '{{ addslashes($papi->employee ? $papi->employee->name : $papi->employee_number) }}')"
                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-100 hover:text-red-800 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                        title="Delete PAPI Record"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="mr-1 h-4 w-4"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                            />
                                        </svg>
                                        Delete Record
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="mx-auto h-16 w-16 text-gray-300"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                            />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-500">No PAPI data found</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm text-gray-400">
                            @if ($search || $activeFilter !== 'all')
                                Try adjusting your filters or search terms.
                            @else
                                    Click "Sync from API" to load PAPI assessment data.
                            @endif
                        </p>
                        @if (! $search && $activeFilter === 'all')
                            <button
                                wire:click="syncFromApi"
                                class="mt-6 inline-flex items-center rounded-lg bg-gradient-to-r from-[#721d21] to-[#9d2d32] px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
                            >
                                <svg
                                    class="mr-2 h-5 w-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                                Sync from API
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <x-ui.modals.delete-confirmation />

        {{-- Pagination --}}
        @if ($papis->hasPages())
            <div class="mt-6">
                {{ $papis->onEachSide(1)->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            // Setup Echo/Pusher listeners untuk PAPI
            @if(auth()->check())
                const userId = {{ auth()->id() }};

                // Check if Echo is available
                if (typeof window.Echo !== 'undefined') {
                    // Listen for PAPI sync status updates via Echo
                    window.Echo.private(`papi-sync-status.${userId}`)
                        .listen('.papi.sync.status.updated', (e) => {
                            console.log('[Pusher] PAPI sync status update:', e);
                            @this.handleSyncStatusUpdate(e);
                        })
                        .listen('.papi.sync.progress.updated', (e) => {
                            console.log('[Pusher] PAPI sync progress update:', e);
                            @this.handleSyncProgressUpdate(e);
                        });

                    console.log('[Pusher] Listening to papi-sync-status.' + userId);
                } else {
                    console.warn('[Pusher] Echo is not available, falling back to polling');
                }
            @endif

            // Fallback polling jika Pusher tidak tersedia
            let pollingInterval = null;
            let isPollingActive = @js($isPolling);

            // Function to start/stop polling
            function updatePolling() {
                const syncStatus = @js($syncStatus);
                const shouldPoll = ['processing', 'queued'].includes(syncStatus) || isPollingActive;

                if (shouldPoll && !pollingInterval) {
                    console.log('[Polling] Starting PAPI polling interval');
                    pollingInterval = setInterval(() => {
                        @this.checkSyncStatus();
                    }, 3000); // Poll every 3 seconds
                } else if (!shouldPoll && pollingInterval) {
                    console.log('[Polling] Stopping PAPI polling interval');
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // Initial polling setup
            updatePolling();

            // Listen for Livewire events
            Livewire.on('syncPollingUpdated', ({ isPolling }) => {
                isPollingActive = isPolling;
                updatePolling();
            });

            // Listen for sync status changes
            Livewire.on('refreshData', () => {
                console.log('[Livewire] Refreshing PAPI data...');
                // Data will be refreshed automatically via Livewire
            });

            // Cleanup on page navigation
            document.addEventListener('livewire:navigating', () => {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            });

            // Auto-refresh page if sync completes while user is on page
            let refreshTimeout = null;

            Livewire.on('syncCompleted', () => {
                console.log('[Livewire] PAPI sync completed, refreshing in 2 seconds...');

                if (refreshTimeout) clearTimeout(refreshTimeout);

                refreshTimeout = setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
        });

        // Utility function untuk interpretasi score PAPI
        function interpretPapiScore(score, trait) {
            if (score === null || score === undefined || score === '-') return 'No data';

            const interpretations = {
                'N': { // Nervous vs Stable
                    low: [1, 3], moderate: [4, 6], high: [7, 10]
                },
                'G': { // Dominance
                    low: [1, 3], moderate: [4, 6], high: [7, 10]
                },
                'A': { // Sociability
                    low: [1, 3], moderate: [4, 6], high: [7, 10]
                },
                'L': { // Leadership
                    low: [1, 3], moderate: [4, 6], high: [7, 10]
                },
                'P': { // Confidence
                    low: [1, 3], moderate: [4, 6], high: [7, 10]
                }
            };

            if (!interpretations[trait]) return score;

            for (const [category, [min, max]] of Object.entries(interpretations[trait])) {
                if (score >= min && score <= max) {
                    return {
                        score: score,
                        category: category,
                        color: category === 'high' ? 'text-red-600' :
                               category === 'moderate' ? 'text-yellow-600' : 'text-green-600'
                    };
                }
            }

            return { score: score, category: 'unknown', color: 'text-gray-600' };
        }
    </script>
</div>
