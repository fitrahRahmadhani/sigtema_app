<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Role & Permission Management</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Role & Permission Management</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage roles, permissions, and access control</p>
                </div>
            </div>
        </div>

        {{-- Main Content - Split Layout --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- ==================== LEFT SIDE: ROLES ==================== --}}
            <div class="space-y-4">
                {{-- Roles Header --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Roles</h2>
                    <a href="{{ route('role-permissions.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#721d21] px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-[#9d2d32]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Role
                    </a>
                </div>

                {{-- Roles Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..." class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                </div>

                {{-- Roles Stats --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <p class="text-2xl font-bold text-gray-900">{{ $roles->total() }}</p>
                        <p class="text-xs text-gray-500">Total Roles</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <p class="text-2xl font-bold text-gray-900">{{ $roles->sum('users_count') }}</p>
                        <p class="text-xs text-gray-500">Total Users</p>
                    </div>
                </div>

                {{-- Roles Table --}}
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <div class="color-gradient grid grid-cols-12 gap-2 bg-gradient-to-r px-4 py-4 text-white">
                        <div class="col-span-4 text-sm font-semibold">Role</div>
                        <div class="col-span-3 text-sm font-semibold">Guard</div>
                        <div class="col-span-3 text-sm font-semibold">Users</div>
                        <div class="col-span-2 text-center text-sm font-semibold">Actions</div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($roles as $role)
                            <div class="grid grid-cols-12 gap-2 px-4 py-3 transition-colors hover:bg-gray-50">
                                <div class="col-span-4 flex items-center">
                                    <div class="flex items-center gap-2">
                                        <p class="truncate text-sm font-medium text-gray-900" title="{{ $role->name }}">{{ $role->name }}</p>
                                    </div>
                                </div>

                                <div class="col-span-3 flex items-center">
                                    <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">{{ $role->guard_name }}</span>
                                </div>

                                <div class="col-span-3 flex items-center">
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">{{ $role->users_count }} users</span>
                                </div>

                                <div class="col-span-2 flex items-center justify-center gap-1">
                                    <a href="{{ route('role-permissions.edit', $role->id) }}" class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-yellow-50 hover:text-yellow-600" title="Edit Role">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                    /></svg>
                                    </a>

                                    @if ($role->name !== 'superadmin')
                                        <button wire:click="confirmDelete({{ $role->id }}, '{{ $role->name }}')" class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-red-50 hover:text-red-600" title="Delete Role">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    /></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <p class="text-sm text-gray-500">No roles found</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($roles->hasPages())
                        <div class="border-t border-gray-200 px-4 py-3">{{ $roles->links('pagination::tailwind') }}</div>
                    @endif
                </div>
            </div>

            {{-- ==================== RIGHT SIDE: PERMISSIONS ==================== --}}
            <div class="space-y-4">
                {{-- Permissions Header --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Permissions</h2>
                    <a href="{{ route('permissions.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#721d21] px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-[#9d2d32]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Permission
                    </a>
                </div>

                {{-- Permissions Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="permissionSearch" placeholder="Search permissions..." class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"/>
                </div>

                {{-- Permissions Stats by Guard --}}
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($permissionGuards as $guard)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ $groupedPermissions[$guard]?->count() ?? 0 }}</p>
                            <p class="text-xs text-gray-500 uppercase">{{ $guard }} guard</p>
                        </div>
                    @endforeach
                </div>

                {{-- Permissions Table --}}
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <div class="color-gradient grid grid-cols-12 gap-2 bg-gradient-to-r px-4 py-4 text-white">
                        <div class="col-span-8 text-sm font-semibold">Permission</div>
                        <div class="col-span-2 text-sm font-semibold">Guard</div>
                        <div class="col-span-2 text-center text-sm font-semibold">Actions</div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($permissions as $permission)
                            <div class="grid grid-cols-12 gap-2 px-4 py-3 transition-colors hover:bg-gray-50">
                                <div class="col-span-8 flex items-center">
                                    <p class="truncate text-sm font-medium text-gray-900" title="{{ $permission->name }}">{{ $permission->name }}</p>
                                </div>

                                <div class="col-span-2 flex items-center">
                                    <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">{{ $permission->guard_name }}</span>
                                </div>

                                <div class="col-span-2 flex items-center justify-center gap-1">
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-yellow-50 hover:text-yellow-600" title="Edit Permission">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                    /></svg>
                                    </a>

                                    <button wire:click="deletePermission({{ $permission->id }})" wire:confirm="Delete permission '{{ $permission->name }}'?" class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-red-50 hover:text-red-600" title="Delete Permission">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    /></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <p class="text-sm text-gray-500">No permissions found</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($permissions->hasPages())
                        <div class="border-t border-gray-200 px-4 py-3">{{ $permissions->links('pagination::tailwind') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Global Delete Confirmation Modal --}}
    <x-ui.modals.delete-confirmation />
</div>
