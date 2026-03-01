<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employee Comparison</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
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
                            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employee Comparison</h1>

                    {{-- Company Info with Change Button --}}
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

            {{-- Add Column Button --}}
            @if (! $showCompanySelection && $columnCount < 5)
                <button
                    wire:click="addColumn"
                    class="color-gradient flex cursor-pointer items-center gap-2 rounded-lg px-4 py-2 font-medium text-white transition focus:ring-2 focus:ring-red-900 focus:ring-offset-2 focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="hidden md:inline">Add Column</span>
                </button>
            @endif
        </div>

        {{-- COMPANY SELECTION SECTION --}}
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
                <p class="mt-2 text-gray-500">Silakan pilih perusahaan untuk membandingkan karyawan</p>

                {{-- Company List --}}
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
                                    <p class="text-sm text-gray-500">{{ $company->employees_count ?? 0 }} karyawan</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- COMPARISON TABLE SECTION --}}
        @if (! $showCompanySelection)
            @if ($selectedCompany)
                {{-- Info Box --}}
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="text-sm text-gray-600">
                        Membandingkan karyawan di
                        <span class="font-semibold">{{ $selectedCompany->name }}</span>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                    <div class="flex flex-col items-start gap-2 text-sm text-yellow-600">
                        <div class="flex items-center gap-2 text-base">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <h6 class="font-semibold">Informasi</h6>
                        </div>
                        <p>
                            <span class="font-semibold">Jika chart (bagan) tidak muncul,</span>
                            mohon untuk refresh halaman
                        </p>
                        @hasrole('head-of-unit')
                            <p>
                                Anda hanya dapat mengkomparasi karyawan
                                <span class="font-semibold">selevel atau di bawah band Anda</span>
                            </p>
                        @endhasrole
                    </div>
                </div>

                {{-- Comparison Table --}}
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="left-0 w-64 bg-gray-50 px-6 py-3 text-left text-sm font-medium tracking-wider text-gray-500 uppercase"
                                    ></th>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <th scope="col" class="relative w-[400px] content-start bg-gray-50 px-6 py-3">
                                            {{-- Remove Column Button --}}
                                            @if ($columnCount > 3)
                                                <button
                                                    wire:click="removeColumn({{ $i }})"
                                                    class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600 transition hover:bg-red-200"
                                                    title="Remove column"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                        stroke="currentColor"
                                                        class="size-3"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M6 18 18 6M6 6l12 12"
                                                        />
                                                    </svg>
                                                </button>
                                            @endif

                                            <div class="relative">
                                                {{-- Search Input --}}
                                                <div class="relative w-[400px]">
                                                    <input
                                                        type="text"
                                                        wire:model.live.debounce.300ms="searchTerms.{{ $i }}"
                                                        placeholder="Search employee {{ $i + 1 }}..."
                                                        class="w-full rounded-lg border border-gray-300 py-2 pr-10 pl-10 focus:border-red-900 focus:ring-2 focus:ring-red-900 focus:outline-none"
                                                        @if(!empty($selectedEmployees[$i])) readonly @endif
                                                    />
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="size-4 text-gray-400"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                                            />
                                                        </svg>
                                                    </div>
                                                    @if (! empty($selectedEmployees[$i]))
                                                        <button
                                                            wire:click="clearEmployee({{ $i }})"
                                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-600"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="currentColor"
                                                                class="size-4"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M6 18 18 6M6 6l12 12"
                                                                />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- Search Dropdown --}}
                                                @if ($showSearchDropdown[$i] && ! empty($searchResults[$i]))
                                                    <div
                                                        class="absolute top-full right-0 left-0 mt-1 max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                                                    >
                                                        @foreach ($searchResults[$i] as $employee)
                                                            <button
                                                                wire:click="selectEmployee({{ $employee->id }}, {{ $i }})"
                                                                class="flex w-full items-center gap-3 border-b border-gray-100 px-4 py-3 text-left transition last:border-b-0 hover:bg-gray-50"
                                                            >
                                                                <div class="min-w-0 flex-1">
                                                                    <p class="font-medium text-gray-900">
                                                                        {{ $employee->name }}
                                                                    </p>
                                                                    <p class="truncate text-sm text-gray-500">
                                                                        {{ $employee->position?->name ?? 'No Position' }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-400">
                                                                        {{ $employee->employee_number }}
                                                                    </p>
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Selected Employee Info --}}
                                                @if (! empty($selectedEmployees[$i]))
                                                    <div
                                                        class="border-primary-text bg-primary-text/10 mt-3 flex w-[400px] items-center gap-3 rounded-lg border p-3"
                                                    >
                                                        <div
                                                            class="bg-primary-text/20 text-primary-text flex h-12 w-12 items-center justify-center place-self-start overflow-hidden rounded-lg object-cover"
                                                        >
                                                            @if ($selectedEmployees[$i]->profile_path)
                                                                <img
                                                                    src="{{ asset('storage/' . $selectedEmployees[$i]->profile_path) }}"
                                                                    alt="Foto Profile {{ $selectedEmployees[$i]->name }}"
                                                                />
                                                            @else
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24"
                                                                    fill="currentColor"
                                                                    class="size-8"
                                                                >
                                                                    <path
                                                                        fill-rule="evenodd"
                                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                                        clip-rule="evenodd"
                                                                    />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1 text-start">
                                                            <p class="text-primary-text font-semibold">
                                                                {{ $selectedEmployees[$i]->name }}
                                                            </p>
                                                            <p class="truncate text-sm text-gray-600">
                                                                {{ $selectedEmployees[$i]->position?->name ?? 'No Position' }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $selectedEmployees[$i]->employee_number }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                {{-- Basic Information Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        Basic Information
                                    </td>
                                </tr>

                                {{-- Email --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Email</td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <span class="font-medium">{{ $selectedEmployees[$i]->email }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Phone --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Phone</td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <span class="font-medium">{{ $selectedEmployees[$i]->phone }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Personal Grade --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Personal Grade
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <span class="font-medium">
                                                    {{ $selectedEmployees[$i]->personal_grade }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Band --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Band</td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <span class="font-medium">{{ $selectedEmployees[$i]->band }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Hire Date --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Hire Date</td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <span class="text-gray-400 italic">
                                                    {{ $selectedEmployees[$i]->hire_date?->format('d F Y') ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Personality Information Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">Personality Information</p>
                                        <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                        <img
                                            src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                            class="mb-1 ml-2 inline h-6 self-end"
                                            alt="Logo SIGMATIC"
                                        />
                                    </td>
                                </tr>

                                {{-- MBTI --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">MBTI</td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]->mbti->mbti))
                                                <span class="font-medium">
                                                    {{ $selectedEmployees[$i]->mbti->mbti }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- PAPI Kostick Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">PAPI Kostick</p>
                                        <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                        <img
                                            src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                            class="mb-1 ml-2 inline h-6 self-end"
                                            alt="Logo SIGMATIC"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top">
                                            <div class="flex w-[400px] flex-col items-center justify-start">
                                                {{-- PAPI Chart Container --}}
                                                <div
                                                    class="relative aspect-square w-[400px] rounded-full border-2 border-dashed border-gray-300"
                                                >
                                                    @if (! empty($selectedEmployees[$i]))
                                                        <canvas
                                                            id="papiChart-{{ $i }}"
                                                            class="h-full w-full"
                                                            data-papi-data="{{ json_encode($papiChartData[$i]) }}"
                                                            data-employee-name="{{ $selectedEmployees[$i]->name ?? 'Employee' }}"
                                                        ></canvas>
                                                    @else
                                                        {{-- Placeholder ketika tidak ada karyawan yang dipilih --}}
                                                        <div
                                                            class="flex h-full w-full flex-col items-center justify-center"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-12 w-12 text-gray-300"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"
                                                                />
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"
                                                                />
                                                            </svg>
                                                            <p class="mt-2 text-sm text-gray-300">Select an employee</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    @endfor
                                </tr>

                                {{-- DISC Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">DISC</p>
                                        <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                        <img
                                            src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                                            class="mb-1 ml-2 inline h-6 self-end"
                                            alt="Logo SIGMATIC"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top">
                                            @if (! empty($selectedEmployees[$i]) && ! empty($discChartData[$i]))
                                                <div class="flex w-[400px] flex-col space-y-6">
                                                    {{-- DISC 1 --}}
                                                    <div class="overflow-hidden rounded-lg border border-gray-300">
                                                        <div class="bg-red-500 px-3 py-2 text-center">
                                                            <p class="text-sm font-semibold text-white">DISC 1</p>
                                                        </div>
                                                        <div
                                                            class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                        >
                                                            <canvas
                                                                id="disc1Chart-{{ $i }}"
                                                                class="h-full w-full"
                                                                data-disc-data="{{
                                                                    json_encode([
                                                                        'D' => $discChartData[$i]['D1'],
                                                                        'I' => $discChartData[$i]['I1'],
                                                                        'S' => $discChartData[$i]['S1'],
                                                                        'C' => $discChartData[$i]['C1'],
                                                                    ])
                                                                }}"
                                                            ></canvas>
                                                        </div>
                                                        <div
                                                            class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                        >
                                                            <p
                                                                class="text-center text-sm leading-relaxed text-gray-700"
                                                            >
                                                                {{ $discChartData[$i]['interpretation1'] }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- DISC 2 --}}
                                                    <div class="overflow-hidden rounded-lg border border-gray-300">
                                                        <div class="bg-yellow-500 px-3 py-2 text-center">
                                                            <p class="text-sm font-semibold text-white">DISC 2</p>
                                                        </div>
                                                        <div
                                                            class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                        >
                                                            <canvas
                                                                id="disc2Chart-{{ $i }}"
                                                                class="h-full w-full"
                                                                data-disc-data="{{
                                                                    json_encode([
                                                                        'D' => $discChartData[$i]['D2'],
                                                                        'I' => $discChartData[$i]['I2'],
                                                                        'S' => $discChartData[$i]['S2'],
                                                                        'C' => $discChartData[$i]['C2'],
                                                                    ])
                                                                }}"
                                                            ></canvas>
                                                        </div>
                                                        <div
                                                            class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                        >
                                                            <p
                                                                class="text-center text-sm leading-relaxed text-gray-700"
                                                            >
                                                                {{ $discChartData[$i]['interpretation2'] }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- DISC 3 --}}
                                                    <div class="overflow-hidden rounded-lg border border-gray-300">
                                                        <div class="bg-green-500 px-3 py-2 text-center">
                                                            <p class="text-sm font-semibold text-white">DISC 3</p>
                                                        </div>
                                                        <div
                                                            class="relative flex min-h-[150px] items-center justify-center border-x border-gray-300 bg-white p-4"
                                                        >
                                                            <canvas
                                                                id="disc3Chart-{{ $i }}"
                                                                class="h-full w-full"
                                                                data-disc-data="{{
                                                                    json_encode([
                                                                        'D' => $discChartData[$i]['D3'],
                                                                        'I' => $discChartData[$i]['I3'],
                                                                        'S' => $discChartData[$i]['S3'],
                                                                        'C' => $discChartData[$i]['C3'],
                                                                    ])
                                                                }}"
                                                            ></canvas>
                                                        </div>
                                                        <div
                                                            class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-4 py-3"
                                                        >
                                                            <p
                                                                class="text-center text-sm leading-relaxed text-gray-700"
                                                            >
                                                                {{ $discChartData[$i]['interpretation3'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex w-[400px] flex-col items-center justify-center py-8">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-12 w-12 text-gray-300"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"
                                                        />
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-300">No DISC data</p>
                                                </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Soft Competencies Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        Soft Competencies
                                    </td>
                                </tr>

                                {{-- Strengths --}}
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Strengths
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <div class="flex items-center gap-2">
                                                    <ol class="ml-4 list-disc">
                                                        @forelse ($selectedEmployees[$i]->soft_competencies['strengths'] as $strength)
                                                            <li>{{ $strength->aspect->aspect_name }}</li>
                                                        @empty
                                                            <li>-</li>
                                                        @endforelse
                                                    </ol>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Weaknesses --}}
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Weaknesses
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 text-gray-700">
                                            @if (! empty($selectedEmployees[$i]))
                                                <div class="flex items-center gap-2">
                                                    <ol class="ml-4 list-disc">
                                                        @forelse ($selectedEmployees[$i]->soft_competencies['weaknesses'] as $strength)
                                                            <li>{{ $strength->aspect->aspect_name }}</li>
                                                        @empty
                                                            <li>-</li>
                                                        @endforelse
                                                    </ol>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Technical Competency Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">Technical Competency</p>
                                        <p class="ml-2 inline text-sm text-white italic">
                                            data powered by
                                            <span class="font-bold">LSP SIG</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->technicalCompetencies && $selectedEmployees[$i]->technicalCompetencies->count() > 0)
                                                <ul class="space-y-3">
                                                    @foreach ($selectedEmployees[$i]->technicalCompetencies as $skill)
                                                        <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                            <p class="font-medium text-gray-900">{{ $skill->name }}</p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $skill->issued_by }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $skill->year }}
                                                            </p>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">
                                                    {{ ! empty($selectedEmployees[$i]) ? 'N/A' : '-' }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Innovation Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">Innovation</p>
                                        <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                        <img
                                            src="{{ asset('assets/logo/logo-portal-inovasi.png') }}"
                                            class="mb-1 ml-2 inline h-6 self-end"
                                            alt="Logo Portal Inovasi"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->innovations && $selectedEmployees[$i]->innovations->count() > 0)
                                                <ul class="w-[400px] space-y-3">
                                                    @foreach ($selectedEmployees[$i]->innovations as $innovation)
                                                        <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                            <div class="mb-1 flex items-start justify-between gap-2">
                                                                <p class="flex-1 font-medium text-gray-900">
                                                                    {{ $innovation->title }}
                                                                </p>
                                                                @if ($innovation->status)
                                                                    <span
                                                                        class="{{ $innovation->status === 'Peserta' ? 'bg-gray-200 text-gray-800' : '' }} {{ preg_match('/^Juara\s.+/i', $innovation->status) ? 'bg-yellow-100 text-yellow-800' : '' }} inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                                                    >
                                                                        {{ ucfirst($innovation->status) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if ($innovation->event)
                                                                <p class="text-sm text-gray-600">
                                                                    {{ $innovation->event }}
                                                                </p>
                                                            @endif

                                                            @if ($innovation->achievement)
                                                                <p class="mt-1 text-sm font-medium text-amber-700">
                                                                    🏆 {{ $innovation->achievement }}
                                                                </p>
                                                            @endif

                                                            @if ($innovation->category || $innovation->theme)
                                                                <p class="mt-1 text-sm text-gray-500">
                                                                    {{ $innovation->category ? $innovation->category : '' }}
                                                                    {{ $innovation->category && $innovation->theme ? ' • ' : '' }}
                                                                    {{ $innovation->theme ? $innovation->theme : '' }}
                                                                </p>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">
                                                    {{ ! empty($selectedEmployees[$i]) ? 'N/A' : '-' }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Position History Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        Position History
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->positionHistories && $selectedEmployees[$i]->positionHistories->count() > 0)
                                                <ul class="space-y-3">
                                                    @if ($selectedEmployees[$i]->position)
                                                        <li class="border-l-2 border-gray-500 pl-3">
                                                            <p class="font-medium text-gray-900">
                                                                {{ $selectedEmployees[$i]->position->name }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $selectedEmployees[$i]->position->company->name }}
                                                            </p>
                                                            <p class="text-sm text-gray-400">Current</p>
                                                        </li>
                                                    @endif

                                                    @foreach ($selectedEmployees[$i]->positionHistories as $position)
                                                        <li class="border-l-2 border-gray-500 pl-3">
                                                            <p class="font-medium text-gray-900">
                                                                {{ $position->position_name }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $position->company }}
                                                            </p>
                                                            <p class="text-sm text-gray-400">
                                                                {{ $position->formatted_period }}
                                                            </p>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Education Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        Education
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->educations && $selectedEmployees[$i]->educations->count() > 0)
                                                <ul class="space-y-3">
                                                    @foreach ($selectedEmployees[$i]->educations as $education)
                                                        <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                            <p class="font-medium text-gray-900">
                                                                {{ $education->degree }} - {{ $education->major }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $education->institution_name }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $education->formatted_period }}
                                                            </p>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">
                                                    {{ ! empty($selectedEmployees[$i]) ? 'N/A' : '-' }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- MCU History Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">MCU History</p>
                                        <p class="ml-2 inline text-sm text-white italic">
                                            data powered by
                                            <span class="font-bold">Unit of Tuban & Gresik SHE</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-0 w-[400px] bg-white px-6 py-4 font-medium text-gray-900"></td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->mcuResults && $selectedEmployees[$i]->mcuResults->count() > 0)
                                                <div class="flex flex-col gap-1">
                                                    @foreach ($selectedEmployees[$i]->mcuResults as $mcu)
                                                        @php
                                                            $maxStars = 5;
                                                            $filledStars = min($mcu->result ?? 0, $maxStars);
                                                        @endphp

                                                        <div class="flex items-center justify-start gap-2">
                                                            {{-- Tahun --}}
                                                            <span
                                                                class="rounded-full bg-gray-100 px-2 py-1 text-sm text-gray-800"
                                                            >
                                                                {{ $mcu->year }}
                                                            </span>

                                                            {{-- Bintang --}}
                                                            <div class="flex gap-1">
                                                                @for ($s = 0; $s < $filledStars; $s++)
                                                                    <span class="text-xl text-yellow-500">★</span>
                                                                @endfor

                                                                @for ($s = $filledStars; $s < $maxStars; $s++)
                                                                    <span class="text-xl text-gray-300">★</span>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">
                                                    {{ ! empty($selectedEmployees[$i]) ? 'No MCU data' : '-' }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Burnout Section --}}
                                <tr class="color-gradient">
                                    <td
                                        colspan="{{ $columnCount + 1 }}"
                                        class="left-0 px-6 py-3 font-semibold text-white"
                                    >
                                        <p class="inline">Burnout Test Results</p>
                                        <p class="ml-2 inline text-sm text-white italic">data powered by</p>
                                        <img
                                            src="{{ asset('assets/logo/logo-sighap-white.png') }}"
                                            class="mb-1 ml-2 inline h-5 self-end"
                                            alt="Logo SIGHAP"
                                        />
                                    </td>
                                </tr>

                                {{-- Kelalahan Emosional --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Kelalahan Emosional
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]->burnout->emotional_exhaustion))
                                                <span class="font-medium">
                                                    {{ $selectedEmployees[$i]->burnout->emotional_exhaustion }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Depersonalisasi --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Depersonalisasi
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]->burnout->depersonalization))
                                                <span class="font-medium">
                                                    {{ $selectedEmployees[$i]->burnout->depersonalization }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Penurunan Prestasi --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Penurunan Prestasi
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]->burnout->achievement_decline))
                                                <span class="font-medium">
                                                    {{ $selectedEmployees[$i]->burnout->achievement_decline }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>

                                {{-- Penafsiran --}}
                                <tr>
                                    <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                        Penafsiran
                                    </td>
                                    @for ($i = 0; $i < $columnCount; $i++)
                                        <td class="w-[400px] px-6 py-4 align-top text-gray-700">
                                            @if (! empty($selectedEmployees[$i]->burnout->result))
                                                <span class="font-medium">
                                                    {!! strip_tags($selectedEmployees[$i]->burnout->result, '<i><b>') !!}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script>
        (function () {
            // ── Config ──────────────────────────────────────────────────────
            const papiSegments = [
                {
                    code: 'N',
                    angle: 0,
                    cat: 'ARAH KERJA',
                    desc: 'Menyelesaikan\nTugas\nPribadi',
                    color: '#FFB7B7',
                    colorSolid: '#ef4444',
                },
                {
                    code: 'G',
                    angle: 18,
                    cat: 'ARAH KERJA',
                    desc: 'Peranan sbg\nPekerja\nKeras',
                    color: '#FFB7B7',
                    colorSolid: '#ef4444',
                },
                {
                    code: 'A',
                    angle: 36,
                    cat: 'ARAH KERJA',
                    desc: 'Hasrat utk\nBerprestasi',
                    color: '#FFB7B7',
                    colorSolid: '#ef4444',
                },
                {
                    code: 'L',
                    angle: 54,
                    cat: 'KEPEMIMPINAN',
                    desc: 'Peranan\nSbg\nPemimpin',
                    color: '#d1d5db',
                    colorSolid: '#6b7280',
                },
                {
                    code: 'P',
                    angle: 72,
                    cat: 'KEPEMIMPINAN',
                    desc: 'Mengendalikan\nOrang\nLain',
                    color: '#d1d5db',
                    colorSolid: '#6b7280',
                },
                {
                    code: 'I',
                    angle: 90,
                    cat: 'KEPEMIMPINAN',
                    desc: 'Mudah\nMembuat\nMemutuskan',
                    color: '#d1d5db',
                    colorSolid: '#6b7280',
                },
                {
                    code: 'T',
                    angle: 108,
                    cat: 'AKTIVITAS',
                    desc: 'Tempo Kerja',
                    color: '#bfdbfe',
                    colorSolid: '#3b82f6',
                },
                { code: 'V', angle: 126, cat: 'AKTIVITAS', desc: 'Stamina', color: '#bfdbfe', colorSolid: '#3b82f6' },
                {
                    code: 'S',
                    angle: 180,
                    cat: 'PERGAULAN',
                    desc: 'Pergaulan\nSosial',
                    color: '#cffafe',
                    colorSolid: '#06b6d4',
                },
                {
                    code: 'B',
                    angle: 162,
                    cat: 'PERGAULAN',
                    desc: 'Kebutuhan\nTerhadap\nKelompok',
                    color: '#cffafe',
                    colorSolid: '#06b6d4',
                },
                {
                    code: 'O',
                    angle: 144,
                    cat: 'PERGAULAN',
                    desc: 'Kebutuhan\nutk mendekati\n& menyayangi',
                    color: '#cffafe',
                    colorSolid: '#06b6d4',
                },
                {
                    code: 'X',
                    angle: 198,
                    cat: 'PERGAULAN',
                    desc: 'Mendapatkan\nPerhatian',
                    color: '#cffafe',
                    colorSolid: '#06b6d4',
                },
                {
                    code: 'C',
                    angle: 216,
                    cat: 'GAYA KERJA',
                    desc: 'Suka\nPekerjaan\nyg Teratur',
                    color: '#f0fdf4',
                    colorSolid: '#16A34A',
                },
                {
                    code: 'D',
                    angle: 234,
                    cat: 'GAYA KERJA',
                    desc: 'Suka\nPekerjaan\nyg Terperinci',
                    color: '#f0fdf4',
                    colorSolid: '#16A34A',
                },
                {
                    code: 'R',
                    angle: 252,
                    cat: 'GAYA KERJA',
                    desc: 'Tipe\nTeoritis',
                    color: '#f0fdf4',
                    colorSolid: '#16A34A',
                },
                {
                    code: 'Z',
                    angle: 270,
                    cat: 'SIFAT',
                    desc: 'Hasrat utk\nBerubah',
                    color: '#fefce8',
                    colorSolid: '#f59e0b',
                },
                {
                    code: 'E',
                    angle: 288,
                    cat: 'SIFAT',
                    desc: 'Pengendalian\nEmosi',
                    color: '#fefce8',
                    colorSolid: '#f59e0b',
                },
                { code: 'K', angle: 306, cat: 'SIFAT', desc: 'Agresivitas', color: '#fefce8', colorSolid: '#f59e0b' },
                {
                    code: 'F',
                    angle: 324,
                    cat: 'KETAATAN',
                    desc: 'Dukungan\nutk\nArahan',
                    color: '#ffedd5',
                    colorSolid: '#f97316',
                },
                {
                    code: 'W',
                    angle: 342,
                    cat: 'KETAATAN',
                    desc: 'Kebutuhan utk\nTaat Aturan\nArahan',
                    color: '#ffedd5',
                    colorSolid: '#f97316',
                },
            ];
            const papiCategories = {
                KETAATAN: { start: 315, end: 351, color: '#f97316' },
                'ARAH KERJA': { start: 351, end: 45, color: '#ef4444' },
                KEPEMIMPINAN: { start: 45, end: 99, color: '#6b7280' },
                AKTIVITAS: { start: 99, end: 135, color: '#3b82f6' },
                PERGAULAN: { start: 135, end: 207, color: '#06b6d4' },
                'GAYA KERJA': { start: 207, end: 261, color: '#16A34A' },
                SIFAT: { start: 261, end: 315, color: '#FACC15' },
            };

            // ── State ────────────────────────────────────────────────────────
            const chartStore = { papi: {}, disc1: {}, disc2: {}, disc3: {} };
            let pending = false;

            // ── Helpers ──────────────────────────────────────────────────────
            function safeDestroy(store, key) {
                if (store[key]) {
                    try {
                        store[key].destroy();
                    } catch (_) {}
                    delete store[key];
                }
            }

            function drawArcText(ctx, text, cx, cy, radius, centerDeg, spacing = 0.6) {
                ctx.save();
                ctx.font = 'bold 12px Arial';
                ctx.fillStyle = '#fff';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const left = centerDeg > 90 && centerDeg < 270;
                const chars = left ? [...text].reverse() : [...text];
                const ws = chars.map((c) => ctx.measureText(c).width);
                const total = ws.reduce((a, b) => a + b, 0) + spacing * (chars.length - 1);
                let angle = ((centerDeg - 90) * Math.PI) / 180 - total / (2 * radius);
                chars.forEach((ch, i) => {
                    const w = ws[i] / radius,
                        mid = angle + w / 2;
                    ctx.save();
                    ctx.translate(cx + radius * Math.cos(mid), cy + radius * Math.sin(mid));
                    ctx.rotate(mid + Math.PI / 2 + (left ? Math.PI : 0));
                    ctx.fillText(ch, 0, 0);
                    ctx.restore();
                    angle += w + spacing / radius;
                });
                ctx.restore();
            }

            // ── PAPI ─────────────────────────────────────────────────────────
            function drawPAPIChart(id, data, name = 'Employee') {
                const canvas = document.getElementById(id);
                if (!canvas || typeof Chart === 'undefined') return;

                canvas.parentElement?.querySelector('.radar-chart-container')?.remove();
                safeDestroy(chartStore.papi, id);

                const ctx = canvas.getContext('2d');
                const size = Math.min(canvas.parentElement.offsetWidth || 400, 400);
                canvas.width = size;
                canvas.height = size;
                canvas.style.width = size + 'px';
                canvas.style.height = size + 'px';

                const cx = size / 2,
                    cy = size / 2;
                const oR = size * 0.48,
                    iR = size * 0.42;
                const bO = size * 0.41,
                    bI = size * 0.33;
                const dR = size * 0.37;
                const sO = size * 0.32,
                    sI = size * 0.28;
                const rR = size * 0.28;

                ctx.clearRect(0, 0, size, size);

                const catCenter = (s, e) => {
                    if (s <= e) return (s + e) / 2;
                    let c = s + (360 - s + e) / 2;
                    if (c >= 360) c -= 360;
                    return c;
                };
                const catAvg = (cat) => {
                    const segs = papiSegments.filter((s) => s.cat === cat);
                    const vals = segs
                        .map((s) => {
                            const d = data.find((w) => w.code === s.code);
                            return d ? +d.score : null;
                        })
                        .filter((v) => v !== null);
                    return vals.length ? (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) : 0;
                };
                const arc = (s, e, color) => {
                    ctx.beginPath();
                    ctx.arc(cx, cy, oR, ((s - 90) * Math.PI) / 180, ((e - 90) * Math.PI) / 180);
                    ctx.arc(cx, cy, iR, ((e - 90) * Math.PI) / 180, ((s - 90) * Math.PI) / 180, true);
                    ctx.closePath();
                    ctx.fillStyle = color;
                    ctx.fill();
                };

                // Category arcs
                Object.entries(papiCategories).forEach(([n, c]) => {
                    n === 'KETAATAN' ? (arc(315, 360, c.color), arc(0, 45, c.color)) : arc(c.start, c.end, c.color);
                });
                // Category labels
                Object.entries(papiCategories).forEach(([n, c]) => {
                    drawArcText(ctx, `${n} | ${catAvg(n)}`, cx, cy, (oR + iR) / 2, catCenter(c.start, c.end), 0.6);
                });
                // Segment code rings
                papiSegments.forEach((s) => {
                    const sa = ((s.angle - 9 - 90) * Math.PI) / 180,
                        ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                    ctx.beginPath();
                    ctx.arc(cx, cy, sO, sa, ea);
                    ctx.arc(cx, cy, sI, ea, sa, true);
                    ctx.closePath();
                    ctx.fillStyle = s.colorSolid;
                    ctx.fill();
                    ctx.strokeStyle = '#fff';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                });
                // Segment code text
                papiSegments.forEach((s) => {
                    const rad = ((s.angle - 90) * Math.PI) / 180,
                        r = (sO + sI) / 2;
                    ctx.save();
                    ctx.translate(cx + r * Math.cos(rad), cy + r * Math.sin(rad));
                    let rot = rad + Math.PI / 2;
                    if (s.angle > 90 && s.angle < 270) rot += Math.PI;
                    ctx.rotate(rot);
                    ctx.font = 'bold 10px Arial';
                    ctx.fillStyle = '#fff';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(s.code, 0, 0);
                    ctx.restore();
                });
                // Description boxes
                papiSegments.forEach((s) => {
                    const sa = ((s.angle - 9 - 90) * Math.PI) / 180,
                        ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                    ctx.beginPath();
                    ctx.arc(cx, cy, bO, sa, ea);
                    ctx.arc(cx, cy, bI, ea, sa, true);
                    ctx.closePath();
                    ctx.fillStyle = s.color;
                    ctx.fill();
                    ctx.strokeStyle = s.colorSolid;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                });
                // Description text
                papiSegments.forEach((s) => {
                    const rad = ((s.angle - 90) * Math.PI) / 180;
                    ctx.save();
                    ctx.translate(cx + dR * Math.cos(rad), cy + dR * Math.sin(rad));
                    let rot = rad + Math.PI / 2;
                    if (s.angle > 90 && s.angle < 270) rot += Math.PI;
                    ctx.rotate(rot);
                    ctx.font = '8px Arial';
                    ctx.fillStyle = '#000';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    s.desc.split('\n').forEach((line, i, arr) => ctx.fillText(line, 0, (i - (arr.length - 1) / 2) * 8));
                    ctx.restore();
                });

                // Radar overlay
                const div = document.createElement('div');
                div.className = 'radar-chart-container';
                Object.assign(div.style, {
                    position: 'absolute',
                    left: `${cx - rR}px`,
                    top: `${cy - rR}px`,
                    width: `${rR * 2}px`,
                    height: `${rR * 2}px`,
                });
                const rc = document.createElement('canvas');
                rc.width = rR * 2;
                rc.height = rR * 2;
                div.appendChild(rc);
                canvas.parentElement.appendChild(div);

                try {
                    chartStore.papi[id] = new Chart(rc, {
                        type: 'radar',
                        data: {
                            labels: papiSegments.map((s) => s.code),
                            datasets: [
                                {
                                    label: 'PAPI',
                                    data: papiSegments.map((s) => {
                                        const d = data.find((x) => x.code === s.code);
                                        return d ? +d.score : 0;
                                    }),
                                    backgroundColor: 'rgba(59,130,246,0.2)',
                                    borderColor: '#2563EB',
                                    borderWidth: 2,
                                    pointRadius: 2,
                                    pointBackgroundColor: '#2563EB',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 1,
                                },
                            ],
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        title: (c) =>
                                            `${papiSegments[c[0].dataIndex].code} - ${papiSegments[c[0].dataIndex].cat}`,
                                        label: (c) => `${papiSegments[c.dataIndex].desc.replace(/\n/g, ' ')}: ${c.raw}`,
                                    },
                                },
                            },
                            scales: { r: { min: 0, max: 9, pointLabels: { display: false }, ticks: { stepSize: 1 } } },
                            maintainAspectRatio: false,
                            responsive: true,
                        },
                    });
                } catch (_) {}
            }

            // ── DISC ─────────────────────────────────────────────────────────
            function drawDISCChart(id, data, type = 1) {
                const canvas = document.getElementById(id);
                if (!canvas || typeof Chart === 'undefined') return;
                const ex = Chart.getChart(canvas);
                if (ex) ex.destroy();
                const key = `disc${type}`;
                safeDestroy(chartStore[key], id);
                const cm = {
                    1: { border: '#ef4444', bg: 'rgba(239,68,68,0.1)' },
                    2: { border: '#eab308', bg: 'rgba(234,179,8,0.1)' },
                    3: { border: '#22c55e', bg: 'rgba(34,197,94,0.1)' },
                };
                const c = cm[type] || cm[1];
                try {
                    chartStore[key][id] = new Chart(canvas, {
                        type: 'line',
                        plugins: [window.ChartDataLabels || {}],
                        data: {
                            labels: ['D', 'I', 'S', 'C'],
                            datasets: [
                                {
                                    label: `DISC ${type}`,
                                    data: [+data.D || 0, +data.I || 0, +data.S || 0, +data.C || 0],
                                    borderColor: c.border,
                                    backgroundColor: c.bg,
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 6,
                                    pointHoverRadius: 8,
                                    pointBackgroundColor: '#fff',
                                    pointBorderWidth: 3,
                                    pointBorderColor: c.border,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: { padding: { top: 10, bottom: 10, left: 5, right: 5 } },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    padding: 12,
                                    callbacks: { label: (x) => `Score: ${x.parsed.y}` },
                                },
                                datalabels: {
                                    display: true,
                                    color: '#111',
                                    backgroundColor: c.bg.replace('0.1', '0.2'),
                                    borderRadius: 4,
                                    font: { size: 12, weight: 'bold' },
                                    align: 'top',
                                    offset: 6,
                                    formatter: (v) => v,
                                },
                            },
                            scales: {
                                y: { ticks: { stepSize: 20, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                                x: {
                                    ticks: { font: { size: 13, weight: 'bold' }, color: '#374151' },
                                    grid: { color: 'rgba(0,0,0,0.05)' },
                                },
                            },
                        },
                    });
                } catch (_) {}
            }

            // ── Init ─────────────────────────────────────────────────────────
            function initCharts() {
                if (typeof Chart === 'undefined') return;
                for (let i = 0; i < 5; i++) {
                    // PAPI
                    const pc = document.getElementById(`papiChart-${i}`);
                    if (pc) {
                        try {
                            const raw = pc.getAttribute('data-papi-data');
                            if (raw && raw !== '[]' && raw !== 'null') {
                                const d = JSON.parse(raw);
                                if (Array.isArray(d) && d.length)
                                    drawPAPIChart(`papiChart-${i}`, d, pc.dataset.employeeName);
                            }
                        } catch (_) {}
                    }
                    // DISC
                    ['disc1', 'disc2', 'disc3'].forEach((t, idx) => {
                        const dc = document.getElementById(`${t}Chart-${i}`);
                        if (dc) {
                            try {
                                const raw = dc.getAttribute('data-disc-data');
                                if (raw && raw !== '{}' && raw !== 'null') {
                                    const d = JSON.parse(raw);
                                    if (d && Object.keys(d).length) drawDISCChart(`${t}Chart-${i}`, d, idx + 1);
                                }
                            } catch (_) {}
                        }
                    });
                }
            }

            // Debounce agar tidak double-fire
            function scheduleInit() {
                if (pending) return;
                pending = true;
                // requestAnimationFrame memastikan DOM paint sudah selesai
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        initCharts();
                        pending = false;
                    });
                });
            }

            // ── Load Chart.js jika belum ada ─────────────────────────────────
            function ensureChartJS(cb) {
                if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
                    cb();
                    return;
                }

                const loadScript = (src, next) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = next;
                    document.head.appendChild(s);
                };

                if (typeof Chart === 'undefined') {
                    loadScript('https://cdn.jsdelivr.net/npm/chart.js', () => {
                        loadScript('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2', cb);
                    });
                } else {
                    loadScript('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2', cb);
                }
            }

            // ── Bootstrap ────────────────────────────────────────────────────
            ensureChartJS(() => {
                // Jalankan pertama kali
                scheduleInit();

                // Livewire 3 — event dari PHP component
                document.addEventListener('livewire:init', () => {
                    Livewire.on('employee-selected', () => scheduleInit());
                    Livewire.on('charts-loaded', () => scheduleInit());

                    Livewire.on('employee-cleared', (ev) => {
                        const idx = Array.isArray(ev) ? ev[0]?.index : ev?.index;
                        if (idx === undefined) return;

                        const pc = document.getElementById(`papiChart-${idx}`);
                        if (pc) {
                            pc.getContext('2d').clearRect(0, 0, pc.width, pc.height);
                            pc.parentElement?.querySelector('.radar-chart-container')?.remove();
                            safeDestroy(chartStore.papi, `papiChart-${idx}`);
                        }
                        ['disc1', 'disc2', 'disc3'].forEach((t) => {
                            const dc = document.getElementById(`${t}Chart-${idx}`);
                            if (dc) {
                                const ch = Chart.getChart(dc);
                                if (ch) ch.destroy();
                                safeDestroy(chartStore[t], `${t}Chart-${idx}`);
                            }
                        });
                    });

                    // Livewire 3 hook — dipanggil setiap kali DOM selesai di-morph
                    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                        succeed(({ snapshot, effect }) => {
                            scheduleInit();
                        });
                    });
                });

                // Resize
                let rt;
                window.addEventListener('resize', () => {
                    clearTimeout(rt);
                    rt = setTimeout(scheduleInit, 250);
                });
            });
        })();
    </script>
</div>
