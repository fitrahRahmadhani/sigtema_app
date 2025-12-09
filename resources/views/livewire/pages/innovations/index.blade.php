{{-- resources/views/livewire/pages/innovations/index.blade.php --}}
<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Innovations</flux:breadcrumbs.item>
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
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Innovations</h1>
                    <p class="mt-1 text-sm text-gray-500">View innovation data from external API</p>
                </div>
            </div>

            <!-- Sync Button -->
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
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
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
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Innovations</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Innovation::count() }}</p>
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
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Matched Employees</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Innovation::whereNotNull('employee_id')->count() }}
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
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
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

        <!-- Sync Status Panel -->
        <div class="mb-6 space-y-4">
            <!-- Status Badge -->
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
                    }} rounded-lg border p-4"
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

                    <!-- Progress Bar (for processing) -->
                    @if ($syncStatus === 'processing')
                        <div class="mt-2">
                            <div class="h-1 w-full rounded-full bg-blue-200">
                                <div class="h-1 animate-pulse rounded-full bg-blue-600" style="width: 60%"></div>
                            </div>
                            <p class="mt-1 text-xs text-blue-600">
                                Processing in background... This page will auto-refresh
                            </p>
                        </div>
                    @endif

                    <!-- Result Details -->
                    @if ($lastSyncResult && ($syncStatus === 'completed' || $syncStatus === 'failed'))
                        <div class="mt-3 border-t pt-3">
                            @if ($syncStatus === 'completed')
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Created:</span>
                                        <span class="ml-2 font-medium text-green-700">
                                            {{ $lastSyncResult['created'] ?? 0 }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Updated:</span>
                                        <span class="ml-2 font-medium text-blue-700">
                                            {{ $lastSyncResult['updated'] ?? 0 }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Total:</span>
                                        <span class="ml-2 font-medium">{{ $lastSyncResult['total'] ?? 0 }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="ml-2">
                                            @if (isset($lastSyncResult['started_at']) && isset($lastSyncResult['completed_at']))
                                                {{ \Carbon\Carbon::parse($lastSyncResult['started_at'])->diffForHumans(\Carbon\Carbon::parse($lastSyncResult['completed_at']), true) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-red-600">
                                    Error: {{ $lastSyncResult['error'] ?? 'Unknown error' }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Auto-refresh script -->
            @if ($polling)
                <script>
                    // Auto-refresh setiap 5 detik saat polling aktif
                    setInterval(() => {
                        @this.checkSyncStatus();
                    }, 5000);
                </script>
            @endif
        </div>

        <!-- Filters -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Search Row -->
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search Innovation</label>
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
                            placeholder="Search by title, employee, team, or theme..."
                            class="block w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 pl-10 text-sm placeholder-gray-500 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Recently Added</option>
                            <option value="oldest">Oldest Added</option>
                            <option value="title_asc">Title (A-Z)</option>
                            <option value="title_desc">Title (Z-A)</option>
                            <option value="team_asc">Team (A-Z)</option>
                            <option value="team_desc">Team (Z-A)</option>
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

            <!-- Filter Row -->
            <div class="grid gap-4 md:grid-cols-4">
                <!-- Team Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Team</label>
                    <div class="relative">
                        <select
                            wire:model.live="teamFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
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

                <!-- Category Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <div class="relative">
                        <select
                            wire:model.live="categoryFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
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

                <!-- Replication Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Replication</label>
                    <div class="relative">
                        <select
                            wire:model.live="replicationFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
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

                <!-- Status Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                    <div class="relative">
                        <select
                            wire:model.live="statusFilter"
                            class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
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
            </div>

            <!-- Results Info -->
            <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                Showing {{ $innovations->count() }} of {{ $innovations->total() }} innovations
            </div>
        </div>

        <!-- Innovations Table TABLE -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Desktop -->
            <div
                class="color-gradient hidden grid-cols-12 gap-4 rounded-lg bg-gradient-to-r px-4 py-3 text-white md:grid"
            >
                <div class="col-span-3 text-sm font-semibold">Innovation & Employee</div>
                <div class="col-span-2 text-sm font-semibold">Team & Category</div>
                <div class="col-span-2 text-sm font-semibold">Theme & Event</div>
                <div class="col-span-2 text-sm font-semibold">Status & Replication</div>
                <div class="col-span-2 text-sm font-semibold">Achievement & Source</div>
                <div class="col-span-1 text-center text-sm font-semibold">Updated</div>
            </div>

            <!-- List Items -->
            @forelse ($innovations as $innovation)
                <!-- Desktop View -->
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    <!-- Innovation & Employee -->
                    <div class="col-span-3">
                        <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $innovation->title }}</p>
                        <div class="mt-1">
                            @if ($innovation->employee)
                                <p class="truncate text-sm text-gray-700">{{ $innovation->employee->name }}</p>
                                <span
                                    class="mt-1 inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                >
                                    {{ $innovation->employee_number }}
                                </span>
                            @elseif ($innovation->employee_number)
                                <p class="text-sm text-gray-500">External Employee</p>
                                <span
                                    class="mt-1 inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                >
                                    {{ $innovation->employee_number }}
                                </span>
                            @else
                                <p class="text-sm text-gray-400 italic">No employee data</p>
                            @endif
                        </div>
                    </div>

                    <!-- Team & Category -->
                    <div class="col-span-2">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Team:</span>
                            {{ $innovation->team ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Category:</span>
                            {{ $innovation->category ?? '-' }}
                        </p>
                    </div>

                    <!-- Theme & Event -->
                    <div class="col-span-2">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Theme:</span>
                            {{ $innovation->theme ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Event:</span>
                            {{ $innovation->event ?? '-' }}
                        </p>
                    </div>

                    <!-- Status & Replication -->
                    <div class="col-span-2">
                        @if ($innovation->status)
                            <div class="mb-1">
                                <span
                                    class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700"
                                >
                                    Status: {{ ucfirst($innovation->status) }}
                                </span>
                            </div>
                        @endif

                        @if ($innovation->replication)
                            <div>
                                <span
                                    class="{{
                                        str_contains(strtolower($innovation->replication), 'bisa')
                                            ? 'bg-green-50 text-green-700'
                                            : (str_contains(strtolower($innovation->replication), 'tidak')
                                                ? 'bg-red-50 text-red-700'
                                                : 'bg-yellow-50 text-yellow-700')
                                    }} inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                >
                                    Replication: {{ ucfirst($innovation->replication) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Achievement & Source -->
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $innovation->achievement ?? 'None' }}
                        </p>
                    </div>

                    <!-- Updated -->
                    <div class="col-span-1">
                        <p class="text-center text-xs text-gray-500">
                            {{ $innovation->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <!-- Mobile View -->
                <div
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-3">
                        <!-- Header -->
                        <div class="border-b border-gray-100 pb-3">
                            <h3 class="font-semibold text-gray-900">{{ $innovation->title }}</h3>
                            <div class="mt-2">
                                @if ($innovation->employee)
                                    <p class="text-sm text-gray-700">{{ $innovation->employee->name }}</p>
                                    <span
                                        class="mt-1 inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                    >
                                        {{ $innovation->employee_number }}
                                    </span>
                                @elseif ($innovation->employee_number)
                                    <p class="text-sm text-gray-500">External: {{ $innovation->employee_number }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Team</p>
                                <p class="text-sm text-gray-900">{{ $innovation->team ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Category</p>
                                <p class="text-sm text-gray-900">{{ $innovation->category ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Theme</p>
                                <p class="text-sm text-gray-900">{{ $innovation->theme ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Event</p>
                                <p class="text-sm text-gray-900">{{ $innovation->event ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Status & Replication -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Status</p>
                                @if ($innovation->status)
                                    <span
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700"
                                    >
                                        {{ ucfirst($innovation->status) }}
                                    </span>
                                @else
                                    <p class="text-sm text-gray-400 italic">-</p>
                                @endif
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Replication</p>
                                @if ($innovation->replication)
                                    <span
                                        class="{{
                                            str_contains(strtolower($innovation->replication), 'bisa')
                                                ? 'bg-green-50 text-green-700'
                                                : (str_contains(strtolower($innovation->replication), 'tidak')
                                                    ? 'bg-red-50 text-red-700'
                                                    : 'bg-yellow-50 text-yellow-700')
                                        }} inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                    >
                                        {{ ucfirst($innovation->replication) }}
                                    </span>
                                @else
                                    <p class="text-sm text-gray-400 italic">-</p>
                                @endif
                            </div>
                        </div>

                        <!-- Achievement & Source -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500">Achievement</p>
                                <p class="text-sm text-gray-900">{{ $innovation->achievement ?? 'None' }}</p>
                            </div>
                        </div>

                        <!-- Updated -->
                        <div class="border-t border-gray-100 pt-3 text-center">
                            <p class="text-xs text-gray-500">
                                Updated: {{ $innovation->updated_at->diffForHumans() }}
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
                            d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No innovations found</p>
                    <p class="mt-1 text-sm text-gray-400">
                        @if ($search || $teamFilter || $categoryFilter)
                            Try adjusting your filters or
                        @endif
                        Click "Sync from API" to load data
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($innovations->hasPages())
            <div class="mt-6">
                {{ $innovations->links() }}
            </div>
        @endif
    </div>
</div>
