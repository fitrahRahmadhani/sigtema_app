<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Start cards --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            {{-- Start cards data row 1 --}}
            <div class="order-1 col-span-1 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:order-1 lg:col-span-2">
                <div
                    class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-shadow hover:shadow-md"
                >
                    <div
                        class="color-gradient flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full shadow-sm"
                    >
                        <img src="{{ asset('assets/icon/total_employees.png') }}" class="w-7" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-4xl font-bold md:text-5xl">3000</p>
                        <p class="mt-2 text-base text-gray-500 md:mt-3">Total Employees</p>
                    </div>
                </div>
                <div
                    class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-shadow hover:shadow-md"
                >
                    <div
                        class="color-gradient flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full shadow-sm"
                    >
                        <img src="{{ asset('assets/icon/vacant_positions.png') }}" class="w-6" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-4xl font-bold md:text-5xl">4</p>
                        <p class="mt-2 text-base text-gray-500 md:mt-3">Vacant Positions</p>
                    </div>
                </div>
            </div>
            {{-- End cards data row 1 --}}

            {{-- Start card team performance --}}
            <div
                class="order-3 col-span-1 flex min-h-64 flex-col rounded-lg border border-gray-300 bg-white p-4 shadow-sm lg:order-2 lg:row-span-2"
            >
                <h6 class="text-primary-text mb-4 text-lg font-bold md:text-xl">Team Performance</h6>
                {{-- Line Chart Container --}}
                <div class="relative min-h-0 flex-1">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            {{-- End card team performance --}}

            {{-- Start cards data row 2 --}}
            <div class="order-2 col-span-1 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:order-3 lg:col-span-2">
                <div
                    class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-shadow hover:shadow-md"
                >
                    <div
                        class="color-gradient flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full shadow-sm"
                    >
                        <img src="{{ asset('assets/icon/new_employees.png') }}" class="w-4" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-4xl font-bold md:text-5xl">10</p>
                        <p class="mt-2 text-base text-gray-500 md:mt-3">New Employees</p>
                    </div>
                </div>
                <div
                    class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-shadow hover:shadow-md"
                >
                    <div
                        class="color-gradient flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full shadow-sm"
                    >
                        <img src="{{ asset('assets/icon/resigned_employees.png') }}" class="w-4" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-4xl font-bold md:text-5xl">20</p>
                        <p class="mt-2 text-base text-gray-500 md:mt-3">Resigned Employees</p>
                    </div>
                </div>
            </div>
            {{-- End cards data row 2 --}}
        </div>

        <div class="grid h-full w-full grid-cols-1 gap-4 lg:grid-cols-3">
            {{-- Vacant Positions Table --}}
            <div
                class="col-span-1 flex h-full w-full flex-col rounded-xl border border-gray-300 bg-white p-4 shadow-sm lg:col-span-2"
            >
                <h6 class="text-primary-text mb-4 text-lg font-bold md:text-xl">Vacant Positions</h6>

                {{-- Table Container --}}
                <div class="flex-1 overflow-auto">
                    {{-- Desktop View --}}
                    <div class="hidden min-w-full md:block">
                        <div
                            class="color-gradient mb-2 grid grid-cols-12 gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-white lg:text-base"
                        >
                            <div class="col-span-4">Nama Posisi</div>
                            <div class="col-span-2">Kode</div>
                            <div class="col-span-3">Unit Kerja</div>
                            <div class="col-span-3">Departemen</div>
                        </div>

                        <div class="space-y-2">
                            {{-- Position Item 1 --}}
                            <a
                                href="{{ route('vacant-positions.show') }}"
                                class="grid grid-cols-12 gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:border-[#721d21] hover:shadow-md"
                            >
                                <div class="col-span-4 flex items-center">
                                    <p class="line-clamp-2 text-sm font-medium text-gray-900 lg:text-base">
                                        Senior Software Engineer
                                    </p>
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <p class="text-sm font-medium text-gray-700 lg:text-base">SE-001</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Engineering</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Technology</p>
                                </div>
                            </a>

                            {{-- Position Item 2 --}}
                            <a
                                href="{{ route('vacant-positions.show') }}"
                                class="grid grid-cols-12 gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:border-[#721d21] hover:shadow-md"
                            >
                                <div class="col-span-4 flex items-center">
                                    <p class="line-clamp-2 text-sm font-medium text-gray-900 lg:text-base">
                                        Marketing Manager
                                    </p>
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <p class="text-sm font-medium text-gray-700 lg:text-base">MM-002</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Marketing</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Business</p>
                                </div>
                            </a>

                            {{-- Position Item 3 --}}
                            <a
                                href="{{ route('vacant-positions.show') }}"
                                class="grid grid-cols-12 gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:border-[#721d21] hover:shadow-md"
                            >
                                <div class="col-span-4 flex items-center">
                                    <p class="line-clamp-2 text-sm font-medium text-gray-900 lg:text-base">
                                        HR Specialist
                                    </p>
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <p class="text-sm font-medium text-gray-700 lg:text-base">HR-003</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Human Resources</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">HR</p>
                                </div>
                            </a>

                            {{-- Position Item 4 --}}
                            <a
                                href="{{ route('vacant-positions.show') }}"
                                class="grid grid-cols-12 gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:border-[#721d21] hover:shadow-md"
                            >
                                <div class="col-span-4 flex items-center">
                                    <p class="line-clamp-2 text-sm font-medium text-gray-900 lg:text-base">
                                        Sales Executive
                                    </p>
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <p class="text-sm font-medium text-gray-700 lg:text-base">SL-004</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Sales</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Business</p>
                                </div>
                            </a>

                            {{-- Position Item 5 --}}
                            <a
                                href="{{ route('vacant-positions.show') }}"
                                class="grid grid-cols-12 gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:border-[#721d21] hover:shadow-md"
                            >
                                <div class="col-span-4 flex items-center">
                                    <p class="line-clamp-2 text-sm font-medium text-gray-900 lg:text-base">Sales</p>
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <p class="text-sm font-medium text-gray-700 lg:text-base">SL-005</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Sales</p>
                                </div>
                                <div class="col-span-3 flex items-center">
                                    <p class="line-clamp-1 text-sm text-gray-600 lg:text-base">Business</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Mobile View --}}
                    <div class="space-y-3 md:hidden">
                        {{-- Position Item 1 --}}
                        <a
                            href="{{ route('vacant-positions.show') }}"
                            class="block rounded-lg border border-gray-200 bg-white p-3 transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="space-y-2">
                                <div class="border-b border-gray-100 pb-2">
                                    <p class="text-sm font-medium text-gray-500 uppercase">Nama Posisi</p>
                                    <p class="mt-1 text-base font-semibold text-gray-900">Senior Software Engineer</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Kode</p>
                                        <p class="mt-1 text-base font-medium text-gray-700">SE-001</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Unit</p>
                                        <p class="mt-1 text-base text-gray-600">Engineering</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase">Departemen</p>
                                    <p class="mt-1 text-base text-gray-600">Technology</p>
                                </div>
                            </div>
                        </a>

                        {{-- Position Item 2 --}}
                        <a
                            href="{{ route('vacant-positions.show') }}"
                            class="block rounded-lg border border-gray-200 bg-white p-3 transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="space-y-2">
                                <div class="border-b border-gray-100 pb-2">
                                    <p class="text-sm font-medium text-gray-500 uppercase">Nama Posisi</p>
                                    <p class="mt-1 text-base font-semibold text-gray-900">Marketing Manager</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Kode</p>
                                        <p class="mt-1 text-base font-medium text-gray-700">MM-002</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Unit</p>
                                        <p class="mt-1 text-base text-gray-600">Marketing</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase">Departemen</p>
                                    <p class="mt-1 text-base text-gray-600">Business</p>
                                </div>
                            </div>
                        </a>

                        {{-- Position Item 3 --}}
                        <a
                            href="{{ route('vacant-positions.show') }}"
                            class="block rounded-lg border border-gray-200 bg-white p-3 transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="space-y-2">
                                <div class="border-b border-gray-100 pb-2">
                                    <p class="text-sm font-medium text-gray-500 uppercase">Nama Posisi</p>
                                    <p class="mt-1 text-base font-semibold text-gray-900">HR Specialist</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Kode</p>
                                        <p class="mt-1 text-base font-medium text-gray-700">HR-003</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Unit</p>
                                        <p class="mt-1 text-base text-gray-600">Human Resources</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase">Departemen</p>
                                    <p class="mt-1 text-base text-gray-600">HR</p>
                                </div>
                            </div>
                        </a>

                        {{-- Position Item 4 --}}
                        <a
                            href="{{ route('vacant-positions.show') }}"
                            class="block rounded-lg border border-gray-200 bg-white p-3 transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="space-y-2">
                                <div class="border-b border-gray-100 pb-2">
                                    <p class="text-sm font-medium text-gray-500 uppercase">Nama Posisi</p>
                                    <p class="mt-1 text-base font-semibold text-gray-900">Sales Executive</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Kode</p>
                                        <p class="mt-1 text-base font-medium text-gray-700">SL-004</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase">Unit</p>
                                        <p class="mt-1 text-base text-gray-600">Sales</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase">Departemen</p>
                                    <p class="mt-1 text-base text-gray-600">Business</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- See More Button --}}
                <div class="mt-4 w-full border-t border-gray-200 pt-3">
                    <button class="w-full rounded-lg py-2 text-center transition-colors hover:bg-gray-50">
                        <a
                            href="{{ route('vacant-positions.index') }}"
                            class="font-medium text-gray-600 hover:text-[#721d21]"
                        >
                            See more
                        </a>
                    </button>
                </div>
            </div>

            {{-- Doughnut Chart --}}
            <div class="flex h-full w-full flex-col rounded-xl border border-gray-300 bg-white p-4 shadow-sm">
                <h6 class="text-primary-text mb-4 text-lg font-bold md:text-xl">Total Employees</h6>
                {{-- Doughnut Chart Container --}}
                <div class="relative flex-1">
                    <canvas id="EmployeesDoughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:navigated', () => {
            renderCharts();
        });

        function renderCharts() {
            // Cegah duplikasi chart jika user berpindah halaman
            if (window.myChartInstance) {
                window.myChartInstance.destroy();
            }
            if (window.myDoughnutInstance) {
                window.myDoughnutInstance.destroy();
            }

            // Line Chart dengan styling yang lebih baik
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Team Performance',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        borderColor: 'rgb(148, 37, 42)',
                        backgroundColor: 'rgba(148, 37, 42, 0.1)',
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(148, 37, 42)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(148, 37, 42)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        fill: true,
                        borderWidth: 3,
                    },
                    {
                        label: 'Department Performance',
                        data: [28, 48, 40, 19, 86, 27, 90],
                        borderColor: 'rgb(230, 84, 91)',
                        backgroundColor: 'rgba(230, 84, 91, 0.1)',
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(230, 84, 91)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(230, 84, 91)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        fill: true,
                        borderWidth: 3,
                    },
                    {
                        label: 'Units Performance',
                        data: [36, 21, 33, 45, 68, 79, 24],
                        borderColor: 'rgb(188, 76, 71)',
                        backgroundColor: 'rgba(188, 76, 71, 0.1)',
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(188, 76, 71)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(188, 76, 71)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        fill: true,
                        borderWidth: 3,
                    },
                ],
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 15,
                                font: {
                                    size: 11,
                                    family: "'Inter', sans-serif",
                                },
                            },
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 13,
                                weight: 'bold',
                            },
                            bodyFont: {
                                size: 12,
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 20,
                                font: {
                                    size: 11,
                                },
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false,
                            },
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11,
                                },
                            },
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                        },
                    },
                },
            };

            const ctx = document.getElementById('myChart');
            if (ctx) {
                window.myChartInstance = new Chart(ctx, config);
            }

            // Doughnut Chart dengan styling yang lebih baik
            const doughnutData = {
                labels: ['Management', 'Engineering', 'Sales', 'Marketing', 'HR'],
                datasets: [
                    {
                        label: 'Employees',
                        data: [300, 1200, 800, 500, 200],
                        backgroundColor: [
                            'rgb(148, 37, 42)',
                            'rgb(230, 84, 91)',
                            'rgb(188, 76, 71)',
                            'rgb(245, 158, 161)',
                            'rgb(255, 205, 210)',
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 15,
                        hoverBorderWidth: 4,
                    },
                ],
            };

            const doughnutCtx = document.getElementById('EmployeesDoughnutChart');
            if (doughnutCtx) {
                window.myDoughnutInstance = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: doughnutData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 12,
                                    font: {
                                        size: 11,
                                        family: "'Inter', sans-serif",
                                    },
                                },
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold',
                                },
                                bodyFont: {
                                    size: 12,
                                },
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    },
                                },
                            },
                        },
                    },
                });
            }
        }

        // Jalankan juga saat load pertama (untuk hard reload)
        document.addEventListener('DOMContentLoaded', renderCharts);
    </script>
</x-layouts.app>
