<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('positions.index') }}">Positions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Position</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Position</h1>
                    <p class="mt-1 text-sm text-gray-500">Update position details and organizational information</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Position Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Position Information</h3>
                <div class="space-y-6">
                    {{-- Position Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Position Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model.live="name"
                            placeholder="e.g., CSR Officer"
                            class="@error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        {{-- Position Code --}}
                        <div>
                            <label for="position_code" class="mb-2 block text-sm font-medium text-gray-700">
                                Position Code
                                <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                id="position_code"
                                wire:model.blur="position_code"
                                placeholder="e.g., 50066627"
                                class="@error('position_code') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('position_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Job Grade --}}
                        <div>
                            <label for="job_grade" class="mb-2 block text-sm font-medium text-gray-700">
                                Job Grade
                                <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="number"
                                id="job_grade"
                                wire:model.blur="job_grade"
                                placeholder="e.g., 11"
                                class="@error('job_grade') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('job_grade')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Job grade classification</p>
                        </div>

                        {{-- Band --}}
                        <div>
                            <label for="band" class="mb-2 block text-sm font-medium text-gray-700">Band</label>
                            <div wire:ignore class="relative">
                                <select
                                    id="band"
                                    wire:model="band"
                                    class="@error('band') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:outline-none"
                                >
                                    <option value="">Select Band</option>
                                    @foreach ($bandOptions as $bandOption)
                                        <option value="{{ $bandOption }}">Band {{ $bandOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('band')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Band classification</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Company Assignment --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Company Assignment</h3>
                <div class="mb-6 grid gap-6">
                    {{-- Company Selection --}}
                    @hasrole('superadmin')
                        <div class="md:col-span-2">
                            <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                                Company
                                <span class="text-red-600">*</span>
                            </label>
                            <div wire:ignore class="relative">
                                <select
                                    id="company_id"
                                    wire:model="company_id"
                                    class="@error('company_id') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:outline-none"
                                >
                                    <option value="">Select a company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company['value'] }}">
                                            {{ $company['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('company_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Company
                            </label>
                            <input
                                type="text"
                                value="{{ Auth::user()->company->name ?? 'N/A' }}"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 focus:outline-none"
                                readonly
                                disabled
                            />
                            <input type="hidden" wire:model="company_id" />
                        </div>
                    @endhasrole
                </div>

                {{-- Organizational Structure --}}
                <div class="mb-6">
                    <h4 class="text-md mb-4 font-medium text-gray-700">Organizational Structure</h4>
                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Directorate --}}
                        <div>
                            <label for="directorate" class="mb-2 block text-sm font-medium text-gray-700">
                                Directorate
                            </label>
                            <input
                                type="text"
                                id="directorate"
                                wire:model="directorate"
                                placeholder="e.g., Finance & Human Capital Directorate"
                                class="@error('directorate') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('directorate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- KOMP --}}
                        <div>
                            <label for="komp" class="mb-2 block text-sm font-medium text-gray-700">KOMP</label>
                            <input
                                type="text"
                                id="komp"
                                wire:model="komp"
                                placeholder="e.g., Sub Group of Production"
                                class="@error('komp') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('komp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Biro --}}
                        <div>
                            <label for="biro" class="mb-2 block text-sm font-medium text-gray-700">Biro</label>
                            <input
                                type="text"
                                id="biro"
                                wire:model="biro"
                                placeholder="e.g., Unit of Communication & CSR"
                                class="@error('biro') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('biro')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div>
                            <label for="department" class="mb-2 block text-sm font-medium text-gray-700">
                                Department
                            </label>
                            <input
                                type="text"
                                id="department"
                                wire:model="department"
                                placeholder="e.g., Departement of IT"
                                class="@error('department') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Group --}}
                        <div>
                            <label for="group" class="mb-2 block text-sm font-medium text-gray-700">Group</label>
                            <input
                                type="text"
                                id="group"
                                wire:model="group"
                                placeholder="e.g., Group of Water Treatment Operation"
                                class="@error('group') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Section --}}
                        <div>
                            <label for="section" class="mb-2 block text-sm font-medium text-gray-700">Section</label>
                            <input
                                type="text"
                                id="section"
                                wire:model="section"
                                placeholder="e.g., Section of Utility Operation"
                                class="@error('section') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Work Unit --}}
                        <div>
                            <label for="work_unit" class="mb-2 block text-sm font-medium text-gray-700">
                                Work Unit
                            </label>
                            <input
                                type="text"
                                id="work_unit"
                                wire:model="work_unit"
                                placeholder="e.g., Unit of Energy Procurement"
                                class="@error('work_unit') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('work_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div>
                            <label for="location" class="mb-2 block text-sm font-medium text-gray-700">Location</label>
                            <input
                                type="text"
                                id="location"
                                wire:model="location"
                                placeholder="e.g., Jakarta"
                                class="@error('location') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                            />
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employee Assignment --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Employee Assignment</h3>
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Optional</span>
                </div>
                <div class="mb-6">
                    {{-- Employee Selection --}}
                    <div class="w-full">
                        <label for="employee_id" class="mb-2 block text-sm font-medium text-gray-700">Employee</label>
                        <div wire:ignore class="relative">
                            <select
                                id="employee_id"
                                wire:model="employee_id"
                                class="@error('employee_id') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-700 focus:outline-none"
                                @if (!$company_id) disabled @endif
                            >
                                <option value="">
                                    {{ $company_id ? 'Select employee (optional)' : 'Select company first' }}
                                </option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee['value'] }}">{{ $employee['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">
                            You can assign an employee to this position now or later
                        </p>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
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
                    <svg wire:loading.remove wire:target="save" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                        />
                    </svg>
                    <svg wire:loading wire:target="save" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Update Position</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('livewire:init', function() {
            initializeSelect2();
        });

        if (window.Livewire) {
            initializeSelect2();
        }

        function initializeSelect2() {
            // Inisialisasi Select2 untuk Band
            const $bandSelect = $('#band');
            $bandSelect.select2({
                placeholder: 'Select Band',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $bandSelect.parent(),
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                @this.set('band', $(this).val());
            });

            // Set nilai awal untuk Band jika ada
            const initialBand = @js($band);
            if (initialBand) {
                $bandSelect.val(initialBand).trigger('change');
            }

            // Inisialisasi Select2 untuk Company
            const $companySelect = $('#company_id');
            $companySelect.select2({
                placeholder: 'Select a company',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $companySelect.parent()
            }).on('change', function(e) {
                @this.set('company_id', $(this).val());
            });

            // Set nilai awal untuk Company jika ada
            const initialCompanyId = @js($company_id);
            if (initialCompanyId) {
                $companySelect.val(initialCompanyId).trigger('change');
            }

            // Inisialisasi Select2 untuk Employee
            const $employeeSelect = $('#employee_id');
            const isDisabled = !@this.company_id;

            $employeeSelect.select2({
                placeholder: isDisabled ? 'Select company first' : 'Select employee (optional)',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $employeeSelect.parent(),
                disabled: isDisabled
            }).on('change', function(e) {
                @this.set('employee_id', $(this).val());
            });

            // Set nilai awal untuk Employee jika ada
            const initialEmployeeId = @js($employee_id);
            if (initialEmployeeId) {
                $employeeSelect.val(initialEmployeeId).trigger('change');
            }

            // Update Employee dropdown saat company berubah
            Livewire.on('companyUpdated', function() {
                const placeholder = @this.company_id
                    ? 'Select employee (optional)'
                    : 'Select company first';

                const isDisabled = !@this.company_id;

                $employeeSelect.select2('destroy');
                $employeeSelect.select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap-5',
                    dropdownParent: $employeeSelect.parent(),
                    disabled: isDisabled
                }).on('change', function(e) {
                    @this.set('employee_id', $(this).val());
                });

                $employeeSelect.prop('disabled', isDisabled);

                // Reset selected employee jika company berubah
                if (!@this.company_id) {
                    $employeeSelect.val(null).trigger('change');
                }
            });
        }
    });
    </script>

    <style>
    .select2-container--bootstrap-5 {
        display: block;
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        min-height: 44px !important;
        background-color: white !important;
        position: relative;
    }

    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: #721d21 !important;
        box-shadow: 0 0 0 1px #721d21 !important;
        outline: none !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #721d21 !important;
        box-shadow: 0 0 0 1px #721d21 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        color: #374151 !important;
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
        padding-left: 12px !important;
        padding-right: 30px !important;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        display: block !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        right: 8px !important;
        width: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
        border-color: #6b7280 transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        border-style: solid !important;
        margin-left: 0 !important;
        margin-top: 0 !important;
        position: relative !important;
        left: 0 !important;
        top: 0 !important;
    }

    .select2-container--bootstrap-5.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #6b7280 transparent !important;
        border-width: 0 4px 5px 4px !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        margin-top: 0.25rem !important;
        z-index: 1060 !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 0.875rem !important;
        margin: 8px !important;
        width: calc(100% - 16px) !important;
    }

    .select2-container--bootstrap-5 .select2-search__field:focus {
        border-color: #721d21 !important;
        box-shadow: 0 0 0 1px #721d21 !important;
        outline: none !important;
    }

    .select2-container--bootstrap-5 .select2-results__option {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #f9fafb !important;
        color: #111827 !important;
    }

    .select2-container--bootstrap-5.has-error .select2-selection {
        border-color: #f87171 !important;
        box-shadow: 0 0 0 1px #f87171 !important;
    }

    .select2-container--bootstrap-5 .select2-selection__clear {
        position: absolute !important;
        right: 30px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 1.25rem !important;
        line-height: 1 !important;
        color: #6b7280 !important;
        width: 16px !important;
        height: 16px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .select2-container--bootstrap-5 .select2-selection__clear:hover {
        color: #374151 !important;
    }

    .select2-container--bootstrap-5 .select2-selection[aria-disabled="true"] {
        background-color: #f9fafb !important;
        cursor: not-allowed !important;
    }

    div[wire\:ignore].relative {
        position: relative;
    }

    select {
        padding-right: 2.5rem !important;
    }

    select[wire\:model] {
        width: 100% !important;
        appearance: none !important;
        background-color: transparent !important;
    }
    </style>
</div>
