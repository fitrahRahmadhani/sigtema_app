<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Innovations</flux:breadcrumbs.item>
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
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Innovations</h1>
                    <p class="mt-1 text-sm text-gray-500">View innovation data from external API</p>
                    <div class="mt-2 flex">
                        <p class="mt-2 text-sm italic">data powered by</p>
                        <img
                            src="{{ asset('assets/logo/logo-portal-inovasi-red.png') }}"
                            class="ml-2 h-7"
                            alt="Logo Portal Inovasi"
                        />
                    </div>
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
                                d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Innovations</p>
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
                                $latest = \App\Models\Innovation::latest('updated_at')->first();
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
                                <span class="ml-2 font-medium text-blue-800">Sync Queued - Waiting to start...</span>
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
                                <span class="ml-2 font-medium text-blue-800">Processing in Background...</span>
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
                                <span class="ml-2 font-medium text-green-800">Sync Completed Successfully</span>
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
                                            <p class="text-sm font-medium text-red-800">Sync Error</p>
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
            <div class="grid gap-4 md:grid-cols-3">
                {{-- Search Input --}}
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
                            placeholder="Search by innovation title, employee name or employee number..."
                            class="block w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 pl-10 text-sm placeholder-gray-500 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
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
            </div>

            {{-- Filter Row --}}
            <div class="grid gap-4 md:grid-cols-4">
                {{-- Team Filter --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Team</label>
                    <div class="relative">
                        <select
                            wire:model.live="teamFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Teams</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team }}">{{ $team }}</option>
                            @endforeach
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

                {{-- Category Filter --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <div class="relative">
                        <select
                            wire:model.live="categoryFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
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

                {{-- Replication Filter --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Replication</label>
                    <div class="relative">
                        <select
                            wire:model.live="replicationFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Replication</option>
                            @foreach ($replications as $replication)
                                <option value="{{ $replication }}">{{ ucfirst($replication) }}</option>
                            @endforeach
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
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 transition-colors focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Recently Added</option>
                            <option value="oldest">Oldest Added</option>
                            <option value="title_asc">Title (A-Z)</option>
                            <option value="title_desc">Title (Z-A)</option>
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
            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing {{ $innovations->count() }} of {{ $innovations->total() }} innovations
                        @if ($search)
                            <span class="text-gray-400">• Search: "{{ $search }}"</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Bulk Actions Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
                            >
                                <svg
                                    class="mr-2 h-4 w-4 text-gray-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                                    />
                                </svg>
                                Bulk Actions
                                <svg
                                    class="ml-2 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition duration-100 ease-out"
                                x-transition:enter-start="scale-95 transform opacity-0"
                                x-transition:enter-end="scale-100 transform opacity-100"
                                x-transition:leave="transition duration-75 ease-in"
                                x-transition:leave-start="scale-100 transform opacity-100"
                                x-transition:leave-end="scale-95 transform opacity-0"
                                class="ring-opacity-5 absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black focus:outline-none"
                                style="display: none"
                            >
                                <div class="py-1">
                                    <button
                                        wire:click="bulkActivate"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-green-50 hover:text-green-700"
                                    >
                                        <div class="flex items-center">
                                            <svg
                                                class="mr-2 h-4 w-4 text-green-600"
                                                xmlns="http://www.w3.org/2000/svg"
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
                                            Activate All Inactive
                                        </div>
                                    </button>
                                    <button
                                        wire:click="bulkDeactivate"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-red-50 hover:text-red-700"
                                    >
                                        <div class="flex items-center">
                                            <svg
                                                class="mr-2 h-4 w-4 text-red-600"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            Deactivate All Active
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Innovations Table --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            {{-- Table Header - Desktop --}}
            <div class="color-gradient hidden grid-cols-12 gap-4 bg-gradient-to-r px-6 py-3 text-white md:grid">
                <div class="col-span-4 text-sm font-semibold">Innovation & Employee</div>
                <div class="col-span-2 text-sm font-semibold">Team & Category</div>
                <div class="col-span-2 text-sm font-semibold">Theme & Event</div>
                <div class="col-span-2 text-sm font-semibold">Status & Achievement</div>
                <div class="col-span-1 text-sm font-semibold">Active</div>
                <div class="col-span-1 text-center text-sm font-semibold">Actions</div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-gray-200">
                @forelse ($innovations as $innovation)
                    {{-- Desktop View --}}
                    <div
                        class="{{ $innovation->is_active ? 'hover:bg-gray-50' : 'bg-gray-50 opacity-90 hover:bg-gray-100' }} hidden grid-cols-12 items-center gap-4 px-6 py-4 transition-colors duration-150 md:grid"
                        wire:key="innovation-{{ $innovation->id }}"
                    >
                        {{-- Innovation & Employee --}}
                        <div class="col-span-4">
                            <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $innovation->title }}</p>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                @if ($innovation->employee)
                                    <div class="flex items-center">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $innovation->employee->name }}</span>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                    >
                                        {{ $innovation->employee_number }}
                                    </span>
                                @elseif ($innovation->employee_number)
                                    <div class="flex items-center">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        <span class="text-sm text-gray-500">External Employee</span>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                    >
                                        {{ $innovation->employee_number }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 italic">No employee data</span>
                                @endif
                            </div>
                        </div>

                        {{-- Team & Category --}}
                        <div class="col-span-2 space-y-1">
                            <div class="flex items-center">
                                <svg
                                    class="mr-2 h-4 w-4 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $innovation->team ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="mr-2 h-4 w-4 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $innovation->category ?? 'N/A' }}</span>
                            </div>
                        </div>

                        {{-- Theme & Event --}}
                        <div class="col-span-2 space-y-1">
                            <div class="flex items-center">
                                <svg
                                    class="mr-2 h-4 w-4 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $innovation->theme ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="mr-2 h-4 w-4 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $innovation->event ?? 'N/A' }}</span>
                            </div>
                        </div>

                        {{-- Achievement --}}
                        <div class="col-span-2">
                            <div class="mb-2">
                                @if ($innovation->status)
                                    @php
                                        $statusColors = [
                                            'approved' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'draft' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusColor = $statusColors[strtolower($innovation->status)] ?? 'bg-gray-100 text-gray-800';
                                    @endphp

                                    <span
                                        class="{{ $statusColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    >
                                        {{ ucfirst($innovation->status) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-start">
                                @if ($innovation->achievement)
                                    <svg
                                        class="mt-0.5 mr-2 h-4 w-4 text-yellow-500"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11.048 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                        />
                                    </svg>
                                    <p class="line-clamp-2 text-sm text-gray-900">{{ $innovation->achievement }}</p>
                                @else
                                    <span class="text-sm text-gray-400 italic">No achievement</span>
                                @endif
                            </div>
                        </div>

                        {{-- Active Status --}}
                        <div class="col-span-1">
                            <button
                                wire:click="toggleStatus({{ $innovation->id }})"
                                class="transition-all duration-200 hover:scale-105 focus:outline-none"
                                title="{{ $innovation->is_active ? 'Deactivate' : 'Activate' }}"
                            >
                                @if ($innovation->is_active)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 hover:bg-green-200"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 hover:bg-red-200"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Inactive
                                    </span>
                                @endif
                            </button>
                        </div>

                        {{-- Actions --}}
                        <div class="col-span-1 flex items-center justify-center gap-2">
                            <button
                                wire:click="confirmDelete({{ $innovation->id }}, '{{ addslashes($innovation->title) }}')"
                                class="rounded-lg p-1.5 text-gray-600 transition-colors hover:bg-red-50 hover:text-red-600 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                title="Delete Innovation"
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
                        class="{{ $innovation->is_active ? '' : 'bg-gray-50 opacity-90' }} block p-4 md:hidden"
                        wire:key="innovation-mobile-{{ $innovation->id }}"
                    >
                        <div class="space-y-3">
                            {{-- Header --}}
                            <div class="border-b border-gray-100 pb-3">
                                <div class="flex items-start justify-between">
                                    <h3 class="line-clamp-2 font-semibold text-gray-900">{{ $innovation->title }}</h3>
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="toggleStatus({{ $innovation->id }})"
                                            class="focus:outline-none"
                                            title="{{ $innovation->is_active ? 'Deactivate' : 'Activate' }}"
                                        >
                                            @if ($innovation->is_active)
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
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @if ($innovation->employee)
                                        <div class="flex items-center">
                                            <svg
                                                class="mr-1 h-4 w-4 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            <span class="text-sm text-gray-700">
                                                {{ $innovation->employee->name }}
                                            </span>
                                        </div>
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                        >
                                            {{ $innovation->employee_number }}
                                        </span>
                                    @elseif ($innovation->employee_number)
                                        <div class="flex items-center">
                                            <svg
                                                class="mr-1 h-4 w-4 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                                />
                                            </svg>
                                            <span class="text-sm text-gray-500">External Employee</span>
                                        </div>
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                        >
                                            {{ $innovation->employee_number }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 italic">No employee data</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <p class="text-xs font-medium text-gray-500">Team</p>
                                    <p class="flex items-center text-sm text-gray-900">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            />
                                        </svg>
                                        {{ $innovation->team ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-medium text-gray-500">Category</p>
                                    <p class="flex items-center text-sm text-gray-900">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                            />
                                        </svg>
                                        {{ $innovation->category ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-medium text-gray-500">Theme</p>
                                    <p class="flex items-center text-sm text-gray-900">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                            />
                                        </svg>
                                        {{ $innovation->theme ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-medium text-gray-500">Event</p>
                                    <p class="flex items-center text-sm text-gray-900">
                                        <svg
                                            class="mr-1 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                        {{ $innovation->event ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Status & Achievement --}}
                            <div class="space-y-2">
                                @if ($innovation->status)
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Status</p>
                                        @php
                                            $statusColors = [
                                                'approved' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'submitted' => 'bg-blue-100 text-blue-800',
                                                'draft' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $statusColor = $statusColors[strtolower($innovation->status)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp

                                        <span
                                            class="{{ $statusColor }} mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        >
                                            {{ ucfirst($innovation->status) }}
                                        </span>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-xs font-medium text-gray-500">Achievement</p>

                                    @if ($innovation->achievement)
                                        <div class="mt-1 flex items-start">
                                            <svg
                                                class="mt-0.5 mr-2 h-4 w-4 text-yellow-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11.048 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                />
                                            </svg>
                                            <p class="text-sm text-gray-900">{{ $innovation->achievement }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400 italic">No achievement</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-3">
                                <button
                                    wire:click="confirmDelete({{ $innovation->id }}, '{{ addslashes($innovation->title) }}')"
                                    class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-red-50 hover:text-red-600 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                    title="Delete Innovation"
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
                                d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                            />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-500">No innovations found</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm text-gray-400">
                            @if ($search || $teamFilter || $categoryFilter || $replicationFilter || $statusFilter || $activeFilter !== 'all')
                                Try adjusting your filters or search terms.
                            @else
                                    Click "Sync from API" to load data from external source.
                            @endif
                        </p>
                        @if (! $search && ! $teamFilter && ! $categoryFilter && ! $replicationFilter && ! $statusFilter && $activeFilter === 'all')
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
        @if ($innovations->hasPages())
            <div class="mt-6">
                {{ $innovations->onEachSide(1)->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            // Setup Echo/Pusher listeners
            @if(auth()->check())
                const userId = {{ auth()->id() }};

                // Check if Echo is available
                if (typeof window.Echo !== 'undefined') {
                    // Listen for sync status updates via Echo
                    window.Echo.private(`sync-status.${userId}`)
                        .listen('.sync.status.updated', (e) => {
                            console.log('[Pusher] Sync status update:', e);
                            @this.handleSyncStatusUpdate(e);
                        })
                        .listen('.sync.progress.updated', (e) => {
                            console.log('[Pusher] Sync progress update:', e);
                            @this.handleSyncProgressUpdate(e);
                        });

                    console.log('[Pusher] Listening to sync-status.' + userId);
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
                    console.log('[Polling] Starting polling interval');
                    pollingInterval = setInterval(() => {
                        @this.checkSyncStatus();
                    }, 3000); // Poll every 3 seconds
                } else if (!shouldPoll && pollingInterval) {
                    console.log('[Polling] Stopping polling interval');
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
                console.log('[Livewire] Refreshing data...');
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
                console.log('[Livewire] Sync completed, refreshing in 2 seconds...');

                if (refreshTimeout) clearTimeout(refreshTimeout);

                refreshTimeout = setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
        });

        // Utility function to format numbers
        function formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        // Add some CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes pulse-ring {
                0% { transform: scale(0.95); opacity: 0.7; }
                100% { transform: scale(1.1); opacity: 0; }
            }

            .animate-pulse-ring {
                position: relative;
            }

            .animate-pulse-ring::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                border-radius: inherit;
                background: currentColor;
                animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                z-index: -1;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    </script>
</div>
