<div class="space-y-4 p-2">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('role-permissions.index') }}">Role Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create Role</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Content -->
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        <!-- Header -->
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Create New Role</h1>
                    <p class="mt-1 text-sm text-gray-500">Add a new role to the system</p>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full">
            <form wire:submit="saveRole">
                <div class="space-y-8">
                    <!-- Card -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
                            <p class="mt-1 text-sm text-gray-500">Basic information about the role</p>
                        </div>

                        <div class="space-y-6 p-6">
                            <!-- Role Name -->
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
                                        placeholder="Enter role name (e.g., manager, editor, analyst)"
                                        autofocus
                                    />
                                </div>
                                @error('roleName')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <p class="mt-2 text-sm text-gray-500">
                                    Use descriptive names in kebab-case format (e.g., content-manager, data-analyst)
                                </p>
                            </div>

                            <!-- Guard Name Info -->
                            <div class="rounded-lg bg-gray-50 p-4">
                                <div class="flex items-start gap-3">
                                    <svg
                                        class="mt-0.5 h-5 w-5 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
                                        />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Default Guard</p>
                                        <p class="mt-1 text-sm text-gray-600">
                                            This role will be assigned to the "web" guard by default. Guards define how
                                            users are authenticated for the role.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
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
                            <span wire:loading.remove>Create Role</span>
                            <span wire:loading>Creating Role...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
