<section class="w-full">
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
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Profile Settings</h1>
                    <p class="mt-1 text-sm text-gray-500">Update your profile information and password</p>
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
                            wire:model.live.debounce.500ms="name"
                            placeholder="Enter your full name"
                            class="@error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (Readonly) --}}
                    <div class="md:col-span-2">
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5">
                            <p class="text-sm text-gray-700">{{ auth()->user()->email }}</p>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Email cannot be changed. Please contact administrator if you need to update your email.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Change Password Section --}}
            <div class="rounded-lg border border-red-200 bg-red-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-red-900">Change Password</h3>
                    <span class="rounded-full bg-red-200 px-3 py-1 text-xs font-medium text-red-800">Danger Zone</span>
                </div>
                <p class="mb-4 text-sm text-gray-600">Leave blank to keep your current password</p>
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Current Password --}}
                    <div class="md:col-span-2">
                        <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">
                            Current Password
                            @if ($password || $password_confirmation)
                                <span class="text-red-600">*</span>
                            @endif
                        </label>
                        <input
                            type="password"
                            id="current_password"
                            wire:model.live.debounce.500ms="current_password"
                            placeholder="Enter your current password"
                            class="@error('current_password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                        <input
                            type="password"
                            id="password"
                            wire:model.live.debounce.500ms="password"
                            placeholder="Min. 12 characters"
                            class="@error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-gray-600">Password must contain:</p>
                            <ul class="space-y-1 text-xs text-gray-500">
                                <li class="flex items-center gap-1">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                                    Minimum 12 characters
                                </li>
                                <li class="flex items-center gap-1">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                                    At least one uppercase letter
                                </li>
                                <li class="flex items-center gap-1">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                                    At least one lowercase letter
                                </li>
                                <li class="flex items-center gap-1">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                                    At least one number
                                </li>
                                <li class="flex items-center gap-1">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                                    At least one special character (@$!%*#?&)
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            wire:model.live.debounce.500ms="password_confirmation"
                            placeholder="Confirm your new password"
                            class="@error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-[#721d21] focus:ring-[#721d21] @enderror w-full rounded-lg border px-4 py-2.5 text-sm focus:ring-1 focus:outline-none"
                        />
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
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
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</section>
