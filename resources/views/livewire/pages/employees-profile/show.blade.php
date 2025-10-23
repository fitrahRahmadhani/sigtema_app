<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('employees-profile.index') }}">Employees Profile</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>M. Habib Sulaiman's Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-6 lg:p-10">
        <!-- Header -->
        <div class="mb-8 flex flex-col items-start gap-6 lg:flex-row lg:gap-8">
            <div
                class="flex h-32 w-28 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100 sm:h-44 sm:w-40"
            >
                <img src="https://avatar.iran.liara.run/public/12" alt="" class="h-full w-full object-cover" />
            </div>
            <div class="h-fit w-full flex-1">
                <h6 class="text-xl font-bold text-gray-900 sm:text-2xl md:text-3xl">M. Habib Sulaiman</h6>
                <div class="mt-4 grid h-full w-full grid-cols-2 gap-4 sm:mt-6 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Employee ID</p>
                        <p class="">113231001</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Position</p>
                        <p class="">Band 4</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Section/Unit</p>
                        <p class="">
                            Human Capital
                            <br />
                            Talent Acquistion & Mobility
                        </p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Hire Date</p>
                        <p class="">21 Agustus 2020</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Office</p>
                        <p class="">Tuban, Jawa Timur</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Contact</p>
                        <p class="">Band 4</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Usia</p>
                        <p class="">25 tahun</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Job Grade</p>
                        <p class="">13</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Personal Grade</p>
                        <p class="">13</p>
                    </div>
                    <div class="w-full">
                        <p class="text-primary-text font-semibold">Personality</p>
                        <p class="">
                            ESFJ DISC
                            <br />
                            PAPI KOSTICK
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full space-y-6 md:space-y-8">
            <div class="grid w-full grid-cols-1 gap-6 md:gap-8 lg:grid-cols-2">
                <!-- Soft Competencies -->
                <div class="w-full">
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
                <div class="w-full">
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
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        2025
                                    </td>
                                    <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">BNSP</td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Ability to break down complex problems
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        2024
                                    </td>
                                    <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">BNSP</td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Clear and effective expression of ideas
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        2023
                                    </td>
                                    <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">BNSP</td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">Ability to guide and inspire teams</td>
                                </tr>
                                <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        2022
                                    </td>
                                    <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">BNSP</td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Finding effective solutions to challenges
                                    </td>
                                </tr>
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td
                                        class="w-1/6 border-r border-gray-300 px-3 py-2 text-center text-gray-800 sm:px-4"
                                    >
                                        2021
                                    </td>
                                    <td class="w-2/6 border-r px-3 py-2 text-gray-600 sm:px-4">BNSP</td>
                                    <td class="px-3 py-2 text-gray-600 sm:px-4">
                                        Collaborating effectively with others
                                    </td>
                                </tr>
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
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    2021 - 2024
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">S1</td>
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    Teknik Informatika
                                </td>
                                <td class="px-3 py-3 text-gray-600 sm:px-4">Universitas Brawijaya</td>
                            </tr>
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    2019 - 2021
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    SMA
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    IPA
                                </td>
                                <td class="px-3 py-3 text-gray-600 sm:px-4">SMAN 1 Banyuwangi</td>
                            </tr>
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
                                    Section
                                </th>
                                <th class="px-3 py-2 font-semibold text-gray-800 sm:px-4">Company</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    01-01-2018 - 31-12-2020
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">
                                    Sales Representative
                                </td>
                                <td class="border-r px-3 py-3 text-gray-600 sm:px-4">
                                    Section of East Java Sales 31-Sby Barat
                                </td>
                                <td class="px-3 py-3 text-center text-gray-800 sm:px-4">PT. SEMEN INDONESIA</td>
                            </tr>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    10-10-2021 - 10-10-2024
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">
                                    Manager of Area Sales
                                </td>
                                <td class="border-r px-3 py-3 text-gray-600 sm:px-4">
                                    Section of Area Sales 51 52-Sby Madura
                                </td>
                                <td class="px-3 py-3 text-center text-gray-800 sm:px-4">PT. SEMEN INDONESIA</td>
                            </tr>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    01-01-2016 - 31-12-2017
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">
                                    Marketing Analyst
                                </td>
                                <td class="border-r px-3 py-3 text-gray-600 sm:px-4">
                                    Section of Strategic Marketing Planning
                                </td>
                                <td class="px-3 py-3 text-center text-gray-800 sm:px-4">PT. SEMEN INDONESIA</td>
                            </tr>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    01-02-2014 - 31-12-2015
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">
                                    Assistant Sales Supervisor
                                </td>
                                <td class="border-r px-3 py-3 text-gray-600 sm:px-4">
                                    Section of Sales Support Jawa Timur
                                </td>
                                <td class="px-3 py-3 text-center text-gray-800 sm:px-4">PT. SEMEN INDONESIA</td>
                            </tr>
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-300 px-3 py-3 text-center text-gray-800 sm:px-4">
                                    01-06-2010 - 31-01-2014
                                </td>
                                <td class="border-r border-gray-300 px-3 py-3 text-gray-800 sm:px-4">Sales Officer</td>
                                <td class="border-r px-3 py-3 text-gray-600 sm:px-4">
                                    Section of Regional Sales Madura
                                </td>
                                <td class="px-3 py-3 text-center text-gray-800 sm:px-4">PT. SEMEN INDONESIA</td>
                            </tr>
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

            {{-- MCU --}}
            <div class="w-full">
                <div class="color-gradient w-full rounded-t-lg px-4 py-3">
                    <p class="text-base font-semibold text-white sm:text-lg">MCU History</p>
                </div>
                <div class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-100 text-center">
                                <th
                                    class="w-1/12 border-r border-gray-300 px-3 py-2 font-semibold text-gray-800 sm:px-4"
                                >
                                    Year
                                </th>
                                <th class="px-3 py-2 font-semibold text-gray-600 sm:px-4">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-300 transition-colors hover:bg-gray-50">
                                <td class="w-1/12 border-r border-gray-300 px-3 py-2 text-gray-800 sm:px-4">2025</td>
                                <td class="px-3 py-2 text-gray-600 sm:px-4">K1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
