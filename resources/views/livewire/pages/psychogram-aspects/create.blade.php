<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('psychogram-aspects.index') }}">
            Psychogram Aspects
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Add Aspect</flux:breadcrumbs.item>
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
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Add Psychogram Aspect</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new evaluation aspect for psychogram assessments</p>

                @if ($selectedCompany)
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Basic Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Company (Read-only) --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Company
                            <span class="text-red-600">*</span>
                        </label>
                        <div
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                        >
                            <p>{{ $selectedCompany->name }}</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($isSuperadmin)
                                Company from your current session
                            @else
                                Your assigned company
                            @endif
                        </p>
                    </div>

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
                            class="@error('aspect_code') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('aspect_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">
                            Unique code for the aspect (e.g., initials followed by number)
                        </p>
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
                            placeholder="e.g., Leadership, Communication, Teamwork"
                            class="@error('aspect_name') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
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
                            class="@error('description') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
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
                            value="0"
                            class="@error('sort_order') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first in lists</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="is_active" class="mb-2 block text-sm font-medium text-gray-700">Status</label>
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
                                </label>
                            </div>
                        </div>
                    </div>
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
                    <span wire:loading.remove wire:target="save">Create Aspect</span>
                    <span wire:loading wire:target="save">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
