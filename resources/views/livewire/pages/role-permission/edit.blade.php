<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('role-permissions.index') }}">Role Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Role</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-sm"
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
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Role</h1>
                    <p class="mt-1 text-sm text-gray-500">Update role information</p>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="w-full">
            <form wire:submit="saveRole">
                <div class="space-y-8">
                    {{-- Role Information Card --}}
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
                            <p class="mt-1 text-sm text-gray-500">Update basic information about the role</p>
                        </div>

                        <div class="space-y-6 p-6">
                            {{-- Current Role Name --}}
                            <div class="rounded-lg bg-gray-50 p-4">
                                <div class="flex items-center gap-3">
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
                                        />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            Editing Role:
                                            <span class="font-semibold">{{ $role->name }}</span>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-700">
                                            Guard:
                                            <span class="font-mono">{{ $role->guard_name }}</span>
                                            | Created:
                                            <span class="font-mono">{{ $role->created_at->format('M d, Y') }}</span>
                                            | Users:
                                            <span class="font-mono">{{ $roleCount ?? 0 }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Role Name Input --}}
                            <div>
                                <label for="roleName" class="block text-sm font-medium text-gray-700">
                                    Role Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        id="roleName"
                                        wire:model="roleName"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        placeholder="Enter new role name"
                                        autofocus
                                    />
                                </div>
                                @error('roleName')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <p class="mt-2 text-sm text-gray-500">
                                    Update the role name. Use descriptive names in kebab-case format.
                                </p>
                            </div>

                            {{-- Warning for Super Admin --}}
                            @if ($role->name === 'super-admin')
                                <div class="rounded-lg bg-yellow-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <svg
                                            class="mt-0.5 h-5 w-5 text-yellow-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                                            />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800">Super Admin Role</p>
                                            <p class="mt-1 text-sm text-yellow-700">
                                                This is a system role with full administrative privileges. Be cautious
                                                when modifying this role.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a
                            href="{{ route('role-permissions.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
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
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"
                                />
                            </svg>
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <svg
                                wire:loading.remove
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                                />
                            </svg>
                            <svg
                                wire:loading
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
                            <span wire:loading.remove>Update Role</span>
                            <span wire:loading>Updating Role...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
