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
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Add Psychogram Aspect</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new evaluation aspect for psychogram assessments</p>

                @if ($selectedCompany)
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- COMPANY SELECTION SECTION --}}
        @if ($showCompanySelection)
            <div class="mb-8 rounded-lg border border-gray-200 bg-white p-8 text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="mx-auto h-16 w-16 text-gray-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                    />
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-700">Pilih Perusahaan</h3>
                <p class="mt-2 text-gray-500">
                    Silakan pilih perusahaan terlebih dahulu untuk menambah data aspek psikogram
                </p>

                {{-- Company List --}}
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companiesWithCount as $item)
                        <a
                            href="{{ route('psychogram-aspects.index', ['company_id' => $item['company']->id]) }}"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($item['company']->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $item['company']->logo_path) }}"
                                            alt="{{ 'Logo ' . $item['company']->name }}"
                                        />
                                    @else
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
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $item['company']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} data aspek psikogram</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- MAIN CONTENT SECTION --}}
            @if ($selectedCompany)
                {{-- Form --}}
                <form wire:submit="save" class="space-y-8">
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
                                            class="@error('company_id') @enderror {{ $selectedCompanyId ? 'cursor-not-allowed bg-gray-100' : '' }} w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                            @if($selectedCompanyId) disabled @endif
                                        >
                                            @if ($selectedCompanyId)
                                                <option value="{{ $selectedCompanyId }}">
                                                    {{ $selectedCompany->name }}
                                                </option>
                                                <option value="">Global Aspect (All Companies)</option>
                                            @else
                                                <option value="">Global Aspect (All Companies)</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">
                                                        {{ $company->name }} ({{ $company->cocd }})
                                                    </option>
                                                @endforeach
                                            @endif
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
                                            <p>{{ $selectedCompany->name }}</p>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            You can only create aspects for your assigned company
                                        </p>
                                    @endif
                                </div>
                                @error('company_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($selectedCompanyId && $isSuperadmin)
                                    <p class="mt-1 text-xs text-gray-500">
                                        Company is selected from your previous session
                                    </p>
                                @endif
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
                                    value="0"
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
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"
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
                            <span wire:loading.remove wire:target="save">Create Aspect</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </button>
                    </div>
                </form>
            @endif
        @endif
    </div>
</div>
