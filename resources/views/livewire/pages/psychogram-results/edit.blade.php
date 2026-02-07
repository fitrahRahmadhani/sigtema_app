<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('psychogram-results.index') }}">
            Psychogram Results
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Result</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4">
            <div
                class="color-gradient flex h-12 w-12 items-center justify-center self-start rounded-xl bg-gradient-to-br shadow-sm"
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
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Psychogram Result</h1>
                <p class="mt-1 text-sm text-gray-500">Update evaluation score for this result</p>
                <div class="mt-1 flex items-center gap-2">
                    <span class="text-sm text-gray-600">{{ $result->employee->company->name }}</span>
                </div>
            </div>
        </div>

        {{-- Result Info Summary --}}
        <div class="mb-6 rounded-lg border border-blue-100 bg-blue-50 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-900">Editing Result</p>
                    <div class="mt-1 flex flex-wrap items-center gap-2">
                        <span class="text-lg font-semibold text-blue-900">
                            {{ $result->aspect->aspect_name }}
                        </span>
                        <span class="rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                            {{ $result->aspect->aspect_code }}
                        </span>
                        <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                            Score: {{ $result->score }}
                        </span>
                        <span class="rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                            {{ $result->employee->company->name }}
                        </span>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    Created: {{ $result->created_at->format('M d, Y') }}
                    @if ($result->created_at != $result->updated_at)
                        <br />
                        Last updated: {{ $result->updated_at->format('M d, Y') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="update" class="space-y-8">
            {{-- Employee & Aspect Info --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Result Information</h3>

                {{-- Company Info (Only for Superadmin) --}}
                @if ($isSuperadmin)
                    <div class="mb-6">
                        <p class="mb-2 text-sm font-medium text-gray-700">Company</p>
                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                            <div class="flex items-center gap-3">
                                @if ($result->employee->company->logo_path)
                                    <img
                                        class="h-10 w-10 rounded-lg object-cover"
                                        src="{{ asset('storage/' . $result->employee->company->logo_path) }}"
                                        alt="{{ 'Logo ' . $result->employee->company->name }}"
                                    />
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
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
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $result->employee->company->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $result->employee->company->cocd ?? 'No COCD' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Employee Information --}}
                <div class="mb-6">
                    <p class="mb-2 text-sm font-medium text-gray-700">Employee</p>
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <div class="flex items-center gap-3">
                            @if ($result->employee->profile_path)
                                <img
                                    src="{{ Storage::url($result->employee->profile_path) }}"
                                    alt="{{ $result->employee->name }}"
                                    class="h-12 w-12 rounded-full border-2 border-white object-cover shadow-sm"
                                />
                            @else
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-800"
                                >
                                    {{ substr($result->employee->name, 0, 2) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $result->employee->name }}</p>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <span class="rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                        {{ $result->employee->employee_number }}
                                    </span>
                                    <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                        {{ $result->employee->company->name }}
                                    </span>
                                    @if ($result->employee->position)
                                        <span
                                            class="rounded-md bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800"
                                        >
                                            {{ $result->employee->position->name }}
                                        </span>
                                    @endif

                                    @if ($result->employee->personal_grade)
                                        <span
                                            class="rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800"
                                        >
                                            Grade: {{ $result->employee->personal_grade }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aspect Information --}}
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-700">Aspect</p>
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $result->aspect->aspect_name }}</p>
                                <p class="text-sm text-gray-600">{{ $result->aspect->aspect_code }}</p>
                                @if ($result->aspect->description)
                                    <p class="mt-2 text-sm text-gray-500">{{ $result->aspect->description }}</p>
                                @endif
                            </div>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                @if ($result->aspect->company)
                                    {{ $result->aspect->company->name }}
                                @else
                                    Global Aspect
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Score Update --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Update Score</h3>

                {{-- Score Legend --}}
                <div class="mb-6 rounded-lg border border-green-100 bg-green-50 p-4">
                    <h4 class="mb-2 text-sm font-semibold text-green-900">Score Rating Scale (1-5)</h4>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach ([1 => 'Very Poor', 2 => 'Poor', 3 => 'Average', 4 => 'Good', 5 => 'Excellent'] as $scoreValue => $label)
                            <div class="text-center">
                                <div
                                    class="@if ($scoreValue == 1) bg-red-100 text-red-800 @elseif ($scoreValue == 2) bg-orange-100 text-orange-800 @elseif ($scoreValue == 3) bg-yellow-100 text-yellow-800 @elseif ($scoreValue == 4) bg-lime-100 text-lime-800 @else bg-green-100 text-green-800 @endif mx-auto mb-1 flex h-8 w-8 items-center justify-center rounded-full"
                                >
                                    <span class="font-bold">{{ $scoreValue }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-700">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Current Score --}}
                <div class="mb-6">
                    <p class="mb-2 text-sm font-medium text-gray-700">Current Score</p>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span
                                    class="@if ($result->score == 1) text-red-600 @elseif ($result->score == 2) text-orange-600 @elseif ($result->score == 3) text-yellow-600 @elseif ($result->score == 4) text-lime-600 @else text-green-600 @endif text-3xl font-bold"
                                >
                                    {{ $result->score }}
                                </span>
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div
                                            class="@if($i <= $result->score) bg-[#721d21] @else bg-gray-200 @endif h-3 w-3 rounded-full"
                                        ></div>
                                    @endfor
                                </div>
                            </div>
                            <p
                                class="@if ($result->score == 1) text-red-600 @elseif ($result->score == 2) text-orange-600 @elseif ($result->score == 3) text-yellow-600 @elseif ($result->score == 4) text-lime-600 @else text-green-600 @endif mt-1 text-sm font-medium"
                            >
                                @if ($result->score == 1)
                                    Very Poor
                                @elseif ($result->score == 2)
                                    Poor
                                @elseif ($result->score == 3)
                                    Average
                                @elseif ($result->score == 4)
                                    Good
                                @else
                                    Excellent
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- New Score Input --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        New Score (1-5)
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        {{-- Radio Buttons for Score --}}
                        <div class="flex flex-1 gap-2">
                            @foreach ([1, 2, 3, 4, 5] as $scoreValue)
                                <label class="flex-1 cursor-pointer">
                                    <input
                                        type="radio"
                                        wire:model.live="score"
                                        value="{{ $scoreValue }}"
                                        class="peer hidden"
                                    />
                                    <div
                                        class="@if ($scoreValue == 1) hover:border-red-300 hover:bg-red-50 @elseif ($scoreValue == 2) hover:border-orange-300 hover:bg-orange-50 @elseif ($scoreValue == 3) hover:border-yellow-300 hover:bg-yellow-50 @elseif ($scoreValue == 4) hover:border-lime-300 hover:bg-lime-50 @else hover:border-green-300 hover:bg-green-50 @endif flex h-12 items-center justify-center rounded-lg border border-gray-300 text-center text-base font-medium transition-all peer-checked:border-[#721d21] peer-checked:bg-[#721d21] peer-checked:text-white"
                                    >
                                        {{ $scoreValue }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- Score Display --}}
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-lg border border-gray-300 bg-white"
                        >
                            <span
                                class="@if ($score == 1) text-red-600 @elseif ($score == 2) text-orange-600 @elseif ($score == 3) text-yellow-600 @elseif ($score == 4) text-lime-600 @elseif ($score == 5) text-green-600 @else text-gray-600 @endif text-2xl font-bold"
                            >
                                {{ $score ?? 0 }}
                            </span>
                        </div>
                    </div>
                    @error('score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Score Comparison --}}
                @if (isset($score) && $score != $result->score)
                    <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900">Score Change</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span
                                        class="@if ($result->score == 1) text-red-600 @elseif ($result->score == 2) text-orange-600 @elseif ($result->score == 3) text-yellow-600 @elseif ($result->score == 4) text-lime-600 @else text-green-600 @endif text-lg font-semibold"
                                    >
                                        {{ $result->score }}
                                    </span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4 text-gray-400"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
                                        />
                                    </svg>
                                    <span
                                        class="@if ($score == 1) text-red-600 @elseif ($score == 2) text-orange-600 @elseif ($score == 3) text-yellow-600 @elseif ($score == 4) text-lime-600 @else text-green-600 @endif text-lg font-semibold"
                                    >
                                        {{ $score }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $difference = $score - $result->score;
                                @endphp

                                <p class="text-sm font-medium text-blue-900">Difference</p>
                                <p
                                    class="@if($difference > 0) text-green-600 @elseif($difference < 0) text-red-600 @else text-gray-600 @endif text-lg font-bold"
                                >
                                    @if ($difference > 0)
                                        +{{ $difference }}
                                    @else
                                        {{ $difference }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    wire:click="cancel"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-gray-300 transition ring-inset hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="update"
                    class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg
                        wire:loading.remove
                        wire:target="update"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                        />
                    </svg>
                    <svg
                        wire:loading
                        wire:target="update"
                        class="h-5 w-5 animate-spin"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <span wire:loading.remove wire:target="update">Update Score</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
