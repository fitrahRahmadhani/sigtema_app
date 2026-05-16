<flux:sidebar sticky stashable class="h-full w-[305px] border-e border-zinc-200 bg-zinc-50">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <div class="flex items-center" wire:navigate>
        <img
            src="{{ asset('assets/logo/sigtema_without_text.png') }}"
            class="mx-auto h-14 lg:mt-4"
            alt="Logo SIGTEMA"
        />
    </div>

    <div class="mt-10 flex h-full w-full flex-col space-y-2 text-gray-500">
        <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">main menu</p>

        @role('employee')
        {{-- Profile --}}
        <a
            href="{{ route('my-profile') }}"
            wire:navigate
            class="{{ request()->routeIs('my-profile') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
            <p>My Profile</p>
        </a>
        @endrole

        {{-- Dashboard Menu --}}
        @hasanyrole('superadmin|admin|hc-operator')
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
        @endhasanyrole

        @hasanyrole('superadmin|admin|head-of-unit|hc-operator')
            {{-- Career Pattern Menu with Dropdown --}}
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
                        <p>Career Pattern</p>
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
                        <img src="{{ asset('assets/icon/logo-ai.svg') }}" class="h-5" alt="Logo AI" />
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
                                d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
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
                        <p>Employee Profile</p>
                    </a>
                </div>
            </div>
        @endhasanyrole

        {{-- AI Coaching --}}
        <a
            href="{{ route('ai-coaching') }}"
            wire:navigate
            class="{{ request()->routeIs('ai-coaching') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                    d="M12 3L14.0357 8.16153C14.2236 8.63799 14.3175 8.87622 14.4614 9.0771C14.5889 9.25516 14.7448 9.41106 14.9229 9.53859C15.1238 9.68245 15.362 9.77641 15.8385 9.96432L21 12L15.8385 14.0357C15.362 14.2236 15.1238 14.3175 14.9229 14.4614C14.7448 14.5889 14.5889 14.7448 14.4614 14.9229C14.3175 15.1238 14.2236 15.362 14.0357 15.8385L12 21L9.96432 15.8385C9.77641 15.362 9.68245 15.1238 9.53859 14.9229C9.41106 14.7448 9.25516 14.5889 9.0771 14.4614C8.87622 14.3175 8.63799 14.2236 8.16153 14.0357L3 12L8.16153 9.96432C8.63799 9.77641 8.87622 9.68245 9.0771 9.53859C9.25516 9.41106 9.41106 9.25516 9.53859 9.0771C9.68245 8.87622 9.77641 8.63799 9.96432 8.16153L12 3Z"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            <p>AI Coaching</p>
        </a>

        @hasanyrole('superadmin|admin|hc-operator')
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
        @endhasanyrole

        @hasanyrole('superadmin|admin')
            {{-- Master Data --}}
            <div class="mt-8 space-y-2">
                <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">master data</p>

                @hasrole('superadmin')
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
                @endhasrole

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

                {{-- Educations --}}
                <a
                    href="{{ route('educations.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('educations.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
                        />
                    </svg>
                    <p>Educations</p>
                </a>

                {{-- MCUs --}}
                <a
                    href="{{ route('mcus.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('mcus.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                        />
                    </svg>

                    <p>MCUs</p>
                </a>

                {{-- Technical Competencies --}}
                <a
                    href="{{ route('technical-competencies.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('technical-competencies.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"
                        />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008Z" />
                    </svg>

                    <p>Technical Competencies</p>
                </a>

                {{-- Position Histories --}}
                <a
                    href="{{ route('position-histories.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('position-histories.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                        />
                    </svg>
                    <p>Position Histories</p>
                </a>

                {{-- Psychogram Aspects --}}
                <a
                    href="{{ route('psychogram-aspects.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('psychogram-aspects.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"
                        />
                    </svg>

                    <p>Psychogram Aspects</p>
                </a>

                {{-- Psychogram Result --}}
                <a
                    href="{{ route('psychogram-results.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('psychogram-results.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                        />
                    </svg>

                    <p>Psychogram Result</p>
                </a>
            </div>
        @endhasanyrole

        {{-- Data from API --}}
        @hasanyrole('superadmin|admin')
            <div class="mt-8 space-y-2">
                <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">data from api</p>
                {{-- Innovation --}}
                <a
                    href="{{ route('innovations.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('innovations.*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                        />
                    </svg>

                    <div class="flex w-full justify-between">
                        <p>Innovations</p>
                        <img
                            src="{{ asset('assets/logo/logo-portal-inovasi-red.png') }}"
                            alt="Logo Portal Inovasi"
                            class="h-5"
                        />
                    </div>
                </a>

                {{-- Burnout --}}
                <a
                    href="{{ route('burnouts.index') }}"
                    wire:navigate
                    class="{{ request()->routeIs('burnouts*') ? 'active-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200"
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
                            d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M4.5 10.5h6.75V15H4.5v-4.5ZM3.75 18h15A2.25 2.25 0 0 0 21 15.75v-6a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 1.5 9.75v6A2.25 2.25 0 0 0 3.75 18Z"
                        />
                    </svg>

                    <div class="flex w-full justify-between">
                        <p>Burnout</p>
                        <img
                            src="{{ asset('assets/logo/logo-sighap-black.png') }}"
                            alt="Logo Portal Inovasi"
                            class="h-4"
                        />
                    </div>
                </a>

                {{-- Personality with Dropdown --}}
                <div
                    x-data="{
                        openPersonalityDropdown:
                            {{ request()->routeIs('mbtis' || 'discs' || 'papis') ? 'true' : 'false' }},
                    }"
                >
                    <button
                        @click="openPersonalityDropdown = !openPersonalityDropdown"
                        class="flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200 hover:bg-gray-100"
                        :class="{ 'bg-gray-200 ': openPersonalityDropdown || {{ request()->routeIs('mbtis' || 'discs' || 'papis') ? 'true' : 'false' }} }"
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
                                    d="M15 9h.01M8.99 9H9m12 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM6.6 13a5.5 5.5 0 0 0 10.81 0H6.6Z"
                                />
                            </svg>

                            <div class="flex w-full items-center">
                                <p>Personality</p>
                                <img
                                    src="{{ asset('assets/logo/logo-sigmatic-black.png') }}"
                                    alt="Logo Portal Inovasi"
                                    class="ml-2 h-4"
                                />
                            </div>
                        </div>
                        <div
                            class="rounded-md p-1 transition-transform duration-200 hover:bg-gray-300"
                            :class="{ 'rotate-180 bg-gray-300 ': openPersonalityDropdown }"
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
                        x-show="openPersonalityDropdown"
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
                            href="{{ route('mbtis.index') }}"
                            wire:navigate
                            class="{{ request()->routeIs('mbtis.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                        >
                            <p>MBTI</p>
                        </a>

                        <a
                            href="{{ route('discs.index') }}"
                            wire:navigate
                            class="{{ request()->routeIs('discs.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                        >
                            <p>DISC</p>
                        </a>

                        <a
                            href="{{ route('papis.index') }}"
                            wire:navigate
                            class="{{ request()->routeIs('papis.*') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                        >
                            <p>PAPI</p>
                        </a>
                    </div>
                </div>
            </div>
        @endhasanyrole

        {{-- Settings --}}
        @hasanyrole('superadmin|admin')
            <div class="mt-8 space-y-2">
                <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">settings</p>

                {{-- Bulk Upload with Dropdown --}}
                <div
                    x-data="{
                        openSettingsDropdown:
                            {{ request()->routeIs('requirements.*') ? 'true' : 'false' }},
                    }"
                >
                    <button
                        @click="openSettingsDropdown = !openSettingsDropdown"
                        class="flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200 hover:bg-gray-100"
                        :class="{ 'bg-gray-200 ': openSettingsDropdown || {{ request()->routeIs('requirements.*') ? 'true' : 'false' }} }"
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
                                    d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12"
                                />
                            </svg>
                            <p>Requirement</p>
                        </div>
                        <div
                            class="rounded-md p-1 transition-transform duration-200 hover:bg-gray-300"
                            :class="{ 'rotate-180 bg-gray-300 ': openSettingsDropdown }"
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
                        x-show="openSettingsDropdown"
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
                            href="{{ route('requirements.position') }}"
                            wire:navigate
                            class="{{ request()->routeIs('requirements.position') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                                />
                            </svg>

                            <p>Positions</p>
                        </a>
                    </div>
                </div>
            </div>
        @endhasanyrole

        @role('superadmin')
        {{-- Authentication --}}
        <div class="mt-8 space-y-2">
            <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">authentication</p>

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
        @endrole

        {{-- Action --}}
        @hasanyrole('superadmin|admin')
            <div class="mt-8 space-y-2">
                <p class="ml-4 text-sm font-semibold text-gray-400 uppercase">actions</p>

                {{-- Bulk Upload with Dropdown --}}
                <div
                    x-data="{
                        openBulkUploadDropdown:
                            {{ request()->routeIs('bulk-upload.*') ? 'true' : 'false' }},
                    }"
                >
                    <button
                        @click="openBulkUploadDropdown = !openBulkUploadDropdown"
                        class="flex w-full items-center gap-2 rounded-md p-2 px-4 transition-colors duration-200 hover:bg-gray-100"
                        :class="{ 'bg-gray-200 ': openBulkUploadDropdown || {{ request()->routeIs('bulk-upload.*') ? 'true' : 'false' }} }"
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
                            :class="{ 'rotate-180 bg-gray-300 ': openBulkUploadDropdown }"
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
                        x-show="openBulkUploadDropdown"
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
                            href="{{ route('bulk-upload.users') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.users') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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

                        <a
                            href="{{ route('bulk-upload.employees') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.employees') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                            class="{{ request()->routeIs('bulk-upload.positions') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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

                        <a
                            href="{{ route('bulk-upload.educations') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.educations') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
                                />
                            </svg>
                            <p>Educations</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.mcus') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.mcus') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                                />
                            </svg>

                            <p>MCUs</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.technical-competencies') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.technical-competencies') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.867 19.125h.008v.008h-.008v-.008Z"
                                />
                            </svg>

                            <p>Technical Competencies</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.position-histories') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.position-histories') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                                />
                            </svg>

                            <p>Position Histories</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.psychogram-aspects') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.psychogram-aspects') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"
                                />
                            </svg>

                            <p>Psychogram Aspects</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.psychogram-results') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.psychogram-results') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                                />
                            </svg>

                            <p>Psychogram Results</p>
                        </a>

                        <a
                            href="{{ route('bulk-upload.position-requirements') }}"
                            wire:navigate
                            class="{{ request()->routeIs('bulk-upload.position-requirements') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
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
                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"
                                />
                            </svg>

                            <p>Position Requirements</p>
                        </a>
                    </div>
                </div>

                @hasrole('superadmin')
                    {{-- Report Tickerts --}}
                    <a
                        href="{{ route('report-tickets.index') }}"
                        class="{{ request()->routeIs('report-tickets.index') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="ml-1 size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"
                            />
                        </svg>

                        <p>Report Tickets</p>
                    </a>

                    {{-- Activity Logs --}}
                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="{{ request()->routeIs('activity-logs.index') ? 'active-sub-navbar' : 'navbar' }} flex w-full items-center gap-2 rounded-md p-2 px-3 transition-colors duration-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="ml-1 size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605"
                            />
                        </svg>

                        <p>Activity Logs</p>
                    </a>

                    {{-- Logs --}}
                    <a
                        href="{{ route('logs.index') }}"
                        class="navbar flex w-full items-center gap-2 rounded-md p-2 px-4"
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
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                            />
                        </svg>
                        <p>Logs</p>
                    </a>
                @endhasrole
            </div>
        @endhasanyrole

        <div class="flex-1"></div>

        {{-- Copyright --}}
        <p class="pb-4 text-center text-xs text-gray-400 italic">
            &copy; {{ now()->year }} Unit of Opr HC, GA & Asset Management
        </p>
    </div>
</flux:sidebar>
