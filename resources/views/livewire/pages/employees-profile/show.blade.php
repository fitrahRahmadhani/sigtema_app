<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
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
                <h6 class="text-xl font-bold text-gray-900 sm:text-2xl md:text-3xl">{{ $employee->name }}</h6>
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
                            <span class="text-red-500 italic">placeholder</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full space-y-6 md:space-y-8">
            <!-- Two Column Section: Soft Competencies, Technical Competencies -->
            <div class="grid w-full grid-cols-1 gap-6 md:gap-8 lg:grid-cols-2">
                {{-- PAPI Kostick --}}
                <div class="w-full">
                    <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">PAPI Kostick</p>
                    </div>

                    <div class="rounded-b-lg border border-t-0 border-gray-300 bg-white p-2 md:p-4">
                        {{-- ================= CANVAS ================= --}}
                        <div class="flex items-center justify-center p-2" wire:ignore>
                            <div
                                class="relative aspect-square w-full max-w-[650px] rounded-full border-2 border-dashed"
                            >
                                <canvas id="workDirectionCanvas" class="h-full w-full"></canvas>
                            </div>
                        </div>
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
                    <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">Technical Competencies</p>
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
                                                <p class="mt-2 text-sm font-medium text-gray-400">
                                                    No technical competencies found
                                                </p>
                                                <p class="text-xs text-gray-300">
                                                    Add certifications or technical skills
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
                                            <p class="mt-2 text-sm font-medium text-gray-400">
                                                No education records found
                                            </p>
                                            <p class="text-xs text-gray-300">Add educational background information</p>
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
                                            <p class="mt-2 text-sm font-medium text-gray-400">
                                                No position history records found
                                            </p>
                                            <p class="text-xs text-gray-300">Add position history information</p>
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
                <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                    <p class="text-base font-semibold text-white sm:text-lg">Innovations</p>
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
                                    class="w-6/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Innovation Title
                                </th>
                                <th
                                    class="w-2/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Award
                                </th>
                                <th
                                    class="w-2/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Category
                                </th>
                                <th class="w-1/12 px-3 py-2 font-semibold text-gray-600 sm:px-4">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="w-1/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    Juara 1
                                </td>
                                <td class="w-6/12 border-r border-gray-300 px-3 py-2 text-start text-gray-800 sm:px-4">
                                    Pengembangan Aset Inovasi Perusahaan Yang Menghasilkan Multiplier Effect Dari
                                    Replikasi Inovasi Melalui Program ReportaSIG
                                </td>
                                <td class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    SIG Innovation Award Tahun 2025
                                </td>
                                <td class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    HC Optimization
                                </td>
                                <td class="w-1/12 px-3 py-2 text-center text-gray-600 sm:px-4">Inisiator</td>
                            </tr>
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="w-1/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    Juara 2
                                </td>
                                <td class="w-6/12 border-r border-gray-300 px-3 py-2 text-start text-gray-800 sm:px-4">
                                    Optimizing Data Warehouse Event Di Unit of Knowledge Management & Innovation Untuk
                                    Executive Dashboard Learning Menggunakan Fitur Office 365
                                </td>
                                <td class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    SIG Innovation Award Tahun 2024
                                </td>
                                <td class="w-2/12 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4">
                                    HC Optimization
                                </td>
                                <td class="w-1/12 px-3 py-2 text-center text-gray-600 sm:px-4">Peserta</td>
                            </tr>
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
                                                <p class="mt-2 text-sm font-medium text-gray-400">
                                                    No MCU data available
                                                </p>
                                                <p class="text-xs text-gray-300">Add MCU results to display here</p>
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
                    <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                        <p class="text-base font-semibold text-white sm:text-lg">Burnout Test Result</p>
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
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">50</td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-2/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Depersonalisasi
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">50</td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-2/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Penurunan Prestasi
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">50</td>
                                </tr>
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-2/3 border-r border-gray-300 px-3 py-2 font-bold text-gray-800 sm:px-4"
                                    >
                                        Total Skor
                                    </td>
                                    <td class="px-3 py-2 font-bold text-gray-600 sm:px-4">150</td>
                                </tr>
                                <!-- Baris Penafsiran -->
                                <tr class="border-t border-gray-300 transition-colors">
                                    <td colspan="2" class="px-3 py-3 sm:px-4">
                                        <div class="mb-1 font-semibold text-gray-800">Penafsiran Hasil:</div>
                                        <div class="text-sm text-gray-700">
                                            Berdasarkan skor total 150, Anda berada dalam kategori burnout tinggi.
                                            Disarankan untuk berkonsultasi dengan profesional kesehatan mental dan
                                            mempertimbangkan langkah-langkah pengurangan stres serta penyeimbangan
                                            kehidupan kerja.
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
        /* ================= DATA ================= */

        const workData = @json($workDirectionData);

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
                desc: 'Peranan\nSbg\nPemim',
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

        /* ================= DRAW ================= */

        function drawChart() {
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
                if (start <= end) {
                    return (start + end) / 2;
                }

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

                const avg = scores.reduce((a, b) => a + b, 0) / scores.length;
                return avg.toFixed(1); // 1 angka desimal
            }

            /* ===== OUTER CATEGORY RING ===== */
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

            /* ===== CATEGORY TEXT (CURVED) ===== */
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

            /* ===== LETTER SEGMENTS ===== */
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

            /* ===== DESCRIPTION ===== */
            segments.forEach((s) => {
                const rad = ((s.angle - 90) * Math.PI) / 180;

                const x = cx + descR * Math.cos(rad);
                const y = cy + descR * Math.sin(rad);

                ctx.save();
                ctx.translate(x, y);

                let rotate = rad + Math.PI / 2;

                if (s.angle > 90 && s.angle < 270) {
                    rotate += Math.PI;
                }

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

        /* ===== RADAR ===== */
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
                            font: {
                                size: 12,
                                weight: 'bold',
                            },
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

        /* ===== INIT ===== */
        if (typeof Chart === 'undefined') {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = drawChart;
            document.head.appendChild(s);
        } else drawChart();

        window.addEventListener('resize', () => location.reload());
    </script>
</div>
