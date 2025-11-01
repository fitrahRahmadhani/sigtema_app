<flux:header sticky class="bg-secondary-gray h-24 drop-shadow-lg">
    {{-- Sidebar Toggle --}}
    {{-- Toggle untuk membuka dan menutup sidebar pada layar kecil --}}
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    {{-- Spacer berfungsi untuk memberikan jarak antar elemen --}}
    <flux:spacer />

    {{-- Profile --}}
    <div
        @click="open = !open"
        class="text-primary-text flex items-center gap-2"
        x-data="{ open: false }"
        @click.away="open = false"
    >
        {{-- Nama dan email user --}}
        <div class="flex flex-col items-end">
            <p class="font-bold">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
        </div>

        {{-- Foto profile --}}
        {{-- Default-nya menggunakan icon svg dari heroicons, jika user memiliki foto, maka akan diganti dengan foto profile akun terkait --}}
        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10 self-end">
                <path
                    fill-rule="evenodd"
                    d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                    clip-rule="evenodd"
                />
            </svg>
        </div>

        {{-- Dropdown menu button --}}
        <button class="transition-transform duration-200" :class="{ 'rotate-180': open }">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                <path
                    fill-rule="evenodd"
                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>

        {{-- Dropdown menu --}}
        <div
            x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-95 opacity-0"
            class="absolute top-24 right-6 h-fit w-[200px] rounded-lg border border-zinc-200 bg-white p-2 text-gray-500 shadow-lg"
            style="display: none"
        >
            {{-- Button untuk profile --}}
            <button class="hover:text-primary-text flex w-full items-center gap-2 rounded-md p-2 hover:bg-gray-100">
                <a href="" class="flex w-full items-center gap-2">
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

                    <p>Profil Saya</p>
                </a>
            </button>

            {{-- Button untuk ganti password --}}
            <button class="hover:text-primary-text flex w-full items-center gap-2 rounded-md p-2 hover:bg-gray-100">
                <a href="" class="flex w-full items-center gap-2">
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
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                        />
                    </svg>
                    <p>Ganti Password</p>
                </a>
            </button>

            {{-- Form Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button
                    type="submit"
                    class="hover:text-primary-text flex w-full items-center gap-2 rounded-md p-2 hover:bg-gray-100"
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
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                        />
                    </svg>
                    <p>Keluar</p>
                </button>
            </form>
        </div>
    </div>
</flux:header>
