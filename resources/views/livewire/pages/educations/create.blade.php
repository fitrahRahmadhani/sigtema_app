<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('educations.index') }}">Educations</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Add Education</flux:breadcrumbs.item>
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
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Add Education Record</h1>
                <p class="mt-1 text-sm text-gray-500">Add new education history for employee</p>

                @if ($selectedCompany)
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $selectedCompany->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($isSuperadmin && ! $selectedCompanyId)
            {{-- COMPANY SELECTION SECTION --}}
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
                    Silakan pilih perusahaan terlebih dahulu untuk menambah data pendidikan
                </p>

                {{-- Company List --}}
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <a
                            href="{{ route('educations.index', ['company_id' => $company->id]) }}"
                            class="rounded-lg border border-gray-200 bg-white p-4 text-left transition-all hover:border-[#721d21] hover:shadow-md"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-lg bg-gray-100">
                                    @if ($company->logo_path)
                                        <img
                                            class="h-14 w-14 object-cover"
                                            src="{{ asset('storage/' . $company->logo_path) }}"
                                            alt="{{ 'Logo ' . $company->name }}"
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
                                    <h4 class="font-semibold text-gray-900">{{ $company->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            \App\Models\Education::whereHas('employee', function ($query) use ($company) {
                                                $query->where('company_id', $company->id);
                                            })->count()
                                        }}
                                        data pendidikan
                                    </p>
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
                    {{-- Company & Employee Selection --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Employee Selection</h3>
                        <div class="space-y-6">
                            {{-- Company Selection (only for superadmin without session) --}}
                            @if ($isSuperadmin)
                                <div>
                                    <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                                        Company
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <div class="relative">
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
                                            @else
                                                <option value="">Select a company</option>
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
                                    </div>
                                    @error('company_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    @if ($selectedCompanyId)
                                        <p class="mt-1 text-sm text-gray-500">
                                            Company is selected from your previous session
                                        </p>
                                    @endif
                                </div>
                            @else
                                {{-- For non-superadmin, show company name (readonly) --}}
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Company
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <div
                                        class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700"
                                    >
                                        <p>{{ $selectedCompany->name }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Your assigned company</p>
                                </div>
                            @endif

                            {{-- Employee Selection --}}
                            <div>
                                <label for="employee_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Employee
                                    <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        id="employee_id"
                                        wire:model="employee_id"
                                        class="@error('employee_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        @if(!$company_id) disabled @endif
                                    >
                                        <option value="">Select an employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->name }} ({{ $employee->employee_number }})
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
                                @error('employee_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if (! $company_id)
                                    <p class="mt-1 text-sm text-gray-500">Please select a company first</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Education Details --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Education Details</h3>
                        <div class="grid gap-6 md:grid-cols-3">
                            {{-- Institution Name --}}
                            <div>
                                <label for="institution_name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Institution Name
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="institution_name"
                                    wire:model="institution_name"
                                    placeholder="e.g., Universitas Indonesia"
                                    class="@error('institution_name') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                @error('institution_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Major --}}
                            <div>
                                <label for="major" class="mb-2 block text-sm font-medium text-gray-700">
                                    Major / Field of Study
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="major"
                                    wire:model="major"
                                    placeholder="e.g., Computer Science"
                                    class="@error('major') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                @error('major')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Degree --}}
                            <div>
                                <label for="degree" class="mb-2 block text-sm font-medium text-gray-700">
                                    Degree Level
                                    <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        id="degree"
                                        wire:model="degree"
                                        class="@error('degree') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                    >
                                        <option value="">Select degree level</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D1">Diploma 1 (D1)</option>
                                        <option value="D2">Diploma 2 (D2)</option>
                                        <option value="D3">Diploma 3 (D3)</option>
                                        <option value="D4">Diploma 4 (D4)</option>
                                        <option value="S1">Bachelor (S1)</option>
                                        <option value="S2">Master (S2)</option>
                                        <option value="S3">Doctorate (S3)</option>
                                        <option value="Profesi">Profesi</option>
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
                                @error('degree')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    Start Date
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="start_date"
                                    wire:model="start_date"
                                    class="@error('start_date') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    End Date
                                </label>
                                <input
                                    type="date"
                                    id="end_date"
                                    wire:model="end_date"
                                    class="@error('end_date') @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                />
                                @error('end_date')
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
                            <span wire:loading.remove wire:target="save">Add Education</span>
                            <span wire:loading wire:target="save">Adding...</span>
                        </button>
                    </div>
                </form>
            @endif
        @endif
    </div>
</div>
