<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item
            href="{{ route('technical-competencies.index', ['company_id' => $selectedCompanyId]) }}"
        >
            Technical Competencies
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Technical Competency</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4">
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
                            d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Technical Competency</h1>
                    <p class="mt-1 text-sm text-gray-500">Update technical competency or certification</p>

                    @if ($selectedCompany)
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($isSuperadmin && ! $selectedCompanyId)
            <!-- COMPANY SELECTION SECTION (jika superadmin tanpa company) -->
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
                    Silakan kembali ke daftar Technical Competencies dan pilih perusahaan untuk mengedit data
                </p>

                <div class="mt-6">
                    <a
                        href="{{ route('technical-competencies.index') }}"
                        class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                                d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"
                            />
                        </svg>
                        Kembali ke Daftar Competencies
                    </a>
                </div>
            </div>
        @else
            <!-- MAIN CONTENT SECTION -->
            @if ($selectedCompany)
                <!-- Employee Info (Read-only) -->
                <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Employee Information</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Company (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                            <div
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-700"
                            >
                                @if ($competency->employee && $competency->employee->company)
                                    {{ $competency->employee->company->name }}
                                    ({{ $competency->employee->company->cocd }})
                                @else
                                    <span class="text-gray-400">No company assigned</span>
                                @endif
                            </div>
                        </div>

                        <!-- Employee (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Employee</label>
                            <div
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-700"
                            >
                                @if ($competency->employee)
                                    {{ $competency->employee->name }} ({{ $competency->employee->employee_number }})
                                @else
                                    <span class="text-gray-400">No employee assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form wire:submit="update" class="space-y-6">
                    <!-- Competency Details -->
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <h2 class="text-lg font-semibold text-gray-900">Competency Details</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <!-- Competency Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Competency/Certification Name
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    wire:model="name"
                                    class="@error('name') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    placeholder="Enter competency or certification name"
                                />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Issued By -->
                            <div class="md:col-span-2">
                                <label for="issued_by" class="mb-2 block text-sm font-medium text-gray-700">
                                    Issued By
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="issued_by"
                                    wire:model="issued_by"
                                    class="@error('issued_by') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    placeholder="Enter issuing organization"
                                />
                                @error('issued_by')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Year -->
                            <div>
                                <label for="year" class="mb-2 block text-sm font-medium text-gray-700">
                                    Year
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="year"
                                    wire:model="year"
                                    min="2000"
                                    max="{{ date('Y') + 1 }}"
                                    class="@error('year') @enderror w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                @error('year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="update"
                            class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:opacity-70"
                        >
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
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                />
                            </svg>
                            <span wire:loading.remove wire:target="update">Update Competency</span>
                            <span wire:loading wire:target="update">Updating...</span>
                        </button>
                    </div>
                </form>
            @endif
        @endif
    </div>
</div>
