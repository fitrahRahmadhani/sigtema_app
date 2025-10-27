<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('career-path') }}">Career Path</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Candidates Comparison</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Candidates Comparison</h1>
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
                    <span class="hidden md:inline">Add Candidate</span>
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
                            >
                                Category
                            </th>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <th scope="col" class="relative min-w-[280px] bg-gray-50 px-6 py-3">
                                    <!-- Remove Column Button -->
                                    @if ($columnCount > 2)
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
                                                placeholder="Search candidate {{ $i + 1 }}..."
                                                class="w-full rounded-lg border border-gray-300 py-2 pr-10 pl-10 focus:border-red-900 focus:ring-2 focus:ring-red-900 focus:outline-none"
                                                @if(!empty($selectedCandidates[$i])) readonly @endif
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
                                            @if (! empty($selectedCandidates[$i]))
                                                <button
                                                    wire:click="clearCandidate({{ $i }})"
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
                                                @foreach ($searchResults[$i] as $candidate)
                                                    <button
                                                        wire:click="selectCandidate({{ $candidate->id }}, {{ $i }})"
                                                        class="flex w-full items-center gap-3 border-b border-gray-100 px-4 py-3 text-left transition last:border-b-0 hover:bg-gray-50"
                                                    >
                                                        <div
                                                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 font-semibold text-gray-600"
                                                        >
                                                            {{ strtoupper(substr($candidate->name, 0, 2)) }}
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="font-medium text-gray-900">
                                                                {{ $candidate->name }}
                                                            </p>
                                                            <p class="truncate text-sm text-gray-500">
                                                                {{ $candidate->position }}
                                                            </p>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Selected Candidate Info -->
                                        @if (! empty($selectedCandidates[$i]))
                                            <div
                                                class="border-primary-text bg-primary-text/10 mt-3 flex items-center gap-3 rounded-lg border p-3"
                                            >
                                                <div
                                                    class="bg-primary-text/30 text-primary-text flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full font-semibold"
                                                >
                                                    {{ strtoupper(substr($selectedCandidates[$i]->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0 flex-1 text-start">
                                                    <p class="text-primary-text font-semibold">
                                                        {{ $selectedCandidates[$i]->name }}
                                                    </p>
                                                    <p class="truncate text-sm text-gray-600">
                                                        {{ $selectedCandidates[$i]->position }}
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
                        <!-- Soft Competencies Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Soft Competencies
                            </td>
                        </tr>

                        <!-- Analytical Thinking -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">
                                Analytical Thinking
                            </td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedCandidates[$i]))
                                        @php
                                            $score = collect($selectedCandidates[$i]->softCompetencies)->firstWhere('type', 'analytical_thinking')->score ?? null;
                                        @endphp

                                        @if ($score)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $score }}</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                    <div
                                                        class="h-full rounded-full bg-gray-600"
                                                        style="width: {{ $score }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Communication -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Communication</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedCandidates[$i]))
                                        @php
                                            $score = collect($selectedCandidates[$i]->softCompetencies)->firstWhere('type', 'communication')->score ?? null;
                                        @endphp

                                        @if ($score)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $score }}</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                    <div
                                                        class="h-full rounded-full bg-gray-600"
                                                        style="width: {{ $score }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
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
                                    @if (! empty($selectedCandidates[$i]))
                                        @php
                                            $score = collect($selectedCandidates[$i]->softCompetencies)->firstWhere('type', 'leadership')->score ?? null;
                                        @endphp

                                        @if ($score)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $score }}</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                    <div
                                                        class="h-full rounded-full bg-gray-600"
                                                        style="width: {{ $score }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Problem Solving -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Problem Solving</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedCandidates[$i]))
                                        @php
                                            $score = collect($selectedCandidates[$i]->softCompetencies)->firstWhere('type', 'problem_solving')->score ?? null;
                                        @endphp

                                        @if ($score)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $score }}</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                    <div
                                                        class="h-full rounded-full bg-gray-600"
                                                        style="width: {{ $score }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Teamwork -->
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 pl-10 font-medium text-gray-900">Teamwork</td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 text-gray-700">
                                    @if (! empty($selectedCandidates[$i]))
                                        @php
                                            $score = collect($selectedCandidates[$i]->softCompetencies)->firstWhere('type', 'teamwork')->score ?? null;
                                        @endphp

                                        @if ($score)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $score }}</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                                    <div
                                                        class="h-full rounded-full bg-gray-600"
                                                        style="width: {{ $score }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
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
                                    @if (! empty($selectedCandidates[$i]) && ! empty($selectedCandidates[$i]->technicalCompetencies))
                                        <ul class="space-y-1">
                                            @foreach ($selectedCandidates[$i]->technicalCompetencies as $skill)
                                                <li class="flex items-center gap-2">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-sm font-medium text-gray-700"
                                                    >
                                                        {{ $skill->name }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">{{ $skill->level }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">
                                            {{ ! empty($selectedCandidates[$i]) ? 'N/A' : '-' }}
                                        </span>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        <!-- Innovation Section -->
                        <tr class="color-gradient">
                            <td colspan="{{ $columnCount + 1 }}" class="left-0 px-6 py-3 font-semibold text-white">
                                Innovation
                            </td>
                        </tr>
                        <tr>
                            <td class="left-0 bg-white px-6 py-4 font-medium text-gray-900"></td>
                            @for ($i = 0; $i < $columnCount; $i++)
                                <td class="px-6 py-4 align-top text-gray-700">
                                    @if (! empty($selectedCandidates[$i]) && ! empty($selectedCandidates[$i]->innovations))
                                        <ul class="space-y-3">
                                            @foreach ($selectedCandidates[$i]->innovations as $innovation)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="font-medium text-gray-900">{{ $innovation->title }}</p>
                                                    <p class="mt-1 text-sm text-gray-600">
                                                        {{ $innovation->description }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">
                                            {{ ! empty($selectedCandidates[$i]) ? 'N/A' : '-' }}
                                        </span>
                                    @endif
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
                                    @if (! empty($selectedCandidates[$i]) && ! empty($selectedCandidates[$i]->positionHistories))
                                        <ul class="space-y-3">
                                            @foreach ($selectedCandidates[$i]->positionHistories as $position)
                                                <li class="border-l-2 border-gray-500 pl-3">
                                                    <p class="font-medium text-gray-900">{{ $position->title }}</p>
                                                    <p class="text-sm text-gray-600">{{ $position->company }}</p>
                                                    <p class="text-sm text-gray-400">
                                                        {{ $position->start_date }} -
                                                        {{ $position->end_date ?? 'Present' }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">
                                            {{ ! empty($selectedCandidates[$i]) ? 'N/A' : '-' }}
                                        </span>
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
                                    @if (! empty($selectedCandidates[$i]) && ! empty($selectedCandidates[$i]->educations))
                                        <ul class="space-y-3">
                                            @foreach ($selectedCandidates[$i]->educations as $education)
                                                <li class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                    <p class="font-medium text-gray-900">{{ $education->degree }}</p>
                                                    <p class="text-sm text-gray-600">{{ $education->institution }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        Class of {{ $education->graduation_year }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">
                                            {{ ! empty($selectedCandidates[$i]) ? 'N/A' : '-' }}
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
                                    @if (! empty($selectedCandidates[$i]) && ! empty($selectedCandidates[$i]->mcuHistories))
                                        <ul class="space-y-2">
                                            @foreach ($selectedCandidates[$i]->mcuHistories as $mcu)
                                                <li class="flex items-center gap-2">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="size-4 text-green-600"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                                        />
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $mcu->date }}</p>
                                                        <p class="text-sm">
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-sm font-medium text-green-800"
                                                            >
                                                                {{ $mcu->status }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">
                                            {{ ! empty($selectedCandidates[$i]) ? 'N/A' : '-' }}
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
