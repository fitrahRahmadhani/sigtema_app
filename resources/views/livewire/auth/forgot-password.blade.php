<form wire:submit.prevent="sendPasswordResetLink" class="flex flex-col gap-6">
    {{-- Logo --}}
    <img
        class="mx-auto w-64 md:w-80 lg:w-[600px]"
        src="{{ asset('assets/logo/sigtema-logo-primary.png') }}"
        alt="Logo SIGTEMA"
    />

    <div class="bg-tertiary-gray mx-auto flex w-fit rounded-xl">
        <div class="flex flex-col gap-4 rounded-md p-4 lg:flex-row">
            {{-- Email Input --}}
            <div class="flex flex-col gap-1">
                <label for="email" class="flex h-fit w-80 items-center gap-2 rounded-xl bg-white p-2">
                    <div class="bg-tertiary-gray rounded-full p-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-6 text-white"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <input
                        type="email"
                        id="email"
                        wire:model.blur="email"
                        autocomplete="off"
                        class="w-full p-2 text-black focus:outline-none"
                        placeholder="Masukkan email"
                    />
                </label>
                @error('email')
                    <span class="px-2 text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="color-gradient flex h-fit items-center justify-center rounded-xl px-10 py-2 text-lg font-bold tracking-wide text-white uppercase hover:cursor-pointer md:h-14"
            >
                <svg
                    wire:loading
                    wire:target="sendPasswordResetLink"
                    class="mr-2 h-5 w-5 animate-spin text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span wire:loading.remove wire:target="sendPasswordResetLink">Kirim Link Reset</span>
                <span wire:loading wire:target="sendPasswordResetLink">Loading...</span>
            </button>
        </div>
    </div>

    {{-- reCAPTCHA Desktop --}}
    <div class="hidden flex-col items-center gap-1 lg:flex">
        <div wire:ignore id="recaptcha-desktop">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display(['data-callback' => 'onRecaptchaSuccess']) !!}
        </div>
        @error('gRecaptchaResponse')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    {{-- reCAPTCHA Mobile --}}
    <div class="flex flex-col items-center gap-1 lg:hidden">
        <div class="mx-auto" wire:ignore id="recaptcha-mobile">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display(['data-callback' => 'onRecaptchaSuccess']) !!}
        </div>
        @error('gRecaptchaResponse')
            <span class="px-2 text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    {{-- Back to Login Link --}}
    <div class="text-center">
        <a
            href="{{ route('login') }}"
            wire:navigate
            class="text-sm text-gray-500 underline transition duration-300 hover:font-semibold hover:text-gray-700"
        >
            ← Kembali ke Halaman Login
        </a>
    </div>

    <script>
        function onRecaptchaSuccess(response) {
            @this.set('gRecaptchaResponse', response);
        }

        // Reset reCAPTCHA
        document.addEventListener('livewire:initialized', () => {
            @this.on('resetRecaptcha', () => {
                // Reset reCAPTCHA widget
                if (typeof grecaptcha !== 'undefined') {
                    const widgets = document.querySelectorAll('.g-recaptcha');
                    widgets.forEach(widget => {
                        const widgetId = widget.getAttribute('data-sitekey');
                        if (widgetId) {
                            grecaptcha.reset();
                        }
                    });
                }

                // Reset Livewire property
                @this.set('gRecaptchaResponse', '');
            });
        });
    </script>
</form>
