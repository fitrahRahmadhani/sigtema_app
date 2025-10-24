<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employees Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100">
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
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                    />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employees Profile</h1>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <!-- Desktop Layout: Search & Filter -->
            <div class="hidden gap-4 md:grid md:grid-cols-4">
                <!-- Search Input-->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cari Nama atau Employee ID</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Ketik nama atau employee ID..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                    />
                </div>

                <!-- Filter Gender -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                    <div class="relative">
                        <select
                            wire:model="filterGender"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                        >
                            <option value="">Semua Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
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
                    <label class="mb-2 block text-sm font-medium text-gray-700">Unit</label>
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
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cari Nama atau Employee ID</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Ketik nama atau employee ID..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                    />
                </div>

                <!-- Filter Row -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Gender -->
                    <div class="relative">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                        <div class="relative">
                            <select
                                wire:model="filterGender"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none"
                            >
                                <option value="">Semua</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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

                    <!-- Unit -->
                    <div class="relative">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Unit</label>
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
                <span class="font-semibold">{{ $employees->count() }}</span>
                dari
                <span class="font-semibold">{{ $total }}</span>
                karyawan
            </div>
        </div>

        <!-- List Container -->
        <div class="space-y-3 overflow-x-auto">
            <!-- Column Headers - Hidden on mobile -->
            <div
                class="color-gradient hidden place-items-center justify-items-start gap-4 rounded-lg px-4 py-3 text-white md:grid md:grid-cols-12"
            >
                <div class="col-span-3 text-sm font-semibold">Nama</div>
                <div class="col-span-2 text-sm font-semibold">Employee ID</div>
                <div class="col-span-1 text-sm font-semibold">Gender</div>
                <div class="col-span-1 text-sm font-semibold">JG/PG</div>
                <div class="col-span-3 text-sm font-semibold">Posisi</div>
                <div class="col-span-2 text-sm font-semibold">Unit</div>
            </div>

            <!-- List Items -->
            @forelse ($employees as $employee)
                <!-- Desktop View -->
                <a
                    href="{{ route('employees-profile.show') }}"
                    class="hidden rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid md:grid-cols-12 md:gap-4"
                >
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $employee['name'] }}</p>
                    </div>
                    <div class="col-span-2 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $employee['employee_id'] }}</p>
                    </div>
                    <div class="col-span-1 flex flex-col justify-center">
                        <span
                            class="{{ $employee['gender'] === 'Male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} inline-flex w-fit items-center rounded-full px-2 py-1 text-xs font-medium"
                        >
                            {{ $employee['gender'] }}
                        </span>
                    </div>
                    <div class="col-span-1 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $employee['jg_pg'] }}</p>
                    </div>
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $employee['position'] }}</p>
                    </div>
                    <div class="col-span-2 flex flex-col justify-center">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $employee['unit'] }}</p>
                    </div>
                </a>

                <!-- Mobile View -->
                <a
                    href="{{ route('employees-profile.show') }}"
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-3">
                        <div class="border-b border-gray-100 pb-3">
                            <p class="text-xs font-medium text-gray-500 uppercase">Nama</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $employee['name'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Employee ID</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $employee['employee_id'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Gender</p>
                                <span
                                    class="{{ $employee['gender'] === 'Male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} mt-1 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                >
                                    {{ $employee['gender'] }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">JG/PG</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $employee['jg_pg'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Unit</p>
                                <p class="mt-1 line-clamp-1 font-semibold text-gray-900">{{ $employee['unit'] }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Posisi</p>
                            <p class="mt-1 line-clamp-1 font-semibold text-gray-900">{{ $employee['position'] }}</p>
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
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">Tidak ada karyawan yang ditemukan</p>
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
