<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Start cards --}}
        <div class="grid grid-cols-3 gap-4">
            {{-- Start cards data row 1 --}}
            <div class="order-1 col-span-3 grid grid-cols-2 gap-4 md:order-1 md:col-span-2">
                <div class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4">
                    <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-full">
                        <img src="{{ asset('assets/icon/total_employees.png') }}" class="w-7" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-5xl font-bold">3000</p>
                        <p class="mt-3 text-gray-500">Total Employees</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4">
                    <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-full">
                        <img src="{{ asset('assets/icon/vacant_positions.png') }}" class="w-6" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-5xl font-bold">4</p>
                        <p class="mt-3 text-gray-500">Vacant Positions</p>
                    </div>
                </div>
            </div>
            {{-- End cards data row 1 --}}

            {{-- Start card team performance --}}
            <div
                class="order-3 col-span-3 row-span-2 flex min-h-64 flex-col rounded-lg border border-gray-300 bg-white p-4 md:order-2 md:col-span-1"
            >
                <h6 class="text-primary-text mb-4 text-xl font-bold">Team Performance</h6>
                {{-- Line Chart Container --}}
                <div class="relative min-h-0 flex-1">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            {{-- End card team performance --}}

            {{-- Start cards data row 2 --}}
            <div class="order-2 col-span-3 grid grid-cols-2 gap-4 md:order-3 md:col-span-2">
                <div class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4">
                    <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-full">
                        <img src="{{ asset('assets/icon/new_employees.png') }}" class="w-4" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-5xl font-bold">10</p>
                        <p class="mt-3 text-gray-500">New Employees</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-lg border border-gray-300 bg-white p-4">
                    <div class="color-gradient flex h-10 w-10 items-center justify-center rounded-full">
                        <img src="{{ asset('assets/icon/resigned_employees.png') }}" class="w-4" alt="" />
                    </div>
                    <div class="flex flex-col">
                        <p class="text-primary-text text-5xl font-bold">20</p>
                        <p class="mt-3 text-gray-500">Resigned Employees</p>
                    </div>
                </div>
            </div>
            {{-- End cards data row 2 --}}
        </div>

        <div class="grid h-full w-full grid-cols-3 gap-4">
            {{-- Employees Table --}}
            <div class="col-span-2 flex h-full w-full flex-col rounded-xl border border-gray-300 bg-white p-4">
                <h6 class="text-primary-text mb-4 text-xl font-bold">Employees</h6>
                <div class="flex-1 bg-yellow-200"></div>
                <div class="w-full">
                    <button class="w-full py-2 text-center">
                        <a href="" class="font-medium text-gray-400 hover:text-gray-600">See more</a>
                    </button>
                </div>
            </div>

            {{-- Doughnut Chart --}}
            <div class="flex h-full w-full flex-col rounded-xl border border-gray-300 bg-white p-4">
                <h6 class="text-primary-text mb-4 text-xl font-bold">Total Employees</h6>
                {{-- Doughnut Chart Container --}}
                <div class="relative flex-1">
                    <canvas id="EmployeesDoughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line Chart
        const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Team Performance',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    fill: false,
                    borderColor: 'rgb(148 37 42)',
                    backgroundColor: 'rgba(148, 37, 42, 0.2)',
                    tension: 0,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Department Performance',
                    data: [28, 48, 40, 19, 86, 27, 90],
                    fill: false,
                    borderColor: 'rgb(230 84 91)',
                    backgroundColor: 'rgba(230, 84, 91, 0.2)',
                    tension: 0,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Units Performance',
                    data: [36, 21, 33, 45, 68, 79, 24],
                    fill: false,
                    borderColor: 'rgb(188 76 71)',
                    backgroundColor: 'rgba(188, 76, 71, 0.2)',
                    tension: 0,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
            ],
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                        },
                    },
                    title: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 20,
                        },
                    },
                },
            },
        };

        // Render Line Chart
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, config);

        // Doughnut Chart Configuration
        const doughnutData = {
            labels: ['Management', 'Engineering', 'Sales', 'Marketing', 'HR'],
            datasets: [
                {
                    label: 'Employees by Department',
                    data: [300, 1200, 800, 500, 200],
                    backgroundColor: [
                        'rgb(148, 37, 42)',
                        'rgb(230, 84, 91)',
                        'rgb(188, 76, 71)',
                        'rgb(245, 158, 161)',
                        'rgb(255, 205, 210)',
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 10,
                },
            ],
        };

        const doughnutConfig = {
            type: 'doughnut',
            data: doughnutData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            font: {
                                size: 12,
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + ' employees';
                                return label;
                            },
                        },
                    },
                },
            },
        };

        // Render Doughnut Chart
        const doughnutCtx = document.getElementById('EmployeesDoughnutChart').getContext('2d');
        const employeesDoughnutChart = new Chart(doughnutCtx, doughnutConfig);
    });
</script>
