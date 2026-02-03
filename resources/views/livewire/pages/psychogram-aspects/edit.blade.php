<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('psychogram-aspects.index') }}">
            Psychogram Aspects
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Aspect: {{ $aspect->aspect_name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4">
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
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Psychogram Aspect</h1>
                <p class="mt-1 text-sm text-gray-500">Update aspect information and configuration</p>

                @if ($selectedCompany)
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Aspect Info Summary --}}
        <div class="mb-6 rounded-lg border border-blue-100 bg-blue-50 p-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-900">Editing Aspect</p>
                    <div class="mt-1 flex flex-wrap items-center gap-2">
                        <span class="text-lg font-semibold text-blue-900">{{ $aspect->aspect_name }}</span>
                        <span class="rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                            {{ $aspect->aspect_code }}
                        </span>
                        @if ($aspect->company)
                            <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                {{ $aspect->company->name }}
                            </span>
                        @else
                            <span class="rounded-md bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">
                                Global Aspect
                            </span>
                        @endif
                        <span
                            class="@if($aspect->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif rounded-md px-2 py-0.5 text-xs font-medium"
                        >
                            {{ $aspect->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    Created: {{ $aspect->created_at->format('M d, Y') }}
                    @if ($aspect->created_at != $aspect->updated_at)
                        <br />
                        Last updated: {{ $aspect->updated_at->format('M d, Y') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="update" class="space-y-8">
            {{-- Basic Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="aspect_code" class="mb-2 block text-sm font-medium text-gray-700">
                            Aspect Code
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="aspect_code"
                            wire:model.blur="aspect_code"
                            placeholder="e.g., LEAD-001, COMM-002"
                            class="@error('aspect_code') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('aspect_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Unique code for the aspect</p>
                    </div>

                    <div>
                        <label for="aspect_name" class="mb-2 block text-sm font-medium text-gray-700">
                            Aspect Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="aspect_name"
                            wire:model.blur="aspect_name"
                            placeholder="e.g., Leadership, Communication"
                            class="@error('aspect_name') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('aspect_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            id="description"
                            wire:model.blur="description"
                            rows="3"
                            placeholder="Describe what this aspect evaluates..."
                            class="@error('description') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        ></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Configuration --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Configuration</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Company Assignment --}}
                    <div>
                        <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Company Assignment
                        </label>
                        <div class="relative">
                            @if ($isSuperadmin)
                                <select
                                    id="company_id"
                                    wire:model.live="company_id"
                                    class="@error('company_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                >
                                    <option value="">Global Aspect (All Companies)</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" @selected($company_id == $company->id)>
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
                            @else
                                {{-- For non-superadmin, show company name (readonly) --}}
                                <div
                                    class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                                >
                                    @if ($aspect->company)
                                        <p>{{ $aspect->company->name }}</p>
                                    @else
                                        <p class="text-gray-500">Global Aspect</p>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    @if ($aspect->company)
                                        You can only edit aspects for your assigned company
                                    @else
                                            You can edit global aspects
                                    @endif
                                </p>
                            @endif
                        </div>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-medium text-gray-700">
                            Sort Order
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="number"
                            id="sort_order"
                            wire:model.blur="sort_order"
                            min="0"
                            class="@error('sort_order') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first in lists</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="is_active" class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input
                                    type="radio"
                                    id="is_active_true"
                                    wire:model="is_active"
                                    value="1"
                                    class="h-4 w-4 border-gray-300 text-[#721d21] focus:ring-[#721d21] focus:ring-offset-0"
                                />
                                <label for="is_active_true" class="text-sm text-gray-700">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        Active
                                    </span>
                                    <p class="text-xs text-gray-500">Aspect will be available for use</p>
                                </label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="radio"
                                    id="is_active_false"
                                    wire:model="is_active"
                                    value="0"
                                    class="h-4 w-4 border-gray-300 text-[#721d21] focus:ring-[#721d21] focus:ring-offset-0"
                                />
                                <label for="is_active_false" class="text-sm text-gray-700">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Inactive
                                    </span>
                                    <p class="text-xs text-gray-500">Aspect will be hidden from selection</p>
                                </label>
                            </div>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empty div untuk alignment --}}
                    <div></div>
                </div>
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
                            d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9"
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
                    <span wire:loading.remove wire:target="update">Update Aspect</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
