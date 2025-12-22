<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('requirements.position') }}">
            Position Requirements
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Requirements</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Position Requirements</h1>
                    <p class="mt-1 text-sm text-gray-500">Update requirements for this position</p>
                </div>
            </div>

            <!-- Save All Button -->
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
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
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span wire:loading.remove wire:target="save">Save All Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>

        <!-- Position Information -->
        <div class="mb-8 rounded-lg border border-green-200 bg-green-50 p-6">
            <div class="flex items-start">
                <svg class="mt-0.5 mr-3 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <div class="flex-1">
                    <h4 class="mb-3 font-semibold text-green-800">Position Information</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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

                        @if ($position->band)
                            <div>
                                <p class="text-xs text-gray-500">Band</p>
                                <p class="font-medium text-gray-900">{{ $position->band }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Requirements -->
        <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Existing Requirements</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Edit the requirements below. Changes are saved individually or click "Save All Changes" to save
                        all at once.
                    </p>
                </div>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-800">
                    {{ count($requirements) }} requirements
                </span>
            </div>

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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No requirements found for this position</p>
                    <p class="mt-1 text-sm text-gray-400">Add new requirements using the form below</p>
                </div>
            @else
                <!-- Requirements Table -->
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
                                    Actions
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
                                            wire:model.lazy="requirements.{{ $index }}.name"
                                            wire:change.debounce.500ms="updateRequirement({{ $index }})"
                                            placeholder="Requirement name"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <textarea
                                            wire:model.lazy="requirements.{{ $index }}.value"
                                            wire:change.debounce.500ms="updateRequirement({{ $index }})"
                                            rows="2"
                                            placeholder="Requirement value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        ></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                wire:click="updateRequirement({{ $index }})"
                                                wire:loading.attr="disabled"
                                                wire:target="updateRequirement({{ $index }})"
                                                class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700 transition-colors hover:bg-blue-200 disabled:opacity-50"
                                                title="Save changes for this requirement"
                                            >
                                                <svg
                                                    wire:loading.remove
                                                    wire:target="updateRequirement({{ $index }})"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="mr-1 h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 13l4 4L19 7"
                                                    />
                                                </svg>
                                                <svg
                                                    wire:loading
                                                    wire:target="updateRequirement({{ $index }})"
                                                    class="mr-1 h-4 w-4 animate-spin"
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
                                                Save
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="confirmDelete({{ $requirement['id'] }}, '{{ addslashes($requirement['name']) }}')"
                                                class="inline-flex items-center rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 transition-colors hover:bg-red-200"
                                                title="Delete this requirement"
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
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Auto-save Notice -->
                <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-3">
                    <div class="flex items-center">
                        <svg class="mr-2 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <p class="text-sm text-blue-700">
                            <strong>Auto-save enabled:</strong>
                            Changes are automatically saved 0.5 seconds after you stop typing. You can also click the
                            "Save" button next to each requirement.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Add New Requirements -->
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Add New Requirements</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Add multiple requirements at once. You can add more rows before submitting.
                    </p>
                </div>
                <button
                    type="button"
                    wire:click="addNewRequirementRow"
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

            <!-- Dynamic Form for New Requirements -->
            @if (count($newRequirements) === 0)
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
                    <p class="mt-4 font-medium text-gray-500">Click "Add Row" to start adding new requirements</p>
                </div>
            @else
                <!-- New Requirements Table -->
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
                            @foreach ($newRequirements as $index => $requirement)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            type="text"
                                            wire:model="newRequirements.{{ $index }}.name"
                                            placeholder="e.g., Education, Experience, Certification"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <textarea
                                            wire:model="newRequirements.{{ $index }}.value"
                                            rows="2"
                                            placeholder="e.g., Bachelor's Degree in Computer Science, 3+ years experience"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        ></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                                        @if (count($newRequirements) > 1)
                                            <button
                                                type="button"
                                                wire:click="removeNewRequirementRow({{ $index }})"
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

                <!-- Add Button -->
                <div class="mt-6">
                    <button
                        type="button"
                        wire:click="addRequirements"
                        wire:loading.attr="disabled"
                        wire:target="addRequirements"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-green-700 disabled:opacity-50"
                    >
                        <svg
                            wire:loading.remove
                            wire:target="addRequirements"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <svg
                            wire:loading
                            wire:target="addRequirements"
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
                        <span wire:loading.remove wire:target="addRequirements">
                            Add {{ count($newRequirements) }} Requirement(s)
                        </span>
                        <span wire:loading wire:target="addRequirements">Adding...</span>
                    </button>
                </div>
            @endif
        </div>
        <!-- Form Actions -->
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
                wire:click="save"
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
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
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
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span wire:loading.remove wire:target="save">Save All Changes</span>
                <span wire:loading wire:target="save">Saving All...</span>
            </button>
        </div>
    </div>
    <x-ui.modals.delete-confirmation />
</div>
