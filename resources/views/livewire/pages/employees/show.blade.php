<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('employees.index') }}">Employees</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Employee</flux:breadcrumbs.item>
    </flux:breadcrumbs>

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
                            <br />
                            DISC:
                            <span class="text-red-500 italic">placeholder</span>
                            <br />
                            PAPI:
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
                                    <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Analytical Thinking
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Ability to break down complex problems
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Communication
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Clear and effective expression of ideas
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Leadership
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">Ability to guide and inspire teams</td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Problem Solving
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Finding effective solutions to challenges
                                    </td>
                                </tr>
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td class="w-1/3 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">
                                        Teamwork
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Collaborating effectively with others
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
                                        {{ $positionHistory->start_date }} - {{ $positionHistory->end_date }}
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
                                    <td class="px-3 py-2 font-bold text-gray-600 sm:px-4">200</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
