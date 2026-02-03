<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('requirements.position') }}">
            Position Requirements
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Requirements</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6 text-white"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create Position Requirements</h1>
                    <p class="mt-1 text-sm text-gray-500">Add requirements for a specific position</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Position Selection --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Select Position</h3>

                @if ($isSuperadmin)
                    {{-- Company Selection for Superadmin --}}
                    <div class="mb-6">
                        <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <div class="relative">
                            <select
                                id="company_id"
                                wire:model.live="company_id"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            >
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }} ({{ $company->cocd }})
                                    </option>
                                @endforeach
                            </select>
                            <svg
                                class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                @endif

                {{-- Position Selection --}}
                <div>
                    <label for="position_id" class="mb-2 block text-sm font-medium text-gray-700">
                        Position
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="position_id"
                            wire:model.live="position_id"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                            @if (!$company_id) disabled @endif
                        >
                            <option value="">
                                {{ $company_id ? 'Select Position' : 'Select company first' }}
                            </option>
                            @foreach ($positions as $pos)
                                <option value="{{ $pos->id }}">
                                    [{{ $pos->position_code }}] {{ $pos->name }}
                                    @if ($pos->department)
                                        - {{ $pos->department }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <svg
                            class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    @error('position_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Selected Position Info --}}
                @if ($position)
                    <div class="mt-6 rounded-lg border border-green-200 bg-green-50 p-4">
                        <div class="flex items-start">
                            <svg class="mt-0.5 mr-3 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-green-800">Selected Position</h4>
                                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Position Code</p>
                                        <p class="font-medium text-gray-900">{{ $position->position_code }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Position Name</p>
                                        <p class="font-medium text-gray-900">{{ $position->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Job Grade</p>
                                        <p class="font-medium text-gray-900">{{ $position->job_grade }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Company</p>
                                        <p class="font-medium text-gray-900">{{ $position->company->name }}</p>
                                    </div>
                                    @if ($position->department)
                                        <div>
                                            <p class="text-xs text-gray-500">Department</p>
                                            <p class="font-medium text-gray-900">{{ $position->department }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Requirements Section --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Position Requirements</h3>
                    <button
                        type="button"
                        wire:click="addRequirementRow"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Row
                    </button>
                </div>

                <p class="mb-6 text-sm text-gray-500">
                    Add requirements for this position. You can add multiple rows before submitting.
                </p>

                @if (count($requirements) === 0)
                    <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="mx-auto h-12 w-12 text-gray-400"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <p class="mt-4 font-medium text-gray-500">Click "Add Row" to start adding requirements</p>
                    </div>
                @else
                    {{-- Requirements Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Requirement Name
                                        <span class="text-red-600">*</span>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Value
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($requirements as $index => $requirement)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <input
                                                type="text"
                                                wire:model="requirements.{{ $index }}.name"
                                                placeholder="e.g., Education, Experience, Certification"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            />
                                            @error("requirements.{$index}.name")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea
                                                wire:model="requirements.{{ $index }}.value"
                                                rows="2"
                                                placeholder="e.g., Bachelor's Degree in Computer Science, 3+ years experience, PMP Certification"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            ></textarea>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            @if (count($requirements) > 1)
                                                <button
                                                    type="button"
                                                    wire:click="removeRequirementRow({{ $index }})"
                                                    class="inline-flex items-center rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 transition-colors hover:bg-red-200"
                                                    title="Remove this row"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="mr-1 h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                        />
                                                    </svg>
                                                    Remove
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400">Cannot remove the only row</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    wire:target="save"
                    class="color-gradient inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg
                        wire:loading.remove
                        wire:target="save"
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
                        wire:target="save"
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
                    <span wire:loading.remove wire:target="save">
                        Create {{ count($requirements) }} Requirement(s)
                    </span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
