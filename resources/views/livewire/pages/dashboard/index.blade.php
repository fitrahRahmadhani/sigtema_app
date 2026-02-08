<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Company Selector for Superadmin --}}
    @if ($isSuperadmin && ! $selectedCompanyId)
        <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 shadow-sm">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900">Select Company</h3>
                <p class="text-sm text-gray-600">Choose a company to view analytics dashboard</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($companies as $company)
                    <button
                        wire:click="$set('selectedCompanyId', {{ $company->id }})"
                        class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 text-left transition-all duration-300 hover:scale-[1.02] hover:border-[#69251a] hover:shadow-lg hover:shadow-[#69251a]/5"
                    >
                        {{-- Background gradient effect --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white via-white to-gray-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        ></div>

                        <div class="relative flex items-center gap-4">
                            {{-- Company Logo --}}
                            <div
                                class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 group-hover:from-[#69251a]/10 group-hover:to-[#8b3523]/10"
                            >
                                @if ($company->logo_path)
                                    <img
                                        class="h-12 w-12 rounded-lg object-contain"
                                        src="{{ asset('storage/' . $company->logo_path) }}"
                                        alt="{{ 'Logo ' . $company->name }}"
                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNmI3MjgwIiBzdHJva2Utd2lkdGg9IjEuNSI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNMi4yNSAyMWgxOS41TTIuMjUgM3YxOG0xMC41LTE4djE4bTYtMTMuNVYyMU02Ljc1IDYuNzVoLjc1bS0uNzUgM2guNzVtLS43NSAzaC43NW0zLTZoLjc1bS0uNzUgM2guNzVtLS43NSAzaC43NU02Ljc1IDIxdi0zLjM3NWMwLS42MjEuNTA0LTEuMTI1IDEuMTI1LTEuMTI1aDIuMjVjLjYyMSAwIDEuMTI1LjUwNCAxLjEyNSAxLjEyNVYyMU0zIDNoMTJtLS43NSA0LjVIMjFtLTMuNzUgMy43NWguMDA4di4wMDhoLS4wMDh2LS4wMDh6bTAgM2guMDA4di4wMDhoLS4wMDh2LS4wMDh6bTAgM2guMDA4di4wMDhoLS4wMDh2LS4wMDh6Ii8+PC9zdmc+'"
                                    />
                                @else
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#69251a] to-[#8b3523]"
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
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                                            />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Company Info --}}
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 group-hover:text-[#69251a]">
                                    {{ $company->name }}
                                </h4>
                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700"
                                    >
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        {{ number_format(\App\Models\Employee::where('company_id', $company->id)->count()) }}
                                        employees
                                    </span>
                                    @if ($company->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700"
                                        >
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Chevron Icon --}}
                            <div
                                class="text-gray-400 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-[#69251a]"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if (! $selectedCompanyId)
        <div
            class="flex min-h-[400px] items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-white p-12 text-center"
        >
            <div class="space-y-3">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    />
                </svg>
                <p class="text-lg font-medium text-gray-600">Please select a company to view dashboard</p>
            </div>
        </div>
    @else
        {{-- Company Header --}}
        @if ($isSuperadmin)
            <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    @if ($selectedCompany->logo_path)
                        <img
                            class="h-12 w-12 rounded-xl object-cover"
                            src="{{ asset('storage/' . $selectedCompany->logo_path) }}"
                            alt="{{ 'Logo ' . $selectedCompany->name }}"
                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNmI3MjgwIiBzdHJva2Utd2lkdGg9IjEuNSI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNMi4yNSAyMWgxOS41TTIuMjUgM3YxOG0xMC41LTE4djE4bTYtMTMuNVYyMU02Ljc1IDYuNzVoLjc1bS0uNzUgM2guNzVtLS43NSAzaC43NW0zLTZoLjc1bS0uNzUgM2guNzVtLS43NSAzaC43NU02Ljc1IDIxdi0zLjM3NWMwLS42MjEuNTA0LTEuMTI1IDEuMTI1LTEuMTI1aDIuMjVjLjYyMSAwIDEuMTI1LjUwNCAxLjEyNSAxLjEyNVYyMU0zIDNoMTJtLS43NSA0LjVIMjFtLTMuNzUgMy43NWguMDA4di4wMDhoLS4wMDh2LS4wMDh6bTAgM2guMDA4di4wMDhoLS4wMDh2LS4wMDh6bTAgM2guMDA4di4wMDhoLS4wMDh2LS4wMDh6Ii8+PC9zdmc+'"
                        />
                    @endif

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedCompany->name }}</h2>
                        <p class="text-sm text-gray-600">Analytics Dashboard</p>
                    </div>
                </div>
                <button
                    wire:click="$set('selectedCompanyId', null)"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-100"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 16l-4-4m0 0l4-4m-4 4h18"
                        />
                    </svg>
                    Change Company
                </button>
            </div>
        @endif

        {{-- Key Metrics Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-[#69251a] to-[#8b3523] p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
            >
                <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 inline-flex rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ number_format($totalEmployees) }}</p>
                    <p class="mt-2 text-sm font-medium text-white/80">Total Employees</p>
                </div>
            </div>

            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
            >
                <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 inline-flex rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ number_format($vacantPositions) }}</p>
                    <p class="mt-2 text-sm font-medium text-white/80">Vacant Positions</p>
                </div>
            </div>

            {{-- Male Employees Card - FIXED --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-blue-500 to-blue-700 p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
            >
                <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 inline-flex rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.2323 9.74707C13.1474 8.66733 11.6516 8 10 8C6.68629 8 4 10.6863 4 14C4 17.3137 6.68629 20 10 20C13.3137 20 16 17.3137 16 14C16 12.3379 15.3242 10.8337 14.2323 9.74707ZM14.2323 9.74707L20 4M20 4H16M20 4V8"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ number_format($maleCount) }}</p>
                    <p class="mt-2 text-sm font-medium text-white/80">Male Employees</p>
                </div>
            </div>

            {{-- Female Employees Card - FIXED --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-pink-500 to-rose-600 p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
            >
                <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 inline-flex rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12m0 0v6m-2-2h4"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ number_format($femaleCount) }}</p>
                    <p class="mt-2 text-sm font-medium text-white/80">Female Employees</p>
                </div>
            </div>

            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-emerald-500 to-teal-600 p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
            >
                <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 inline-flex rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ $avgAge }}</p>
                    <p class="mt-2 text-sm font-medium text-white/80">Avg Age (Years)</p>
                </div>
            </div>
        </div>

        {{-- Charts Row 1 - Demographics --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-gray-900">Gender Split</h6>
                    <span
                        class="rounded-full bg-gradient-to-r from-[#69251a] to-[#8b3523] px-3 py-1 text-xs font-semibold text-white"
                    >
                        Demographics
                    </span>
                </div>
                <div class="h-64" wire:ignore>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-gray-900">Age Groups</h6>
                    <span
                        class="rounded-full bg-gradient-to-r from-teal-500 to-cyan-600 px-3 py-1 text-xs font-semibold text-white"
                    >
                        Demographics
                    </span>
                </div>
                <div class="h-64" wire:ignore>
                    <canvas id="ageChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-gray-900">Tenure Distribution</h6>
                    <span
                        class="rounded-full bg-gradient-to-r from-emerald-500 to-green-600 px-3 py-1 text-xs font-semibold text-white"
                    >
                        Experience
                    </span>
                </div>
                <div class="h-64" wire:ignore>
                    <canvas id="tenureChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts Row 2 - Organization --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-gray-900">Band Distribution</h6>
                    <span
                        class="rounded-full bg-gradient-to-r from-[#69251a] to-[#8b3523] px-3 py-1 text-xs font-semibold text-white"
                    >
                        Organization
                    </span>
                </div>
                <div class="h-80" wire:ignore>
                    <canvas id="bandChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-gray-900">Top Departments</h6>
                    <span
                        class="rounded-full bg-gradient-to-r from-orange-500 to-red-600 px-3 py-1 text-xs font-semibold text-white"
                    >
                        Organization
                    </span>
                </div>
                <div class="h-80" wire:ignore>
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts Row 3 - Insights --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @if (count($mbtiDistribution) > 0)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-lg font-bold text-gray-900">MBTI Types</h6>
                        <span
                            class="rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 px-3 py-1 text-xs font-semibold text-white"
                        >
                            Personality
                        </span>
                    </div>
                    <div class="h-80" wire:ignore>
                        <canvas id="mbtiChart"></canvas>
                    </div>
                </div>
            @endif

            @if (count($burnoutLevels) > 0)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-lg font-bold text-gray-900">Burnout Levels</h6>
                        <span
                            class="rounded-full bg-gradient-to-r from-rose-500 to-red-600 px-3 py-1 text-xs font-semibold text-white"
                        >
                            Wellbeing
                        </span>
                    </div>
                    <div class="h-80" wire:ignore>
                        <canvas id="burnoutChart"></canvas>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tables Row --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            {{-- Vacant Positions --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h6 class="mb-4 text-lg font-bold text-gray-900">Vacant Positions</h6>
                <div class="space-y-2">
                    @if (count($vacantPositionsList) > 0)
                        @foreach ($vacantPositionsList as $position)
                            <div
                                class="group rounded-xl border border-gray-100 bg-gradient-to-r from-gray-50 to-white p-4 transition-all hover:border-[#69251a] hover:shadow-md"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 group-hover:text-[#69251a]">
                                            {{ $position['name'] }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $position['code'] }}</p>
                                    </div>
                                    <div class="hidden flex-1 md:block">
                                        <p class="text-sm text-gray-600">Work Unit</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $position['work_unit'] }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                        Vacant
                                    </span>
                                </div>
                                <div class="mt-2 md:hidden">
                                    <p class="text-sm text-gray-600">Work Unit</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $position['work_unit'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="py-8 text-center text-sm text-gray-500">No vacant positions</p>
                    @endif
                </div>
            </div>

            {{-- Top Innovators --}}
            @if (count($topInnovators) > 0)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h6 class="mb-4 text-lg font-bold text-gray-900">Top Innovators</h6>
                    <div class="space-y-2">
                        @foreach ($topInnovators as $index => $innovator)
                            <div
                                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gradient-to-r from-yellow-50 to-white p-4 transition-all hover:border-yellow-300 hover:shadow-md"
                            >
                                <div
                                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#69251a] to-[#8b3523] font-bold text-white"
                                >
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $innovator['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $innovator['total'] }} innovations</p>
                                </div>
                                <div class="text-2xl">
                                    @if ($index === 0)
                                        🥇
                                    @elseif ($index === 1)
                                        🥈
                                    @elseif ($index === 2)
                                        🥉
                                    @else
                                        🏅
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @script
        <script>
            // Chart Storage - global untuk menyimpan chart instances
            window.dashboardCharts = window.dashboardCharts || {};

            // Fungsi untuk destroy chart yang ada
            function destroyChart(chartId) {
                if (window.dashboardCharts[chartId]) {
                    window.dashboardCharts[chartId].destroy();
                    delete window.dashboardCharts[chartId];
                }
            }

            // Fungsi untuk destroy semua charts
            function destroyAllCharts() {
                Object.keys(window.dashboardCharts).forEach((chartId) => {
                    destroyChart(chartId);
                });
            }

            // Fungsi untuk membuat chart
            function createChart(chartId, config) {
                const canvas = document.getElementById(chartId);
                if (!canvas) return null;

                // Destroy existing chart
                destroyChart(chartId);

                // Create new chart
                try {
                    window.dashboardCharts[chartId] = new Chart(canvas, config);
                    return window.dashboardCharts[chartId];
                } catch (error) {
                    return null;
                }
            }

            // Fungsi utama untuk render semua charts dengan styling yang lebih ciamik
            function renderDashboardCharts() {
                // Destroy existing charts first
                destroyAllCharts();

                // Get data from Livewire
                const genderData = $wire.genderDistribution || [];
                const ageData = $wire.ageDistribution || [];
                const tenureData = $wire.tenureDistribution || [];
                const bandData = $wire.bandDistribution || [];
                const departmentData = $wire.departmentDistribution || [];
                const mbtiData = $wire.mbtiDistribution || [];
                const burnoutData = $wire.burnoutLevels || [];

                // Gender Chart - Improved with better styling
                if (genderData.length > 0) {
                    createChart('genderChart', {
                        type: 'doughnut',
                        data: {
                            labels: genderData.map((d) => d.label),
                            datasets: [
                                {
                                    data: genderData.map((d) => d.value),
                                    backgroundColor: ['rgba(59, 130, 246, 0.9)', 'rgba(236, 72, 153, 0.9)'],
                                    borderWidth: 3,
                                    borderColor: '#fff',
                                    hoverOffset: 12,
                                    hoverBorderWidth: 4,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 13,
                                            weight: '600',
                                            family: "'Inter', sans-serif",
                                        },
                                        color: '#374151',
                                    },
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        },
                                    },
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 18,
                                        family: "'Inter', sans-serif",
                                    },
                                    textStrokeColor: 'rgba(0, 0, 0, 0.3)',
                                    textStrokeWidth: 2,
                                    formatter: (value, ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return percentage + '%';
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // Age Chart - Improved polar area chart
                if (ageData.length > 0) {
                    createChart('ageChart', {
                        type: 'polarArea',
                        data: {
                            labels: ageData.map((d) => d.label),
                            datasets: [
                                {
                                    data: ageData.map((d) => d.value),
                                    backgroundColor: [
                                        'rgba(20, 184, 166, 0.85)',
                                        'rgba(6, 182, 212, 0.85)',
                                        'rgba(14, 165, 233, 0.85)',
                                        'rgba(59, 130, 246, 0.85)',
                                        'rgba(99, 102, 241, 0.85)',
                                    ],
                                    borderWidth: 3,
                                    borderColor: '#fff',
                                    hoverBorderWidth: 4,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        usePointStyle: true,
                                        font: {
                                            size: 12,
                                            weight: '600',
                                            family: "'Inter', sans-serif",
                                        },
                                        color: '#374151',
                                    },
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 16,
                                        family: "'Inter', sans-serif",
                                    },
                                    textStrokeColor: 'rgba(0, 0, 0, 0.4)',
                                    textStrokeWidth: 2,
                                    formatter: (value) => value,
                                },
                            },
                            scales: {
                                r: {
                                    ticks: {
                                        display: false,
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // Tenure Chart - Enhanced bar chart
                if (tenureData.length > 0) {
                    createChart('tenureChart', {
                        type: 'bar',
                        data: {
                            labels: tenureData.map((d) => d.label),
                            datasets: [
                                {
                                    label: 'Employees',
                                    data: tenureData.map((d) => d.value),
                                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                                    borderRadius: 10,
                                    borderSkipped: false,
                                    hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    offset: 4,
                                    color: '#059669',
                                    font: {
                                        weight: 'bold',
                                        size: 14,
                                        family: "'Inter', sans-serif",
                                    },
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderRadius: 6,
                                    padding: {
                                        top: 4,
                                        bottom: 4,
                                        left: 8,
                                        right: 8,
                                    },
                                    formatter: (value) => value,
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)',
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#6B7280',
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#6B7280',
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // Band Chart - Enhanced with gradient
                if (bandData.length > 0) {
                    createChart('bandChart', {
                        type: 'bar',
                        data: {
                            labels: bandData.map((d) => d.label),
                            datasets: [
                                {
                                    label: 'Employees',
                                    data: bandData.map((d) => d.value),
                                    backgroundColor: 'rgba(105, 37, 26, 0.9)',
                                    borderRadius: 10,
                                    borderSkipped: false,
                                    hoverBackgroundColor: '#8b3523',
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    offset: 4,
                                    color: '#69251a',
                                    font: {
                                        weight: 'bold',
                                        size: 14,
                                        family: "'Inter', sans-serif",
                                    },
                                    backgroundColor: 'rgba(105, 37, 26, 0.1)',
                                    borderRadius: 6,
                                    padding: {
                                        top: 4,
                                        bottom: 4,
                                        left: 8,
                                        right: 8,
                                    },
                                    formatter: (value) => value,
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)',
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#6B7280',
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#6B7280',
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // Department Chart - Horizontal bar with better labels
                if (departmentData.length > 0) {
                    createChart('departmentChart', {
                        type: 'bar',
                        data: {
                            labels: departmentData.map((d) => d.label),
                            datasets: [
                                {
                                    label: 'Employees',
                                    data: departmentData.map((d) => d.value),
                                    backgroundColor: 'rgba(245, 148, 39, 0.85)',
                                    borderRadius: 10,
                                    borderSkipped: false,
                                    hoverBackgroundColor: 'rgba(245, 148, 39, 1)',
                                },
                            ],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'end',
                                    offset: 6,
                                    color: '#f59427',
                                    font: {
                                        weight: 'bold',
                                        size: 14,
                                        family: "'Inter', sans-serif",
                                    },
                                    backgroundColor: 'rgba(245, 148, 39, 0.1)',
                                    borderRadius: 6,
                                    padding: {
                                        top: 4,
                                        bottom: 4,
                                        left: 8,
                                        right: 8,
                                    },
                                    formatter: (value) => value,
                                },
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)',
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#6B7280',
                                    },
                                },
                                y: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: '500',
                                        },
                                        color: '#374151',
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // MBTI Chart - Enhanced doughnut
                if (mbtiData.length > 0) {
                    createChart('mbtiChart', {
                        type: 'doughnut',
                        data: {
                            labels: mbtiData.map((d) => d.label),
                            datasets: [
                                {
                                    data: mbtiData.map((d) => d.value),
                                    backgroundColor: [
                                        'rgba(14, 165, 233, 0.9)',
                                        'rgba(139, 92, 246, 0.9)',
                                        'rgba(236, 72, 153, 0.9)',
                                        'rgba(245, 158, 11, 0.9)',
                                        'rgba(16, 185, 129, 0.9)',
                                        'rgba(6, 182, 212, 0.9)',
                                        'rgba(239, 68, 68, 0.9)',
                                        'rgba(132, 204, 22, 0.9)',
                                    ],
                                    borderWidth: 3,
                                    borderColor: '#fff',
                                    hoverBorderWidth: 4,
                                    hoverOffset: 8,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 12,
                                        usePointStyle: true,
                                        font: {
                                            size: 11,
                                            weight: '600',
                                            family: "'Inter', sans-serif",
                                        },
                                        color: '#374151',
                                    },
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        },
                                    },
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 13,
                                        family: "'Inter', sans-serif",
                                    },
                                    textStrokeColor: 'rgba(0, 0, 0, 0.3)',
                                    textStrokeWidth: 2,
                                    formatter: (value, ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return percentage >= 3 ? percentage + '%' : '';
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }

                // Burnout Chart - Color-coded doughnut
                if (burnoutData.length > 0) {
                    const burnoutColors = {
                        Low: 'rgba(34, 197, 94, 0.9)',
                        Moderate: 'rgba(251, 191, 36, 0.9)',
                        High: 'rgba(239, 68, 68, 0.9)',
                    };

                    createChart('burnoutChart', {
                        type: 'doughnut',
                        data: {
                            labels: burnoutData.map((d) => d.label),
                            datasets: [
                                {
                                    data: burnoutData.map((d) => d.value),
                                    backgroundColor: burnoutData.map(
                                        (d) => burnoutColors[d.label] || 'rgba(156, 163, 175, 0.8)',
                                    ),
                                    borderWidth: 3,
                                    borderColor: '#fff',
                                    hoverBorderWidth: 4,
                                    hoverOffset: 10,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        font: {
                                            size: 13,
                                            weight: '600',
                                            family: "'Inter', sans-serif",
                                        },
                                        color: '#374151',
                                    },
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        },
                                    },
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 16,
                                        family: "'Inter', sans-serif",
                                    },
                                    textStrokeColor: 'rgba(0, 0, 0, 0.4)',
                                    textStrokeWidth: 2,
                                    formatter: (value, ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return value + '\n' + percentage + '%';
                                    },
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    });
                }
            }

            // Listen untuk event dari Livewire
            $wire.on('dashboard-data-loaded', () => {
                setTimeout(() => renderDashboardCharts(), 200);
            });

            $wire.on('company-changed', () => {
                setTimeout(() => renderDashboardCharts(), 300);
            });

            // Initial render saat page load
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => renderDashboardCharts(), 500);
            });
        </script>
    @endscript
</div>
