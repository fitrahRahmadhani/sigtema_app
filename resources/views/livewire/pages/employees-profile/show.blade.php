<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('employees-profile.index') }}">Employees Profile</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $employee->name }}'s Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-6 lg:p-10">
        <!-- Header -->
        <div class="mb-8 flex flex-col items-start gap-6 lg:flex-row lg:gap-8">
            <div
                class="flex h-32 w-28 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-gray-300 bg-gray-100 sm:h-44 sm:w-40"
            >
                @if ($employee->profile_path)
                    <img
                        src="{{ asset('storage/' . $employee->profile_path) }}"
                        alt="Foto Profile {{ $employee->name }}"
                        class="h-full w-full object-cover"
                    />
                @else
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-32">
                            <path
                                fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                @endif
            </div>
            <div class="h-fit w-full flex-1">
                <h6 class="flex items-center gap-2 text-xl font-bold text-gray-900 sm:text-2xl md:text-3xl">
                    {{ $employee->name }}

                    {{-- ICON GENDER --}}
                    @if ($employee->gender === 'Male')
                        <span class="inline-flex items-center text-blue-500">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                class="size-7"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M15 3C15 2.44772 15.4477 2 16 2H20C21.1046 2 22 2.89543 22 4V8C22 8.55229 21.5523 9 21 9C20.4477 9 20 8.55228 20 8V5.41288L15.4671 9.94579C15.4171 9.99582 15.363 10.0394 15.3061 10.0767C16.3674 11.4342 17 13.1432 17 15C17 19.4183 13.4183 23 9 23C4.58172 23 1 19.4183 1 15C1 10.5817 4.58172 7 9 7C10.8559 7 12.5642 7.63197 13.9214 8.69246C13.9587 8.63539 14.0024 8.58128 14.0525 8.53118L18.5836 4H16C15.4477 4 15 3.55228 15 3ZM9 20.9963C5.68831 20.9963 3.00365 18.3117 3.00365 15C3.00365 11.6883 5.68831 9.00365 9 9.00365C12.3117 9.00365 14.9963 11.6883 14.9963 15C14.9963 18.3117 12.3117 20.9963 9 20.9963Z"
                                />
                            </svg>
                        </span>
                    @elseif ($employee->gender === 'Female')
                        <span class="inline-flex items-center text-pink-500">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                class="size-7"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M20 9C20 13.0803 16.9453 16.4471 12.9981 16.9383C12.9994 16.9587 13 16.9793 13 17V19H14C14.5523 19 15 19.4477 15 20C15 20.5523 14.5523 21 14 21H13V22C13 22.5523 12.5523 23 12 23C11.4477 23 11 22.5523 11 22V21H10C9.44772 21 9 20.5523 9 20C9 19.4477 9.44772 19 10 19H11V17C11 16.9793 11.0006 16.9587 11.0019 16.9383C7.05466 16.4471 4 13.0803 4 9C4 4.58172 7.58172 1 12 1C16.4183 1 20 4.58172 20 9ZM6.00365 9C6.00365 12.3117 8.68831 14.9963 12 14.9963C15.3117 14.9963 17.9963 12.3117 17.9963 9C17.9963 5.68831 15.3117 3.00365 12 3.00365C8.68831 3.00365 6.00365 5.68831 6.00365 9Z"
                                />
                            </svg>
                        </span>
                    @endif
                </h6>
                <div class="mt-4 grid h-full w-full grid-cols-2 gap-4 sm:mt-6 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Employee ID</p>
                        <p class="">{{ $employee->employee_number ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Position</p>
                        <p class="">{{ $employee->position->name ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Work Unit</p>
                        <p class="">
                            {{ $employee->position->department ?? '-' }}
                            <br />
                            {{ $employee->position->work_unit ?? '-' }}
                        </p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Hire Date</p>
                        <p class="">{{ $employee->hired ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Office</p>
                        <p class="">{{ $employee->position->location ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Contract Type</p>
                        <p class="">{{ $employee->band ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Age</p>
                        <p class="">{{ $employee->age ?? '-' }} years old</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Job Grade</p>
                        <p class="">{{ $employee->position->job_grade ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Personal Grade</p>
                        <p class="">{{ $employee->personal_grade ?? '-' }}</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Personality</p>
                        <p class="">
                            MBTI:
                            <span class="font-semibold">{{ $employee->mbti->mbti ?? '-' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full space-y-6 md:space-y-8">
            <!-- Two Column Section: PAPI Kostick & DISC -->
            <div class="grid w-full grid-cols-1 gap-6 md:gap-8 lg:grid-cols-2">
                {{-- PAPI Kostick --}}
                <div class="flex w-full flex-col">
                    <div class="color-gradient flex w-full items-baseline rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">PAPI Kostick</p>
                        <p class="ml-2 text-sm text-white italic">data powered by</p>
                        <img
                            src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                            class="mb-1 ml-2 h-5 self-end"
                            alt="Logo SIGMATIC"
                        />
                    </div>

                    <div
                        class="flex grow items-center justify-center rounded-b-lg border border-t-0 border-gray-300 bg-white p-2 md:p-4"
                    >
                        @if ($workDirectionData)
                            {{-- ================= CANVAS ================= --}}
                            <div class="flex w-full items-center justify-center p-2" wire:ignore>
                                <div
                                    class="relative aspect-square w-full max-w-[650px] rounded-full border-2 border-dashed"
                                >
                                    <canvas id="workDirectionCanvas" class="h-full w-full"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center">
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
                                <p class="mt-2 text-sm text-gray-300">No PAPI Kostick data found</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- DISC --}}
                <div class="flex w-full flex-col">
                    <div class="color-gradient flex w-full items-baseline rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">DISC</p>
                        <p class="ml-2 text-sm text-white italic">data powered by</p>
                        <img
                            src="{{ asset('assets/logo/logo-sigmatic-white.png') }}"
                            class="mb-1 ml-2 h-5 self-end"
                            alt="Logo SIGMATIC"
                        />
                    </div>

                    <div class="flex grow flex-col rounded-b-lg border border-t-0 border-gray-300 bg-white p-2 md:p-4">
                        @if ($discData)
                            <div class="grid h-full grid-cols-1 gap-4 sm:grid-cols-3" wire:ignore>
                                <!-- DISC 1 -->
                                <div class="flex h-full flex-col">
                                    <div class="rounded-t-md bg-red-500 px-3 py-2 text-center">
                                        <p class="text-sm font-semibold text-white">DISC 1</p>
                                    </div>
                                    <div
                                        class="relative flex min-h-[250px] grow items-center justify-center border-x border-gray-300 bg-white p-2 sm:min-h-[300px] md:min-h-[350px]"
                                    >
                                        <canvas
                                            id="disc1Canvas"
                                            style="width: 100% !important; height: 100% !important"
                                        ></canvas>
                                    </div>
                                    <div class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-2 py-3">
                                        <p class="text-center text-sm leading-relaxed text-gray-700">
                                            {{ $discData['interpretation1'] ?? 'No interpretation' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- DISC 2 -->
                                <div class="flex h-full flex-col">
                                    <div class="rounded-t-md bg-yellow-500 px-3 py-2 text-center">
                                        <p class="text-sm font-semibold text-white">DISC 2</p>
                                    </div>
                                    <div
                                        class="relative flex min-h-[250px] grow items-center justify-center border-x border-gray-300 bg-white p-2 sm:min-h-[300px] md:min-h-[350px]"
                                    >
                                        <canvas
                                            id="disc2Canvas"
                                            style="width: 100% !important; height: 100% !important"
                                        ></canvas>
                                    </div>
                                    <div class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-2 py-3">
                                        <p class="text-center text-sm leading-relaxed text-gray-700">
                                            {{ $discData['interpretation2'] ?? 'No interpretation' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- DISC 3 -->
                                <div class="flex h-full flex-col">
                                    <div class="rounded-t-md bg-green-500 px-3 py-2 text-center">
                                        <p class="text-sm font-semibold text-white">DISC 3</p>
                                    </div>
                                    <div
                                        class="relative flex min-h-[250px] grow items-center justify-center border-x border-gray-300 bg-white p-2 sm:min-h-[300px] md:min-h-[350px]"
                                    >
                                        <canvas
                                            id="disc3Canvas"
                                            style="width: 100% !important; height: 100% !important"
                                        ></canvas>
                                    </div>
                                    <div class="rounded-b-md border-x border-b border-gray-300 bg-gray-50 px-2 py-3">
                                        <p class="text-center text-sm leading-relaxed text-gray-700">
                                            {{ $discData['interpretation3'] ?? 'No interpretation' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center">
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
                                <p class="mt-2 text-sm text-gray-300">No DISC data found</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Soft Competencies -->
                <div class="w-full lg:col-span-1">
                    <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">Soft Competencies</p>
                    </div>
                    <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                    <th
                                        class="w-1/3 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                    >
                                        Competency
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Aspects</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Strengths
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        <ol class="ml-4 list-disc">
                                            @forelse ($softCompetencies['strengths'] as $strength)
                                                <li>{{ $strength->aspect->aspect_name }}</li>
                                            @empty
                                                <li>-</li>
                                            @endforelse
                                        </ol>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Weaknesses
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        <ol class="ml-4 list-disc">
                                            @forelse ($softCompetencies['weaknesses'] as $weaknesses)
                                                <li>{{ $weaknesses->aspect->aspect_name }}</li>
                                            @empty
                                                <li>-</li>
                                            @endforelse
                                        </ol>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Technical Competencies -->
                <div class="w-full lg:col-span-1">
                    <div class="color-gradient flex w-full items-baseline rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">Technical Competencies</p>
                        <p class="ml-2 text-sm text-white italic">
                            data powered by
                            <span class="font-bold">LSP SIG</span>
                        </p>
                    </div>
                    <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                    <th
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center font-semibold text-gray-800 sm:px-4"
                                    >
                                        Year
                                    </th>
                                    <th class="w-2/6 border-r px-3 py-2 font-semibold text-gray-600 sm:px-4">Issuer</th>
                                    <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Competencies</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employee->technicalCompetencies as $techCompetency)
                                    <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                        <td
                                            class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                        >
                                            {{ $techCompetency->year }}
                                        </td>
                                        <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">
                                            {{ $techCompetency->issued_by }}
                                        </td>
                                        <td class="px-3 py-2 text-gray-600 sm:px-4">
                                            {{ $techCompetency->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-8 text-center text-gray-500 sm:px-4">
                                            <div class="flex flex-col items-center justify-center">
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
                                                        stroke-width="1.5"
                                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                                    />
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-300">
                                                    No technical competencies found
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Educations --}}
            <div class="w-full">
                <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                    <h6 class="text-base font-semibold text-white sm:text-lg">Educations</h6>
                </div>
                <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4">
                                    Period
                                </th>
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4">
                                    Level
                                </th>
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4">
                                    Major
                                </th>
                                <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Institution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->educations as $education)
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                        {{ $education->formatted_period }}
                                    </td>
                                    <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                        {{ $education->degree ?? '-' }}
                                    </td>
                                    <td class="border-r border-gray-300 px-3 py-3 text-start text-gray-800 sm:px-4">
                                        {{ $education->major ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-gray-600 sm:px-4">
                                        {{ $education->institution_name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500 sm:px-4">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-12 w-12 text-gray-300"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                                                />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-300">No education records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Position History --}}
            <div class="w-full">
                <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                    <h6 class="text-base font-semibold text-white sm:text-lg">Position History</h6>
                </div>
                <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4">
                                    Period
                                </th>
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4">
                                    Position
                                </th>
                                <th class="border-r border-gray-300 px-3 py-2 font-semibold text-gray-600 sm:px-4">
                                    Work Unit
                                </th>
                                <th class="px-3 py-2 font-semibold text-gray-800 sm:px-4">Company</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->positionHistories as $positionHistory)
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                        {{ $positionHistory->formatted_period }}
                                    </td>
                                    <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">
                                        {{ $positionHistory->position_name }}
                                    </td>
                                    <td class="border-r border-gray-300 px-3 py-3 text-gray-600 sm:px-4">
                                        {{ $positionHistory->work_unit }}
                                    </td>
                                    <td class="px-3 py-3 text-center text-gray-800 sm:px-4">
                                        {{ $positionHistory->company }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500 sm:px-4">
                                        <div class="flex flex-col items-center justify-center">
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
                                                    stroke-width="1.5"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-300">No position history records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Innovation --}}
            <div class="w-full">
                <div class="color-gradient flex w-full items-baseline rounded-t-lg px-4 py-3">
                    <p class="text-base font-semibold text-white sm:text-lg">Innovations</p>
                    <p class="ml-2 text-sm text-white italic">data powered by</p>
                    <img
                        src="{{ asset('assets/logo/logo-portal-inovasi.png') }}"
                        class="ml-2 h-5"
                        alt="Logo Portal Inovasi"
                    />
                </div>
                <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                <th
                                    class="w-1/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Rank
                                </th>
                                <th
                                    class="w-5/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Innovation Title
                                </th>
                                <th
                                    class="w-2/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Team
                                </th>
                                <th
                                    class="w-2/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Award
                                </th>
                                <th class="w-2/12 px-3 py-2 font-semibold text-gray-600 sm:px-4">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->innovations as $innovation)
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        {{ $innovation->status }}
                                        <br />
                                        {{ $innovation->achievement }}
                                    </td>
                                    <td
                                        class="w-5/12 border-r border-gray-300 px-3 py-2 text-start text-gray-800 sm:px-4"
                                    >
                                        {{ $innovation->title }}
                                    </td>
                                    <td
                                        class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        {{ $innovation->team }}
                                    </td>
                                    <td
                                        class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        {{ $innovation->event }}
                                    </td>
                                    <td class="w-2/12 px-3 py-2 text-center text-gray-600 sm:px-4">
                                        {{ $innovation->category }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-3 py-8 text-center text-gray-500 sm:px-4">
                                        <div class="flex flex-col items-center justify-center">
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
                                                    d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                                                />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-300">No position history records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Two Column Section: MCU Result, Burnout -->
            <div class="grid w-full grid-cols-1 gap-6 md:gap-8 lg:grid-cols-2">
                <!-- MCU Result -->
                <div class="w-full lg:col-span-1">
                    <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">MCU Results</p>
                    </div>
                    <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                    <th
                                        class="w-1/3 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                    >
                                        Year
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employee->mcuResults as $mcu)
                                    <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                        <td
                                            class="w-1/3 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                        >
                                            {{ $mcu->year }}
                                        </td>
                                        <td class="px-3 py-2 text-gray-600 sm:px-4">{{ $mcu->result }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-8 text-center text-gray-500 sm:px-4">
                                            <div class="flex flex-col items-center justify-center">
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
                                                        stroke-width="1.5"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-300">No MCU data available</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Burnout -->
                <div class="w-full lg:col-span-1">
                    <div class="color-gradient flex w-full items-baseline rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">Burnout Test Result</p>
                        <p class="ml-2 text-sm text-white italic">data powered by</p>
                        <img
                            src="{{ asset('assets/logo/logo-sighap-white.png') }}"
                            class="mb-1 ml-2 h-4 self-end"
                            alt="Logo SIGMATIC"
                        />
                    </div>
                    <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                    <th
                                        class="w-2/3 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                    >
                                        Aspek
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-2/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Kelelahan Emosional
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        {{ $employee->burnout->emotional_exhaustion ?? '-' }}
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-2/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Depersonalisasi
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        {{ $employee->burnout->depersonalization ?? '-' }}
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-2/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Penurunan Prestasi
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        {{ $employee->burnout->achievement_decline ?? '-' }}
                                    </td>
                                </tr>
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-2/3 border-r border-gray-300 px-3 py-2 font-bold text-gray-800 sm:px-4"
                                    >
                                        Total Skor
                                    </td>
                                    <td class="px-3 py-2 font-bold text-gray-600 sm:px-4">
                                        {{ $employee->burnout?->getTotalScore() ?? '-' }}
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-300 transition-colors">
                                    <td colspan="2" class="px-3 py-3 sm:px-4">
                                        <div class="mb-1 font-semibold text-gray-800">Penafsiran Hasil:</div>
                                        <div class="text-sm text-gray-700">
                                            {!! strip_tags($employee->burnout?->result, '<i><b>') ?? '-' !!}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* ================= PAPI DATA ================= */
        const workData = @json($workDirectionData);

        /* ================= DISC DATA ================= */
        const discData = @json($discData);

        const segments = [
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
            { code: 'T', angle: 108, cat: 'AKTIVITAS', desc: 'Tempo Kerja', color: '#bfdbfe', colorSolid: '#3b82f6' },
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

        const categories = {
            KETAATAN: { start: 315, end: 351, color: '#f97316' },
            'ARAH KERJA': { start: 351, end: 45, color: '#ef4444' },
            KEPEMIMPINAN: { start: 45, end: 99, color: '#6b7280' },
            AKTIVITAS: { start: 99, end: 135, color: '#3b82f6' },
            PERGAULAN: { start: 135, end: 207, color: '#06b6d4' },
            'GAYA KERJA': { start: 207, end: 261, color: '#16A34A' },
            SIFAT: { start: 261, end: 315, color: '#FACC15' },
        };

        /* ================= ARC TEXT ================= */
        function drawArcText(ctx, text, cx, cy, radius, centerDeg, letterSpacing = 0.6) {
            ctx.save();
            ctx.font = 'bold 15px Arial';
            ctx.fillStyle = '#fff';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            const left = centerDeg > 90 && centerDeg < 270;
            const chars = left ? [...text].reverse() : [...text];
            const widths = chars.map((c) => ctx.measureText(c).width);
            const totalWidth = widths.reduce((a, b) => a + b, 0) + letterSpacing * (chars.length - 1);
            let angle = ((centerDeg - 90) * Math.PI) / 180 - totalWidth / (2 * radius);

            chars.forEach((ch, i) => {
                const w = widths[i] / radius;
                const mid = angle + w / 2;
                const x = cx + radius * Math.cos(mid);
                const y = cy + radius * Math.sin(mid);

                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(mid + Math.PI / 2 + (left ? Math.PI : 0));
                ctx.fillText(ch, 0, 0);
                ctx.restore();

                angle += w + letterSpacing / radius;
            });

            ctx.restore();
        }

        /* ================= DRAW PAPI CHART ================= */
        function drawPAPIChart() {
            const canvas = document.getElementById('workDirectionCanvas');
            const ctx = canvas.getContext('2d');
            const size = canvas.parentElement.offsetWidth;

            canvas.width = size;
            canvas.height = size;

            const cx = size / 2;
            const cy = size / 2;

            const outerR = size * 0.48;
            const innerR = size * 0.4;
            const boxOuter = size * 0.39;
            const boxInner = size * 0.31;
            const descR = size * 0.35;
            const subDescOuter = size * 0.3;
            const subDescInner = size * 0.24;
            const radarR = size * 0.24;

            ctx.clearRect(0, 0, size, size);

            function getCategoryCenter(start, end) {
                if (start <= end) return (start + end) / 2;
                const span = 360 - start + end;
                let center = start + span / 2;
                if (center >= 360) center -= 360;
                return center;
            }

            function getCategoryAverage(categoryName) {
                const segs = segments.filter((s) => s.cat === categoryName);
                if (!segs.length) return 0;
                const scores = segs
                    .map((s) => {
                        const d = workData.find((w) => w.code === s.code);
                        return d ? d.score : null;
                    })
                    .filter((v) => v !== null);
                if (!scores.length) return 0;
                return (scores.reduce((a, b) => a + b, 0) / scores.length).toFixed(1);
            }

            function arc(start, end, color) {
                ctx.beginPath();
                ctx.arc(cx, cy, outerR, ((start - 90) * Math.PI) / 180, ((end - 90) * Math.PI) / 180);
                ctx.arc(cx, cy, innerR, ((end - 90) * Math.PI) / 180, ((start - 90) * Math.PI) / 180, true);
                ctx.closePath();
                ctx.fillStyle = color;
                ctx.fill();
            }

            Object.entries(categories).forEach(([n, c]) => {
                if (n === 'KETAATAN') {
                    arc(315, 360, c.color);
                    arc(0, 45, c.color);
                } else arc(c.start, c.end, c.color);
            });

            Object.entries(categories).forEach(([name, c]) => {
                const r = (outerR + innerR) / 2;
                const center = getCategoryCenter(c.start, c.end);
                const avg = getCategoryAverage(name);
                const label = `${name} | ${avg}`;
                drawArcText(ctx, label, cx, cy, r, center, 0.6);
            });

            segments.forEach((s) => {
                const sa = ((s.angle - 9 - 90) * Math.PI) / 180;
                const ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                ctx.beginPath();
                ctx.arc(cx, cy, subDescOuter, sa, ea);
                ctx.arc(cx, cy, subDescInner, ea, sa, true);
                ctx.closePath();
                ctx.fillStyle = s.colorSolid;
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 1;
                ctx.stroke();
            });

            segments.forEach((s) => {
                const rad = ((s.angle - 90) * Math.PI) / 180;
                const r = (subDescOuter + subDescInner) / 2;
                const x = cx + r * Math.cos(rad);
                const y = cy + r * Math.sin(rad);
                ctx.save();
                ctx.translate(x, y);
                let rotate = rad + Math.PI / 2;
                if (s.angle > 90 && s.angle < 270) rotate += Math.PI;
                ctx.rotate(rotate);
                ctx.font = 'bold 12px Arial';
                ctx.fillStyle = '#fff';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(s.code, 0, 0);
                ctx.restore();
            });

            segments.forEach((s) => {
                const sa = ((s.angle - 9 - 90) * Math.PI) / 180;
                const ea = ((s.angle + 9 - 90) * Math.PI) / 180;
                ctx.beginPath();
                ctx.arc(cx, cy, boxOuter, sa, ea);
                ctx.arc(cx, cy, boxInner, ea, sa, true);
                ctx.closePath();
                ctx.fillStyle = s.color;
                ctx.fill();
                ctx.strokeStyle = s.colorSolid;
                ctx.lineWidth = 1;
                ctx.stroke();
            });

            segments.forEach((s) => {
                const rad = ((s.angle - 90) * Math.PI) / 180;
                const x = cx + descR * Math.cos(rad);
                const y = cy + descR * Math.sin(rad);
                ctx.save();
                ctx.translate(x, y);
                let rotate = rad + Math.PI / 2;
                if (s.angle > 90 && s.angle < 270) rotate += Math.PI;
                ctx.rotate(rotate);
                ctx.font = '10px Arial';
                ctx.fillStyle = '#000';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const lines = s.desc.split('\n');
                lines.forEach((line, i) => {
                    ctx.fillText(line, 0, (i - (lines.length - 1) / 2) * 10);
                });
                ctx.restore();
            });

            drawRadar(cx, cy, radarR);
        }

        function drawRadar(cx, cy, r) {
            const div = document.createElement('div');
            div.style.position = 'absolute';
            div.style.left = cx - r + 'px';
            div.style.top = cy - r + 'px';
            div.style.width = r * 2 + 'px';
            div.style.height = r * 2 + 'px';

            const c = document.createElement('canvas');
            div.appendChild(c);
            document.getElementById('workDirectionCanvas').parentElement.appendChild(div);

            new Chart(c, {
                type: 'radar',
                plugins: [ChartDataLabels],
                data: {
                    labels: segments.map((s) => s.code),
                    datasets: [
                        {
                            data: segments.map((s) => {
                                const f = workData.find((d) => d.code === s.code);
                                return f ? f.score : 0;
                            }),
                            backgroundColor: 'rgba(59,130,246,.2)',
                            borderColor: '#2563EB',
                            borderWidth: 2,
                            pointRadius: 3,
                        },
                    ],
                },
                options: {
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title(ctx) {
                                    const s = segments[ctx[0].dataIndex];
                                    return `${s.code} — ${s.cat}`;
                                },
                                label(ctx) {
                                    const s = segments[ctx.dataIndex];
                                    const cleanDesc = s.desc
                                        .replace(/\\n/g, ' ')
                                        .replace(/\n/g, ' ')
                                        .replace(/\s+/g, ' ');
                                    return [cleanDesc, `Skor : ${ctx.raw}`];
                                },
                            },
                        },
                        datalabels: {
                            color: '#111',
                            backgroundColor: '#ffffff.9',
                            borderRadius: 4,
                            font: { size: 12, weight: 'bold' },
                            align: 'top',
                            offset: 6,
                            formatter: (value) => value,
                        },
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 9,
                            pointLabels: { display: false },
                            ticks: { stepSize: 1 },
                        },
                    },
                },
            });
        }

        /* ================= DRAW DISC CHARTS ================= */
        function drawDISCCharts() {
            const canvasIds = ['disc1Canvas', 'disc2Canvas', 'disc3Canvas'];
            const datasets = [
                { D: discData.D1, I: discData.I1, S: discData.S1, C: discData.C1 },
                { D: discData.D2, I: discData.I2, S: discData.S2, C: discData.C2 },
                { D: discData.D3, I: discData.I3, S: discData.S3, C: discData.C3 },
            ];

            canvasIds.forEach((canvasId, index) => {
                const canvas = document.getElementById(canvasId);
                const data = datasets[index];

                // Set canvas to fill parent container
                const parent = canvas.parentElement;
                canvas.style.width = '100%';
                canvas.style.height = '100%';

                new Chart(canvas, {
                    type: 'line',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: ['D', 'I', 'S', 'C'],
                        datasets: [
                            {
                                label: `DISC ${index + 1}`,
                                data: [data.D, data.I, data.S, data.C],
                                borderColor: index === 0 ? '#ef4444' : index === 1 ? '#eab308' : '#22c55e',
                                backgroundColor:
                                    index === 0
                                        ? 'rgba(239, 68, 68, 0.1)'
                                        : index === 1
                                          ? 'rgba(234, 179, 8, 0.1)'
                                          : 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 3,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10,
                                left: 5,
                                right: 5,
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function (context) {
                                        return `Score: ${context.parsed.y}`;
                                    },
                                },
                            },
                            datalabels: {
                                color: '#111',
                                backgroundColor:
                                    index === 0
                                        ? 'rgba(239, 68, 68, 0.2)'
                                        : index === 1
                                          ? 'rgba(234, 179, 8, 0.2)'
                                          : 'rgba(34, 197, 94, 0.2)',
                                borderRadius: 4,
                                font: { size: 12, weight: 'bold' },
                                align: 'top',
                                offset: 6,
                                formatter: (value) => value,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: -10,
                                max: 10,
                                pointLabels: { display: false },
                                ticks: {
                                    stepSize: 20,
                                    font: { size: 11 },
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: true,
                                },
                            },
                            x: {
                                ticks: {
                                    font: { size: 13, weight: 'bold' },
                                    color: '#374151',
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: true,
                                },
                            },
                        },
                    },
                });
            });
        }

        /* ================= INIT ================= */
        function initCharts() {
            drawPAPIChart();
            drawDISCCharts();
        }

        if (typeof Chart === 'undefined') {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = () => {
                const s2 = document.createElement('script');
                s2.src = 'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2';
                s2.onload = initCharts;
                document.head.appendChild(s2);
            };
            document.head.appendChild(s);
        } else if (typeof ChartDataLabels === 'undefined') {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2';
            s.onload = initCharts;
            document.head.appendChild(s);
        } else {
            initCharts();
        }

        window.addEventListener('resize', () => location.reload());
    </script>
</div>
