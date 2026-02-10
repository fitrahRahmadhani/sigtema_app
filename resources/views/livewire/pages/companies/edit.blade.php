<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('companies.index') }}">Companies</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Company</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-4 md:p-10">
        {{-- Header --}}
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
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Company</h1>
                    <p class="mt-1 text-sm text-gray-500">Update company information</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-6">
            {{-- Logo Upload --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Company Logo
                    <span class="text-red-900">*</span>
                </label>

                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                    {{-- Preview --}}
                    <div
                        class="flex h-32 w-32 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50"
                    >
                        @if ($logo)
                            <img
                                src="{{ $logo->temporaryUrl() }}"
                                alt="Logo Preview"
                                class="h-full w-full object-contain"
                            />
                        @elseif ($current_logo)
                            <img
                                src="{{ \Storage::url($current_logo) }}"
                                alt="Current Logo"
                                class="h-full w-full object-contain"
                            />
                        @else
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-12 w-12 text-gray-400"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                                />
                            </svg>
                        @endif
                    </div>

                    {{-- Upload Button & Info --}}
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="cursor-pointer">
                                <input type="file" wire:model="logo" accept="image/*" class="hidden" />
                                <span
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                                >
                                    <svg
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
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"
                                        />
                                    </svg>
                                    Change Logo
                                </span>
                            </label>

                            @if ($logo)
                                <button
                                    type="button"
                                    wire:click="$set('logo', null)"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50"
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
                            @elseif ($current_logo)
                                <button
                                    type="button"
                                    wire:click="removeLogo"
                                    wire:confirm="Are you sure you want to remove this logo?"
                                    class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                                >
                                    <svg
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
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        />
                                    </svg>
                                    Remove Logo
                                </button>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500">PNG, JPG max 2MB. Recommended size: 200x200px</p>

                        @error('logo')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="logo" class="text-sm text-red-600">Uploading...</div>
                    </div>
                </div>
            </div>

            {{-- Company Name --}}
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Company Name
                    <span class="text-red-900">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    placeholder="Enter company name"
                    class="block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 transition focus:border-red-900 focus:ring-2 focus:ring-red-900/20 focus:outline-none"
                />
                @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Company Code --}}
            <div class="space-y-2">
                <label for="cocd" class="block text-sm font-medium text-gray-700">
                    Company Code
                    <span class="text-red-900">*</span>
                </label>
                <input
                    type="text"
                    id="cocd"
                    wire:model="cocd"
                    placeholder="Enter company code"
                    class="block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 transition focus:border-red-900 focus:ring-2 focus:ring-red-900/20 focus:outline-none"
                />
                @error('cocd')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Is Active Toggle --}}
            <div class="space-y-2">
                <label class="flex cursor-pointer items-center gap-3">
                    <input
                        type="checkbox"
                        wire:model="is_active"
                        class="h-5 w-5 rounded border-gray-300 text-red-900 transition focus:ring-2 focus:ring-red-900/20"
                    />
                    <span class="text-sm font-medium text-gray-700">Active Company</span>
                </label>
                <p class="text-xs text-gray-500">Set whether this company is currently active</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('companies.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="color-gradient inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg px-6 py-2.5 text-sm font-medium text-white shadow-sm transition disabled:cursor-not-allowed disabled:opacity-50"
                    wire:loading.attr="disabled"
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
                    <span wire:loading.remove wire:target="save">Update Company</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
