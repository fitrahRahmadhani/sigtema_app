<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('role-permissions.index') }}">Role & Permission Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Permission</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.153 2.553c1.296.408 2.067 1.526 1.296 2.553l-2.153 2.553m-6.618-6.618 3.552-3.552c1.296-.408 2.067-1.526 1.296-2.553l-3.552-3.552m0 6.618 3.552-3.552c1.296-.408 2.067-1.526 1.296-2.553l-3.552-3.552m6.618 6.618-3.552-3.552c-1.296.408-2.067 1.526-1.296 2.553l3.552 3.552m0-6.618-3.552 3.552c-1.296.408-2.067 1.526-1.296 2.553l3.552 3.552"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Edit Permission</h1>
                    <p class="mt-1 text-sm text-gray-500">Update permission information</p>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="w-full">
            <form wire:submit="savePermission">
                <div class="space-y-8">
                    {{-- Permission Information Card --}}
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Permission Information</h3>
                            <p class="mt-1 text-sm text-gray-500">Update basic information about the permission</p>
                        </div>

                        <div class="space-y-6 p-6">
                            {{-- Current Permission Info --}}
                            <div class="rounded-lg bg-gray-50 p-4">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.153 2.553c1.296.408 2.067 1.526 1.296 2.553l-2.153 2.553m-6.618-6.618 3.552-3.552c1.296-.408 2.067-1.526 1.296-2.553l-3.552-3.552m0 6.618 3.552-3.552c1.296-.408 2.067-1.526 1.296-2.553l-3.552-3.552m6.618 6.618-3.552-3.552c-1.296.408-2.067 1.526-1.296 2.553l3.552 3.552m0-6.618-3.552 3.552c-1.296.408-2.067 1.526-1.296 2.553l3.552 3.552"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            Editing Permission:
                                            <span class="font-semibold">{{ $permission->name }}</span>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-700">
                                            Guard: <span class="font-mono">{{ $permission->guard_name }}</span>
                                            | Created: <span class="font-mono">{{ $permission->created_at->format('M d, Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Permission Name Input --}}
                            <div>
                                <label for="permissionName" class="block text-sm font-medium text-gray-700">
                                    Permission Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input type="text" id="permissionName" wire:model="permissionName"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                                        placeholder="Enter new permission name"/>
                                </div>
                                @error('permissionName')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <p class="mt-2 text-sm text-gray-500">
                                    Use snake_case format (e.g., "view_all_profiles", "manage_users")
                                </p>
                            </div>

                            {{-- Info Box --}}
                            <div class="rounded-lg bg-gray-50 p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Note:</p>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Permissions can be assigned to roles or directly to users. Direct user permissions override role permissions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('role-permissions.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="color-gradient inline-flex items-center gap-2 rounded-lg bg-gradient-to-r px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50">
                            <svg wire:loading.remove class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
                            </svg>
                            <svg wire:loading class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            <span wire:loading.remove>Update Permission</span>
                            <span wire:loading>Updating...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>