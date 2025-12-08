<x-layouts.auth>
    <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
        @csrf
        <!-- Logo -->
        <img
            class="mx-auto w-64 md:w-80 lg:w-[600px]"
            src="{{ asset('assets/logo/sigtema-logo-primary.png') }}"
            alt="Logo SIGTEMA"
        />

        <!-- Judul -->
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Password</h1>
            <p class="mt-2 text-gray-600">Harap konfirmasi password Anda sebelum melanjutkan.</p>
        </div>

        <div class="bg-tertiary-gray mx-auto flex w-fit rounded-xl">
            <div class="flex flex-col gap-4 rounded-md p-4 lg:flex-row">
                <!-- Password Input -->
                <div class="flex flex-col gap-1">
                    <label
                        for="password"
                        class="relative flex h-fit w-80 items-center gap-2 rounded-xl bg-white p-2"
                        x-data="{ showPassword: false }"
                    >
                        <div class="bg-tertiary-gray rounded-full p-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-6 text-white"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <input
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="w-full p-2 pr-10 text-black focus:outline-none"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                        />
                        <button
                            x-cloak
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 text-gray-300 transition duration-300 hover:text-gray-500"
                        >
                            <!-- Eye Icon (Show) -->
                            <svg
                                x-show="!showPassword"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5"
                            >
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path
                                    fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <!-- Eye Slash Icon (Hide) -->
                            <svg
                                x-show="showPassword"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5"
                            >
                                <path
                                    d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z"
                                />
                                <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12Z" />
                                <path d="M12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                                <path
                                    d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z"
                                />
                            </svg>
                        </button>
                    </label>
                    @error('password')
                        <span class="px-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Button -->
                <button
                    type="submit"
                    class="color-gradient flex h-fit items-center justify-center rounded-xl px-10 py-2 text-lg font-bold tracking-wide text-white uppercase hover:cursor-pointer md:h-14"
                >
                    <svg
                        wire:loading
                        wire:target="confirmPassword"
                        class="mr-2 h-5 w-5 animate-spin text-white"
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
                    <span wire:loading.remove wire:target="confirmPassword">Konfirmasi</span>
                    <span wire:loading wire:target="confirmPassword">Loading...</span>
                </button>
            </div>
        </div>
    </form>
</x-layouts.auth>
