<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Vacant Positions</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center gap-4">
            <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-white">
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
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Vacant Positions</h1>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Desktop Layout: Search & Filter -->
            <div class="hidden gap-4 md:grid md:grid-cols-4">
                <!-- Search Input-->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cari Posisi atau Kode</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Ketik nama posisi atau kode..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                    />
                </div>

                <!-- Filter Departemen -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Departemen</label>
                    <div class="relative">
                        <select
                            wire:model="filterDepartment"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                        >
                            <option value="">Semua Departemen</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>

                        <!-- Ikon dropdown -->
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
                            wire:model="filterUnit"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                        >
                            <option value="">Semua Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>

                        <!-- Ikon dropdown -->
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
                        wire:model.live="search"
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
                                wire:model="filterDepartment"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            >
                                <option value="">Semua Dept</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>

                            <!-- Icon dropdown -->
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
                                wire:model="filterUnit"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            >
                                <option value="">Semua Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                @endforeach
                            </select>

                            <!-- Icon dropdown -->
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

            <!-- Results Info -->
            <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                Menampilkan
                <span class="font-semibold">{{ $positions->count() }}</span>
                dari
                <span class="font-semibold">{{ $total }}</span>
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
                <div class="col-span-2 text-sm font-semibold">Kandidat</div>
            </div>

            <!-- List Items -->
            @forelse ($positions as $position)
                <!-- Desktop View -->
                <a
                    href="{{ route('vacant-positions.show') }}"
                    class="hidden rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid md:grid-cols-12 md:gap-4"
                >
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $position['name'] }}</p>
                    </div>
                    <div class="col-span-1 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $position['code'] }}</p>
                    </div>
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $position['unit'] }}</p>
                    </div>
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $position['department'] }}</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                        @if ($position['candidates'] > 0)
                            <div class="flex md:-space-x-6 lg:-space-x-2">
                                @for ($i = 0; $i < min($position['candidates'], 3); $i++)
                                    <img
                                        class="h-10 w-10 rounded-full border-2 border-white"
                                        src="https://avatar.iran.liara.run/public/{{ $i === 0 ? '' : ($i === 1 ? 'boy' : 'girl') }}"
                                        alt="candidate"
                                    />
                                @endfor

                                @if ($position['candidates'] > 3)
                                    <span
                                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-gray-700 text-xs font-medium text-white"
                                    >
                                        +{{ $position['candidates'] - 3 }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span
                                class="inline-flex items-center justify-center rounded-full bg-gray-100 px-3 py-1 text-center text-xs font-medium text-gray-700"
                            >
                                Tidak ada kandidat
                            </span>
                        @endif
                    </div>
                </a>

                <!-- Mobile View -->
                <a
                    href="{{ route('vacant-positions.show') }}"
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-3">
                        <div class="border-b border-gray-100 pb-3">
                            <p class="text-xs font-medium text-gray-500 uppercase">Nama Posisi</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $position['name'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Kode Posisi</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $position['code'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Unit Kerja</p>
                                <p class="mt-1 line-clamp-1 font-semibold text-gray-900">{{ $position['unit'] }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Departemen</p>
                            <p class="mt-1 line-clamp-1 font-semibold text-gray-900">{{ $position['department'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Kandidat</p>
                            @if ($position['candidates'] > 0)
                                <div class="mt-2 flex -space-x-2">
                                    @for ($i = 0; $i < min($position['candidates'], 3); $i++)
                                        <img
                                            class="h-8 w-8 rounded-full border-2 border-white"
                                            src="https://avatar.iran.liara.run/public/{{ $i === 0 ? '' : ($i === 1 ? 'boy' : 'girl') }}"
                                            alt="candidate"
                                        />
                                    @endfor

                                    @if ($position['candidates'] > 3)
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-gray-700 text-xs font-medium text-white"
                                        >
                                            +{{ $position['candidates'] - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span
                                    class="mt-2 inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700"
                                >
                                    Tidak ada kandidat
                                </span>
                            @endif
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
                    <p class="mt-4 font-medium text-gray-500">Tidak ada posisi yang ditemukan</p>
                    <p class="mt-1 text-sm text-gray-400">Coba ubah filter pencarian Anda</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($total > 10)
            <div class="mt-6 flex justify-center">
                <nav class="inline-flex items-center space-x-1 rounded-lg border border-gray-200">
                    @for ($i = 1; $i <= ceil($total / 10); $i++)
                        <a
                            href="?page={{ $i }}"
                            wire:navigate
                            class="{{ request()->query('page', 1) == $i ? 'border-r border-gray-200 bg-[#721d2110] text-[#721d21]' : 'text-gray-700 hover:bg-gray-50' }} px-3 py-2 text-sm font-medium"
                        >
                            {{ $i }}
                        </a>
                    @endfor
                </nav>
            </div>
        @endif
    </div>
</div>
