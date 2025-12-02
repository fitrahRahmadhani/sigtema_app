<flux:sidebar sticky stashable class="h-full w-[300px] border-e border-zinc-200 bg-zinc-50">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="flex items-center" wire:navigate>
        <img
            src="{{ asset('assets/logo/sigtema_without_text.png') }}"
            class="mx-auto h-8 lg:mt-4"
            alt="Logo SIGTEMA"
        />
    </a>

    <div class="mt-10 flex h-full w-full flex-col space-y-2 text-gray-500">
        <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">main menu</p>

        {{-- Dashboard Menu --}}
        <a
            href="{{ route('dashboard') }}"
            wire:navigate
            class="{{ request()->routeIs('dashboard') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"
                />
            </svg>
            <p>Dashboard</p>
        </a>

        {{-- Career Path Menu with Dropdown --}}
        <div
            x-data="{
                open: {{ request()->routeIs('career-path', 'vacant-positions.*', 'candidates-comparison.*', 'employees-profile.*') ? 'true' : 'false' }},
            }"
        >
            <div
                class="{{ request()->routeIs('career-path', 'vacant-positions.*', 'candidates-comparison.*', 'employees-profile.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <a href="{{ route('career-path') }}" class="flex flex-1 items-center gap-2" wire:navigate>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z"
                        />
                    </svg>
                    <p>Career Path</p>
                </a>
                <button
                    @click="open = !open"
                    class="rounded-md p-1 transition-transform duration-200 hover:bg-gray-300"
                    :class="{ 'rotate-180 bg-gray-300 ': open }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-4"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>

            {{-- Submenu Dropdown --}}
            <div
                x-show="open"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="-translate-y-2 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="-translate-y-2 opacity-0"
                class="ml-6 space-y-1 border-l border-gray-200 pl-2"
                style="display: none"
            >
                <a
                    href="{{ route('vacant-positions.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('vacant-positions.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                    </svg>

                    <p>Vacant Positions</p>
                </a>

                <a
                    href="{{ route('candidates-comparison.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('candidates-comparison.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>

                    <p>Candidate Comparison</p>
                </a>

                <a
                    href="{{ route('employees-profile.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('employees-profile.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"
                        />
                    </svg>
                    <p>Employee's Profile</p>
                </a>
            </div>
        </div>

        {{-- Organizational Structure --}}
        <a
            href="{{ route('organization-structure.index') }}"
            wire:navigate
            class="{{ request()->routeIs('organization-structure.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                class="bi bi-diagram-2"
                viewBox="0 0 16 16"
            >
                <path
                    fill-rule="evenodd"
                    d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 9 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"
                />
            </svg>
            <p>Org. Structure</p>
        </a>

        {{-- Master Data --}}
        <div class="mt-8 space-y-2">
            <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">master data</p>

            {{-- Companies --}}
            <a
                href="{{ route('companies.index') }}"
                wire:navigate
                class="{{ request()->routeIs('companies.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
                    />
                </svg>

                <p>Companies</p>
            </a>

            {{-- Position --}}
            <a
                href="{{ route('positions.index') }}"
                wire:navigate
                class="{{ request()->routeIs('positions.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                    />
                </svg>
                <p>Positions</p>
            </a>

            {{-- Employee --}}
            <a
                href="{{ route('employees.index') }}"
                wire:navigate
                class="{{ request()->routeIs('employees.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                    />
                </svg>
                <p>Employees</p>
            </a>

            {{-- Users --}}
            <a
                href="{{ route('users.index') }}"
                wire:navigate
                class="{{ request()->routeIs('users*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                    />
                </svg>
                <p>Users</p>
            </a>

            {{-- Role & Permission --}}
            <a
                href="{{ route('role-permissions.index') }}"
                wire:navigate
                class="{{ request()->routeIs('role-permissions.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                    />
                </svg>
                <p>Role & Permission</p>
            </a>
        </div>

        {{-- Action --}}
        <div class="mt-8 space-y-2">
            <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">actions</p>

            {{-- Bulk Upload with Dropdown --}}
            <div
                x-data="{
                    open: {{ request()->routeIs('career-path', 'vacant-positions.*', 'candidates-comparison.*', 'employees-profile.*') ? 'true' : 'false' }},
                }"
            >
                <button
                    @click="open = !open"
                    class="{{ request()->routeIs('career-path', 'vacant-positions.*', 'candidates-comparison.*', 'employees-profile.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
                    wire:navigate
                >
                    <div class="flex flex-1 items-center gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"
                            />
                        </svg>

                        <p>Bulk Upload</p>
                    </div>
                    <div
                        class="rounded-md p-1 transition-transform duration-200 hover:bg-gray-300"
                        :class="{ 'rotate-180 bg-gray-300 ': open }"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </button>

                {{-- Submenu Dropdown --}}
                <div
                    x-show="open"
                    x-transition:enter="transition duration-200 ease-out"
                    x-transition:enter-start="-translate-y-2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition duration-150 ease-in"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="-translate-y-2 opacity-0"
                    class="ml-6 space-y-1 border-l border-gray-200 pl-2"
                    style="display: none"
                >
                    <a
                        href="{{ route('bulk-upload.employees') }}"
                        wire:navigate
                        class="{{ request()->routeIs('bulk-upload.employees') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                            />
                        </svg>
                        <p>Employees</p>
                    </a>
                    <a
                        href="{{ route('bulk-upload.positions') }}"
                        wire:navigate
                        class="{{ request()->routeIs('bulk-upload.positions') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"
                            />
                        </svg>
                        <p>Positions</p>
                    </a>
                </div>
            </div>
        </div>

        {{-- Spacer untuk mendorong copyright ke bawah --}}
        <div class="flex-1"></div>

        {{-- Copyright --}}
        <p class="pb-4 text-center text-xs text-gray-400 italic">
            &copy; {{ now()->year }} Unit of Opr HC, GA & Asset Management
        </p>
    </div>
</flux:sidebar>
