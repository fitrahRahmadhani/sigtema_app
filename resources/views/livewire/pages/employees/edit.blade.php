<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('employees.index') }}">Employees</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Employee</flux:breadcrumbs.item>
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
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                    />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Employee</h1>
                <p class="mt-1 text-sm text-gray-500">Update employee information</p>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Profile Photo --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Profile Photo</h3>
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                    <div
                        class="flex h-32 w-32 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50"
                    >
                        @if ($profile_photo)
                            <img
                                src="{{ $profile_photo->temporaryUrl() }}"
                                alt="Profile Preview"
                                class="h-full w-full object-contain"
                            />
                        @elseif ($existing_profile_photo)
                            <img
                                src="{{ \Storage::url($existing_profile_photo) }}"
                                alt="Current Profile"
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
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="cursor-pointer">
                                <input type="file" wire:model="profile_photo" accept="image/*" class="hidden" />
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
                                    {{ $existing_profile_photo ? 'Change Photo' : 'Choose File' }}
                                </span>
                            </label>
                            @if ($profile_photo)
                                <button
                                    type="button"
                                    wire:click="$set('profile_photo', null)"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            @elseif ($existing_profile_photo)
                                <button
                                    type="button"
                                    wire:click="deleteExistingPhoto"
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
                                    Delete Photo
                                </button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG max 2MB. Recommended size: 400x400px</p>
                        @error('profile_photo')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="profile_photo" class="text-sm text-blue-600">Uploading...</div>
                    </div>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Full Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model.live="name"
                            placeholder="Enter full name"
                            class="@error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Email
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            wire:model.blur="email"
                            placeholder="email@example.com"
                            class="@error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                            Phone Number
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="phone"
                            wire:model.blur="phone"
                            placeholder="+62 812 3456 7890"
                            class="@error('phone') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label for="birth_date" class="mb-2 block text-sm font-medium text-gray-700">Birth Date</label>
                        <input
                            type="date"
                            id="birth_date"
                            wire:model.blur="birth_date"
                            class="@error('birth_date') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="mb-2 block text-sm font-medium text-gray-700">
                            Gender
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="gender"
                                wire:model="gender"
                                class="@error('gender') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:ring-1 focus:outline-none"
                            >
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Employment Information --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Employment Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Employee Number --}}
                    <div>
                        <label for="employee_number" class="mb-2 block text-sm font-medium text-gray-700">
                            Employee Number
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="employee_number"
                            wire:model.blur="employee_number"
                            placeholder="e.g., 00007717, 00003324"
                            class="@error('employee_number') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('employee_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Personal Grade --}}
                    <div>
                        <label for="personal_grade" class="mb-2 block text-sm font-medium text-gray-700">
                            Personal Grade
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="personal_grade"
                            wire:model.blur="personal_grade"
                            placeholder="e.g., 12, 13"
                            class="@error('personal_grade') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('personal_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hire Date --}}
                    <div>
                        <label for="hire_date" class="mb-2 block text-sm font-medium text-gray-700">Hire Date</label>
                        <input
                            type="date"
                            id="hire_date"
                            wire:model.blur="hire_date"
                            class="@error('hire_date') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Band --}}
                    <div>
                        <label for="band" class="mb-2 block text-sm font-medium text-gray-700">
                            Band
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="band"
                                wire:model="band"
                                class="@error('band') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:ring-1 focus:outline-none"
                            >
                                <option value="">Select band</option>
                                <option value="1">Band 1</option>
                                <option value="2">Band 2</option>
                                <option value="3">Band 3</option>
                                <option value="4">Band 4</option>
                                <option value="5">Band 5</option>
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
                        @error('band')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Change Password (Optional) --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Optional</span>
                </div>
                <p class="mb-4 text-sm text-gray-600">Leave blank to keep the current password</p>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                        <input
                            type="password"
                            id="password"
                            wire:model.live="password"
                            placeholder="••••••••••••"
                            class="@error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">
                            At least 12 characters with uppercase, lowercase, number, and symbol.
                        </p>
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            wire:model.live="password_confirmation"
                            placeholder="••••••••••••"
                            class="@error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @hasrole('superadmin')
                {{-- Organization Assignment --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Organization Assignment</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">
                                Company
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select
                                    id="company_id"
                                    wire:model.live="company_id"
                                    class="@error('company_id') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:ring-1 focus:outline-none"
                                >
                                    <option value="">Select a company</option>
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
                            @error('company_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endhasrole

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
                    <span wire:loading.remove wire:target="save">Update Employee</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
