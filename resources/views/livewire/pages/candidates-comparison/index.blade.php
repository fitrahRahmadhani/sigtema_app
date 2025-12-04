<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Employee Comparison</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
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
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Employee Comparison</h1>
            </div>

            <!-- Add Column Button -->
            @if ($columnCount < 5)
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
                    <span class="hidden md:inline">Add Employee</span>
                </button>
            @endif
        </div>

        <!-- Comparison Table -->
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
                                <th scope="col" class="relative min-w-[280px] content-start bg-gray-50 px-6 py-3">
                                    <!-- Remove Column Button -->
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
                                        <!-- Search Input -->
                                        <div class="relative">
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

                                        <!-- Search Dropdown -->
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

                                        <!-- Selected Employee Info -->
                                        @if (! empty($selectedEmployees[$i]))
                                            <div
                                                class="border-primary-text bg-primary-text/10 mt-3 flex items-center gap-3 rounded-lg border p-3"
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
                        <!-- Basic Information Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Basic Information
                            </td>
                        </tr>

                        <!-- Email -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Email</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <span class="font-medium">{{ $selectedEmployees[$i]->email }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Phone -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Phone</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <span class="font-medium">{{ $selectedEmployees[$i]->phone }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Personal Grade -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Personal Grade</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <span class="font-medium">{{ $selectedEmployees[$i]->personal_grade }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Band -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Band</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <span class="font-medium">{{ $selectedEmployees[$i]->band }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Hire Date -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Hire Date</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
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

                        <!-- Personality Information Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Personality Information
                            </td>
                        </tr>

                        <!-- MBTI -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">MBTI</td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                        </tr>

                        <!-- DISC -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">DISC</td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                        </tr>

                        <!-- PAPI -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">PAPI</td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                            <td><span class="px-6 py-4 text-gray-700">-</span></td>
                        </tr>

                        <!-- Soft Competencies Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Soft Competencies
                            </td>
                        </tr>

                        <!-- Communication -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Communication</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                <div
                                                    class="h-full rounded-full bg-gray-600"
                                                    style="width: {{ rand(70, 95) }}%"
                                                ></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-600">
                                                {{ rand(70, 95) }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Leadership -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Leadership</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedEmployees[$i]))
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                <div
                                                    class="h-full rounded-full bg-gray-600"
                                                    style="width: {{ rand(65, 90) }}%"
                                                ></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-600">
                                                {{ rand(65, 90) }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Technical Competency Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Technical Competency
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
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

                        <!-- Innovation Section (Blank) -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Innovation
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    <span class="text-gray-400">-</span>
                                </td>
                            @endfor
                        </tr>

                        <!-- Position History Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Position History
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->positionHistories && $selectedEmployees[$i]->positionHistories->count() > 0)
                                        <ul class="space-y-3">
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

                        <!-- Education Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Education
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->educations && $selectedEmployees[$i]->educations->count() > 0)
                                        <ul class="space-y-3">
                                            @foreach ($selectedEmployees[$i]->educations as $education)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="font-medium text-gray-900">{{ $education->degree }}</p>
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

                        <!-- MCU History Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                MCU History
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if (! empty($selectedEmployees[$i]) && $selectedEmployees[$i]->mcuResults && $selectedEmployees[$i]->mcuResults->count() > 0)
                                        <div class="flex w-fit flex-col gap-2">
                                            @foreach ($selectedEmployees[$i]->mcuResults as $mcu)
                                                @php
                                                    $resultColor = $mcu->result_color ?? 'gray';
                                                    $colorClass =
                                                        [
                                                            'green' => 'bg-green-100 text-green-800 border-green-200',
                                                            'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                            'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                            'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                            'red' => 'bg-red-100 text-red-800 border-red-200',
                                                            'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                        ][$resultColor] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                @endphp

                                                <div
                                                    class="{{ $colorClass }} inline-flex items-center gap-1 rounded-full border px-3 py-1 text-sm"
                                                >
                                                    <span class="font-semibold">•</span>
                                                    <span class="font-medium">{{ $mcu->result ?? 'N/A' }}</span>
                                                    <span>|</span>
                                                    <span>{{ $mcu->year }}</span>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
