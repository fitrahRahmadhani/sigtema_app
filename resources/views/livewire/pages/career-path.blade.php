<div class="space-y-4">
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole
        <flux:breadcrumbs.item>Career Path</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <div class="h-full w-full rounded-xl bg-white p-10">
        <div class="grid h-full w-full grid-cols-3 gap-20 md:gap-4 lg:gap-20">
            {{-- Card --}}
            <div class="col-span-3 h-full w-full space-y-4 md:col-span-1">
                <div class="color-gradient flex h-[90%] w-full flex-col rounded-lg p-4">
                    <h6 class="border-b border-white pb-4 text-3xl font-bold text-white lg:text-5xl">
                        Vacant
                        <br />
                        Positions
                    </h6>
                    <div class="flex h-full w-full items-center justify-center p-4 lg:p-10">
                        <img src="{{ asset('assets/hero/hero_vacant_position.png') }}" class="rounded-full" alt="" />
                    </div>
                </div>
                <div class="flex h-[8%] w-full items-center justify-center">
                    <a
                        href="{{ route('vacant-positions.index') }}"
                        class="color-gradient mx-auto rounded-lg px-8 py-4"
                        wire:navigate
                    >
                        <button class="cursor-pointer font-medium text-white">See More</button>
                    </a>
                </div>
            </div>

            {{-- Card --}}
            <div class="col-span-3 h-full w-full space-y-4 md:col-span-1">
                <div class="color-gradient flex h-[90%] w-full flex-col rounded-lg p-4">
                    <h6 class="border-b border-white pb-4 text-3xl font-bold text-white lg:text-5xl">
                        Candidate
                        <br />
                        Comparison
                    </h6>
                    <div class="flex h-full w-full items-center justify-center p-4 lg:p-10">
                        <img
                            src="{{ asset('assets/hero/hero_cadidate_comparison.png') }}"
                            class="rounded-full"
                            alt=""
                        />
                    </div>
                </div>
                <div class="flex h-[8%] w-full items-center justify-center">
                    <a
                        href="{{ route('candidates-comparison.index') }}"
                        class="color-gradient mx-auto rounded-lg px-8 py-4"
                    >
                        <button class="cursor-pointer font-medium text-white">See More</button>
                    </a>
                </div>
            </div>

            {{-- Card --}}
            <div class="col-span-3 h-full w-full space-y-4 md:col-span-1">
                <div class="color-gradient flex h-[90%] w-full flex-col rounded-lg p-4">
                    <h6 class="border-b border-white pb-4 text-3xl font-bold text-white lg:text-5xl">
                        Employee's
                        <br />
                        Profile
                    </h6>
                    <div class="flex h-full w-full items-center justify-center p-4 lg:p-10">
                        <img src="{{ asset('assets/hero/hero_employee_profile.png') }}" class="rounded-full" alt="" />
                    </div>
                </div>
                <div class="flex h-[8%] w-full items-center justify-center">
                    <a
                        href="{{ route('employees-profile.index') }}"
                        class="color-gradient mx-auto rounded-lg px-8 py-4"
                    >
                        <button class="cursor-pointer font-medium text-white">See More</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
