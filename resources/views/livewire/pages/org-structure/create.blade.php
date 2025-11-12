<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organization-structure.index') }}">
            Organization Structure (Chart)
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organization-structure.table') }}">
            Organization Structure (Table)
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Hierarchy</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-10">
        <!-- Header -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl">
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
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create Hierarchy</h1>
                    <p class="mt-1 text-sm text-gray-500">Define a new reporting relationship</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="save" class="space-y-6">
            <!-- Company Selection -->
            <div>
                <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                    Company
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select
                        id="company_id"
                        wire:model.live.live="company_id"
                        class="@error('company_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                    >
                        <option value="">Select a company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }} ({{ $company->cocd }})</option>
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
                @error('company_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-gray-500">Select the company for this hierarchy relationship</p>
            </div>

            @if ($company_id)
                <!-- Position Selection -->
                <div>
                    <label for="position_id" class="mb-2 block text-sm font-medium text-gray-700">
                        Position
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="position_id"
                            wire:model.live.live="position_id"
                            class="@error('position_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">Select a position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}">
                                    {{ $position->name }} ({{ $position->position_code }})
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

                    <p class="mt-1 text-xs text-gray-500">The subordinate position in this relationship</p>
                </div>

                <!-- Superior Selection -->
                <div>
                    <label for="superior_id" class="mb-2 block text-sm font-medium text-gray-700">
                        Reports To (Superior)
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="superior_id"
                            wire:model.live="superior_id"
                            @if (!$position_id) disabled @endif
                            class="@error('superior_id') @enderror @if(!$position_id) cursor-not-allowed @endif w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">Select a superior position</option>
                            @foreach ($superiors as $superior)
                                <option value="{{ $superior->id }}">
                                    {{ $superior->name }} ({{ $superior->position_code }})
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
                    @error('superior_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1 text-xs text-gray-500">The manager or supervisor for this position</p>
                </div>

                <!-- Relation Type Selection -->
                <div>
                    <label for="relation_type" class="mb-2 block text-sm font-medium text-gray-700">
                        Relation Type
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="relation_type"
                            wire:model.live="relation_type"
                            class="@error('relation_type') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="direct">Direct Report</option>
                            <option value="indirect">Indirect Report</option>
                            <option value="dotted">Dotted Line</option>
                            <option value="functional">Functional Report</option>
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
                    @error('relation_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1 text-xs text-gray-500">Define the nature of the reporting relationship</p>
                </div>

                <!-- Hierarchy Preview -->
                @if ($position_id && $superior_id)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <h3 class="mb-4 text-sm font-medium text-gray-700">Hierarchy Preview</h3>
                        <div class="flex items-center justify-center gap-4">
                            <div class="rounded-lg border-2 border-gray-300 bg-white px-6 py-4 text-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $positions->firstWhere('id', $position_id)?->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $positions->firstWhere('id', $position_id)?->position_code }}
                                </div>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-6 w-6 text-gray-400"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                                    />
                                </svg>
                                <span class="text-xs font-medium text-gray-500">Reports to</span>
                            </div>
                            <div class="rounded-lg border-2 border-[#721d21] bg-red-50 px-6 py-4 text-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $superiors->firstWhere('id', $superior_id)?->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $superiors->firstWhere('id', $superior_id)?->position_code }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium text-gray-700">
                                {{ ucfirst($relation_type) }} Relationship
                            </span>
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    <div class="flex gap-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-5 w-5 flex-shrink-0 text-red-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
                            />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Select a company first</h3>
                            <p class="mt-1 text-xs text-red-700">Please select a company to view available positions</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('organization-structure.table') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="color-gradient inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg px-6 py-2.5 text-sm font-medium text-white shadow-sm transition disabled:cursor-not-allowed disabled:opacity-50"
                    wire:loading.attr="disabled"
                    @if (!$company_id || !$position_id || !$superior_id) disabled @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
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
                    <span wire:loading.remove wire:target="save">Create Hierarchy</span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
