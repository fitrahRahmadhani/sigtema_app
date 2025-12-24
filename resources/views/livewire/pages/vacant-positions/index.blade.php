<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Vacant Positions</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="color-gradient flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-white"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="inline text-2xl font-bold text-gray-900 md:text-3xl">
                        Vacant Positions
                        <img src="{{ asset('assets/icon/logo-ai.svg') }}" class="mb-2 ml-2 inline h-8" alt="Logo AI" />
                    </h1>

                    <!-- Company Info with Change Button -->
                    @if ($isSuperadmin && $selectedCompany)
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                            <button
                                wire:click="changeCompany"
                                class="flex flex-shrink-0 items-center gap-1 rounded-lg bg-red-50 px-2 py-1 text-xs text-red-500 transition-colors hover:bg-red-100 hover:text-red-700"
                            >
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>
                                <span class="hidden sm:inline">Change</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Summary Cards -->
            @if ($selectedCompany && ! $showCompanySelection)
                <div class="flex flex-col items-end gap-3">
                    <!-- Tabs -->
                    <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1">
                        <button
                            wire:click="setTab('vacant')"
                            @class([
                                'rounded-md px-4 py-2 text-sm font-medium transition-colors duration-200',
                                'bg-white text-[#721d21] shadow-sm' => $activeTab === 'vacant',
                                'text-gray-600 hover:text-gray-900' => $activeTab !== 'vacant',
                            ])
                        >
                            Vacant
                            @if ($stats['vacant'] > 0)
                                <span class="ml-2 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                    {{ $stats['vacant'] }}
                                </span>
                            @endif
                        </button>
                        <button
                            wire:click="setTab('all')"
                            @class([
                                'rounded-md px-4 py-2 text-sm font-medium transition-colors duration-200',
                                'bg-white text-[#721d21] shadow-sm' => $activeTab === 'all',
                                'text-gray-600 hover:text-gray-900' => $activeTab !== 'all',
                            ])
                        >
                            All Positions
                            @if ($stats['total'] > 0)
                                <span
                                    class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                >
                                    {{ $stats['total'] }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- COMPANY SELECTION SECTION -->
        @if ($showCompanySelection)
            <div class="mb-8 rounded-lg border border-gray-200 bg-white p-8 text-center">
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
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                    />
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Pilih Perusahaan</h3>
                <p class="mt-2 text-gray-500">Silakan pilih perusahaan untuk melihat posisi kosong</p>

                <!-- Company List -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <button
                            wire:click="selectCompany({{ $company->id }})"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($company->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $company->logo_path) }}"
                                            alt="{{ 'Logo ' . $company->name }}"
                                        />
                                    @else
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6 text-gray-600"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                                            />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $company->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ \App\Models\Position::where('company_id', $company->id)->whereNull('employee_id')->count() }}
                                        posisi kosong
                                    </p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- MAIN CONTENT SECTION -->
        @if (! $showCompanySelection)
            @if ($selectedCompany)
                <!-- Search and Filter Section -->
                <div class="mb-8 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-600">
                        @if ($activeTab === 'vacant')
                            Menampilkan posisi kosong di
                        @else
                            Menampilkan semua posisi di
                        @endif
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>

                    <!-- Desktop Layout: Search & Filter -->
                    <div class="hidden gap-4 md:grid md:grid-cols-4">
                        <!-- Search Input-->
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Cari Posisi atau Kode</label>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Ketik nama posisi atau kode..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            />
                        </div>

                        <!-- Filter Departemen -->
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Departemen</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterDepartment"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">Semua Departemen</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
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

                        <!-- Filter Unit -->
                        <div class="relative">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Unit Kerja</label>
                            <div class="relative">
                                <select
                                    wire:model.live="filterUnit"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                >
                                    <option value="">Semua Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
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

                    <!-- Mobile Layout: Stacked -->
                    <div class="space-y-4 md:hidden">
                        <!-- Search Input -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Cari Posisi atau Kode</label>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Ketik nama posisi atau kode..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            />
                        </div>

                        <!-- Filter Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Departemen -->
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Departemen</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterDepartment"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">Semua Dept</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept }}">{{ $dept }}</option>
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

                            <!-- Unit Kerja -->
                            <div class="relative">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Unit Kerja</label>
                                <div class="relative">
                                    <select
                                        wire:model.live="filterUnit"
                                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                                    >
                                        <option value="">Semua Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}">{{ $unit }}</option>
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
                    </div>

                    <!-- Results Count -->
                    <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                        Menampilkan
                        <span class="font-semibold">{{ $positions->count() }}</span>
                        dari
                        <span class="font-semibold">{{ $positions->total() }}</span>
                        posisi
                    </div>
                </div>

                <!-- List Container -->
                <div class="space-y-3 overflow-x-auto">
                    <!-- Column Headers - Hidden on mobile -->
                    <div
                        class="color-gradient hidden place-items-center justify-items-start gap-4 rounded-lg px-4 py-3 text-white md:grid md:grid-cols-12"
                    >
                        <div class="col-span-3 text-sm font-semibold">Nama Posisi</div>
                        <div class="col-span-1 text-sm font-semibold">Kode Posisi</div>
                        <div class="col-span-3 text-sm font-semibold">Unit Kerja</div>
                        <div class="col-span-3 text-sm font-semibold">Departemen</div>
                        <div class="col-span-2 text-sm font-semibold">Status</div>
                    </div>

                    <!-- List Items -->
                    @forelse ($positions as $position)
                        <!-- Desktop View -->
                        <a
                            href="{{ route('vacant-positions.show', $position->slug) }}"
                            class="hidden rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid md:grid-cols-12 md:gap-4"
                        >
                            <div class="col-span-3 flex flex-col justify-center">
                                <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $position->name }}</p>
                            </div>
                            <div class="col-span-1 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ $position->position_code }}
                                </p>
                            </div>
                            <div class="col-span-3 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ $position->work_unit ?? '-' }}
                                </p>
                            </div>
                            <div class="col-span-3 flex flex-col justify-center">
                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">
                                    {{ $position->department ?? '-' }}
                                </p>
                            </div>
                            <div class="col-span-2 flex items-center">
                                @if ($position->employee_id)
                                    <span
                                        class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-center text-xs font-medium text-green-800"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Terisi
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center rounded-full bg-red-100 px-3 py-1 text-center text-xs font-medium text-red-800"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18 18 6M6 6l12 12"
                                            />
                                        </svg>
                                        Kosong
                                    </span>
                                @endif
                            </div>
                        </a>

                        <!-- Mobile View -->
                        <a
                            href="{{ route('vacant-positions.show', $position->id) }}"
                            class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                        >
                            <div class="space-y-3">
                                <div class="border-b border-gray-100 pb-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase">Nama Posisi</p>
                                            <p class="mt-1 font-semibold text-gray-900">{{ $position->name }}</p>
                                        </div>
                                        @if ($position->employee_id)
                                            <span
                                                class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-center text-xs font-medium text-green-800"
                                            >
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                Terisi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center rounded-full bg-red-100 px-3 py-1 text-center text-xs font-medium text-red-800"
                                            >
                                                <svg
                                                    class="mr-1 h-3 w-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                Kosong
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Kode Posisi</p>
                                        <p class="mt-1 font-semibold text-gray-900">{{ $position->position_code }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase">Unit Kerja</p>
                                        <p class="mt-1 line-clamp-1 font-semibold text-gray-900">
                                            {{ $position->work_unit ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Departemen</p>
                                    <p class="mt-1 line-clamp-1 font-semibold text-gray-900">
                                        {{ $position->department ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </a>
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
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                                />
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">
                                @if ($activeTab === 'vacant')
                                    Tidak ada posisi kosong yang ditemukan
                                @else
                                        Tidak ada posisi yang ditemukan
                                @endif
                            </p>
                            <p class="mt-1 text-sm text-gray-400">Coba ubah filter pencarian Anda</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($positions->hasPages())
                    <div class="mt-6">
                        {{ $positions->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
