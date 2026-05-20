<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('users.index') }}">Users</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit User</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
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
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit User</h1>
                    <p class="mt-1 text-sm text-gray-500">Update user information and permissions</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">
            {{-- Profile Photo Section --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Profile Photo</h3>
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                    {{-- Photo Preview --}}
                    <div
                        class="flex h-32 w-32 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50"
                    >
                        @if ($profile_photo_preview)
                            <img
                                src="{{ $profile_photo_preview }}"
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

                    {{-- Upload Controls --}}
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

                            @if ($profile_photo_preview)
                                <button
                                    type="button"
                                    wire:click="removeProfilePhoto"
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
                                    wire:confirm="Are you sure you want to delete this photo?"
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

                        <div wire:loading wire:target="profile_photo" class="text-sm text-red-600">Uploading...</div>
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
                            wire:model="name"
                            placeholder="Enter full name"
                            class="@error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Email Address
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            placeholder="user@example.com"
                            class="@error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Band --}}
                    <div>
                        <label for="band" class="mb-2 block text-sm font-medium text-gray-700">Band</label>
                        <div class="relative">
                            <select
                                id="band"
                                wire:model="band"
                                class="@error('band') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full appearance-none rounded-lg border bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:outline-none"
                            >
                                <option value="">Select Band (optional)</option>
                                @foreach ($bandOptions as $bandOption)
                                    <option value="{{ $bandOption }}">Band {{ $bandOption }}</option>
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

                        @error('band')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">Assign band for this position</p>
                    </div>
                </div>
            </div>

            {{-- Change Password Section --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">Optional</span>
                </div>
                <p class="mb-4 text-sm text-gray-600">Leave blank to keep the current password</p>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                        <input
                            type="password"
                            id="password"
                            wire:model="password"
                            placeholder="••••••••"
                            class="@error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
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
                            wire:model="password_confirmation"
                            placeholder="••••••••"
                            class="@error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none"
                        />
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Company Assignment --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Company Assignment</h3>
                <div>
                    <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                    <div class="relative">
                        <select
                            id="company_id"
                            wire:model="company_id"
                            class="@error('company_id') @enderror w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">Select a company (optional)</option>
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

                    <p class="mt-1 text-xs text-gray-500">Assign user to a specific company</p>
                </div>
            </div>

            {{-- Role Assignment --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Role Assignment</h3>
                <div>
                    <label class="mb-3 block text-sm font-medium text-gray-700">Select Roles</label>
                    <div class="space-y-3">
                        @forelse ($roles as $role)
                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 bg-white p-4 transition hover:border-[#721d21] hover:bg-gray-50"
                            >
                                <input
                                    type="checkbox"
                                    wire:model="selectedRoles"
                                    value="{{ $role->name }}"
                                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#721d21] focus:ring-[#721d21]"
                                />
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
                                        >
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </div>
                                    @if ($role->permissions->count() > 0)
                                        <p class="mt-1 text-xs text-gray-500">
                                            Permissions:
                                            {{ $role->permissions->pluck('name')->take(3)->implode(', ') }}
                                            @if ($role->permissions->count() > 3)
                                                    and {{ $role->permissions->count() - 3 }} more...
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 italic">No roles available. Please create roles first.</p>
                        @endforelse
                    </div>
                    @error('selectedRoles')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-3 text-xs text-gray-500">Select one or more roles for this user</p>
                </div>
            </div>

            {{-- Direct Permission Assignment --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Direct Permission Assignment</h3>
                        <p class="mt-1 text-sm text-gray-500">Assign permissions directly to this user (bypassing role)</p>
                    </div>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-800">Optional</span>
                </div>

                @if ($permissions->count() > 0)
                    <div class="space-y-4">
                        @foreach ($permissions as $guard => $guardPermissions)
                            <div class="rounded-lg border border-gray-200 bg-white">
                                <div class="border-b border-gray-100 bg-gray-50 px-4 py-2">
                                    <span class="inline-flex items-center rounded bg-gray-200 px-2 py-0.5 text-xs font-bold uppercase">{{ $guard }}</span>
                                    <span class="ml-2 text-xs text-gray-500">{{ $guardPermissions->count() }} permissions</span>
                                </div>
                                <div class="p-2">
                                    @foreach ($guardPermissions as $permission)
                                        <label class="flex cursor-pointer items-center gap-3 px-3 py-2 transition hover:bg-gray-50">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="h-4 w-4 rounded border-gray-300 text-[#721d21] focus:ring-[#721d21]" />
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs text-orange-600">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        </svg>
                        Direct permissions override role permissions. Use this to grant special access not available in user's roles.
                    </p>
                @else
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                        <p class="text-sm text-gray-500">No permissions available. Please create permissions first.</p>
                    </div>
                @endif

                @error('selectedPermissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    <span wire:loading.remove wire:target="save">Update User</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
