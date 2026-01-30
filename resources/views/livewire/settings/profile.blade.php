<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            {{-- Nama input --}}
            <div>
                <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

                <flux:text class="mt-1 text-sm text-gray-500">
                    {{ __('Name can only contain letters and spaces') }}
                </flux:text>
            </div>

            {{-- Email display (read-only) --}}
            <div>
                <flux:text class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Email') }}
                </flux:text>
                <div
                    class="mt-2 rounded-lg border border-gray-300 bg-gray-50 p-3 dark:border-gray-600 dark:bg-gray-700"
                >
                    <flux:text class="text-gray-900 dark:text-gray-300">
                        {{ auth()->user()->email }}
                    </flux:text>
                </div>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div class="mt-4">
                        <flux:text>
                            {{ __('Your email address is unverified.') }}

                            <flux:link
                                class="cursor-pointer text-sm"
                                wire:click.prevent="resendVerificationNotification"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="!dark:text-green-400 mt-2 font-medium !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
