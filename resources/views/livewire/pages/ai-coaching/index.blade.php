<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole
    </flux:breadcrumbs>

    {{-- ============================================================ --}}
    {{-- STATE 1: NOT REGISTERED --}}
    {{-- ============================================================ --}}
    @if (!$isRegistered)
        <div class="flex min-h-[75vh] items-center justify-center">
            <div class="mx-auto max-w-md text-center">
                {{-- Illustration --}}
                <div class="relative mx-auto mb-8 flex h-40 w-40 items-center justify-center">
                    <div class="absolute inset-0 rounded-full bg-zinc-100 opacity-70"></div>
                    <div class="absolute inset-3 rounded-full bg-white shadow-inner"></div>
                    <div class="relative z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <div class="absolute -right-2 -bottom-1 flex h-8 w-8 items-center justify-center rounded-full bg-zinc-200 shadow-sm ring-2 ring-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <h2 class="mb-2 text-2xl font-bold text-zinc-800">Access Restricted</h2>
                <p class="mb-1 text-sm font-semibold text-zinc-500">Your account is not registered as an employee</p>
                <p class="mb-8 text-sm leading-relaxed text-zinc-400">
                    The AI Coaching feature is only available to employees registered in the system.
                </p>

                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-6 py-5 text-left">
                    <p class="mb-3 text-xs font-semibold tracking-wider text-zinc-400 uppercase">What can you do?</p>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-zinc-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 12-13.5"/>
                                </svg>
                            </div>
                            <p class="text-sm text-zinc-600">Contact HR or system Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- STATE 2: REGISTERED --}}
        {{-- ============================================================ --}}
    @else
        <div class="h-full w-full rounded-xl bg-white p-6 md:p-8">
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="color-gradient flex h-12 w-12 shrink-0 items-center justify-center rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3L14.0357 8.16153C14.2236 8.63799 14.3175 8.87622 14.4614 9.0771C14.5889 9.25516 14.7448 9.41106 14.9229 9.53859C15.1238 9.68245 15.362 9.77641 15.8385 9.96432L21 12L15.8385 14.0357C15.362 14.2236 15.1238 14.3175 14.9229 14.4614C14.7448 14.5889 14.5889 14.7448 14.4614 14.9229C14.3175 15.1238 14.2236 15.362 14.0357 15.8385L12 21L9.96432 15.8385C9.77641 15.362 9.68245 15.1238 9.53859 14.9229C9.41106 14.7448 9.25516 14.5889 9.0771 14.4614C8.87622 14.3175 8.63799 14.2236 8.16153 14.0357L3 12L8.16153 9.96432C8.63799 9.77641 8.87622 9.68245 9.0771 9.53859C9.25516 9.41106 9.41106 9.25516 9.53859 9.0771C9.68245 8.87622 9.77641 8.63799 9.96432 8.16153L12 3Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold text-zinc-900 md:text-3xl">AI Coaching</h1>
                            @if ($hasFullAccess)
                                <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Human Captain</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">
                            @if ($chatActive && $coachingMode === 'self')
                                Personal coaching session
                            @elseif ($chatActive && $coachingMode === 'subordinate')
                                Coaching for: <span class="font-semibold text-zinc-700">{{ $selectedSubordinate['name'] ?? '' }}</span>
                            @elseif ($chatActive && $coachingMode === 'human_captain')
                                Human Captain Mode: <span class="font-semibold text-zinc-700">{{ $selectedSubordinate['name'] ?? '' }}</span>
                            @else
                                Select a coaching session to begin
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2">
                    @if ($chatActive)
                        <button wire:click="backToSelection" class="flex items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 text-sm text-zinc-500 transition hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 010 12h-3"/>
                            </svg>
                            Switch Session
                        </button>
                        <button wire:click="clearChat" class="flex items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 text-sm text-zinc-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            Reset Chat
                        </button>
                    @endif
                </div>
            </div>

            <div class="mb-6 h-px bg-zinc-100"></div>

            {{-- ======================================================== --}}
            {{-- SELECTION SCREEN --}}
            {{-- ======================================================== --}}
            @if (!$chatActive)
                <div class="space-y-7">
                    {{-- Mode selection info --}}
                    <p class="text-sm text-zinc-500">
                        @if ($hasFullAccess)
                            Sebagai <strong>Super User Human Captain</strong>, Anda memiliki akses penuh untuk melakukan coaching ke seluruh karyawan di organisasi.
                        @elseif ($hasSubordinates)
                            Sebagai <strong>{{ $employee->position?->name ?? 'Leader' }}</strong>, Anda dapat melakukan coaching untuk diri sendiri atau anggota tim langsung.
                        @else
                            Anda dapat memulai sesi coaching AI personal.
                        @endif
                    </p>

                    {{-- Self coaching --}}
                    <div>
                        <p class="mb-3 text-xs font-semibold tracking-widest text-zinc-400 uppercase">Personal Coaching</p>
                        <button wire:click="startCoaching('self')" class="group w-full rounded-2xl border border-zinc-200 bg-zinc-50 p-5 text-left transition hover:border-[#721d21]/30 hover:bg-[#721d21]/5 hover:shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="color-gradient flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-base font-bold text-white shadow-sm">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-zinc-800">{{ $employee->name }}</p>
                                        <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs text-zinc-500">You</span>
                                    </div>
                                    <p class="mt-0.5 text-sm text-zinc-400">
                                        {{ $employee->employee_number }} &bull;
                                        {{ $employee->position?->name ?? 'No Position' }} &bull; Band {{ $employee->band }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-1.5 text-sm font-medium text-[#721d21] opacity-0 transition group-hover:opacity-100">
                                    Start
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>

                    {{-- Manager coaching (only if has subordinates) --}}
                    @if ($hasSubordinates)
                        <div>
                            <p class="mb-3 text-xs font-semibold tracking-widest text-zinc-400 uppercase">
                                Direct Team Members
                                <span class="ml-1 font-normal text-zinc-300 normal-case">({{ count($subordinates) }} people)</span>
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach ($subordinates as $sub)
                                    <button wire:click="startCoaching('subordinate', {{ $sub['id'] }})" class="group rounded-2xl border border-zinc-200 bg-white p-4 text-left shadow-sm transition hover:border-[#721d21]/30 hover:shadow-md">
                                        <div class="mb-3 flex items-center justify-between">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-100 text-sm font-bold text-zinc-500 transition group-hover:bg-[#721d21]/10 group-hover:text-[#721d21]">
                                                {{ strtoupper(substr($sub['name'], 0, 1)) }}
                                            </div>
                                            <span class="rounded-full border border-zinc-100 bg-zinc-50 px-2.5 py-0.5 text-xs text-zinc-400">Band {{ $sub['band'] }}</span>
                                        </div>
                                        <p class="truncate text-sm font-semibold text-zinc-800">{{ $sub['name'] }}</p>
                                        <p class="mt-0.5 truncate text-xs text-zinc-400">{{ $sub['position'] }}</p>
                                        <div class="mt-3 flex items-center gap-1 text-xs font-medium text-[#721d21] opacity-0 transition group-hover:opacity-100">
                                            Start Coaching
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Human Captain mode (only if has permission) --}}
                    @if ($hasFullAccess)
                        <div>
                            <p class="mb-3 text-xs font-semibold tracking-widest text-blue-600 uppercase">
                                Super User Human Captain Mode
                                <span class="ml-1 font-normal text-zinc-400 normal-case">(Coaching seluruh karyawan)</span>
                            </p>

                            {{-- Search input --}}
                            <div class="mb-4">
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="searchEmployee" placeholder="Cari karyawan..." class="w-full rounded-xl border border-blue-200 bg-blue-50 py-3 pl-12 pr-4 text-sm text-zinc-800 placeholder-zinc-400 transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-purple-200 focus:outline-none"/>
                                </div>
                            </div>

                            {{-- Search results --}}
                            @if (count($searchResults) > 0)
                                <div class="mb-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                    @foreach ($searchResults as $emp)
                                        <button wire:click="startCoaching('human_captain', {{ $emp['id'] }})" class="group rounded-2xl border border-blue-200 bg-white p-4 text-left shadow-sm transition hover:border-blue-400 hover:shadow-md">
                                            <div class="mb-3 flex items-center justify-between">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-600 transition group-hover:bg-blue-200">
                                                    {{ strtoupper(substr($emp['name'], 0, 1)) }}
                                                </div>
                                                <span class="rounded-full border border-blue-100 bg-blue-50 px-2.5 py-0.5 text-xs text-blue-600">Band {{ $emp['band'] }}</span>
                                            </div>
                                            <p class="truncate text-sm font-semibold text-zinc-800">{{ $emp['name'] }}</p>
                                            <p class="mt-0.5 truncate text-xs text-zinc-400">{{ $emp['employee_number'] }} &bull; {{ $emp['position'] }}</p>
                                            <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-blue-600 opacity-0 transition group-hover:opacity-100">
                                                Start Human Captain Session
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                                </svg>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif (strlen($searchEmployee) >= 2)
                                <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center">
                                    <p class="text-sm text-zinc-500">Tidak ada karyawan ditemukan</p>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-blue-200 bg-blue-50 p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
                                    </svg>
                                    <p class="text-sm text-blue-600">Ketik nama atau nomor karyawan untuk mencari</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- ======================================================== --}}
            {{-- ACTIVE CHAT --}}
            {{-- ======================================================== --}}
            @if ($chatActive)
                <div x-data="{
                    showScrollButton: false,
                    isAtBottom: true,
                    init() {
                        // Watch for Livewire updates - only auto-scroll if at bottom
                        this.$watch('$wire.messages', () => {
                            this.$nextTick(() => {
                                const container = this.$refs.chatContainer;
                                if (container && this.isAtBottom) {
                                    container.scrollTop = container.scrollHeight;
                                }
                                this.checkScrollPosition();
                            });
                        });
                    },
                    scrollToBottom() {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                        }
                    },
                    checkScrollPosition() {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            const distanceFromBottom = container.scrollHeight - container.scrollTop - container.clientHeight;
                            this.isAtBottom = distanceFromBottom <= 50;
                            this.showScrollButton = !this.isAtBottom;
                        }
                    }
                }"
                class="relative flex h-[calc(100vh-11rem)] flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50">

                    {{-- Messages --}}
                    <div x-ref="chatContainer" id="chatMessages" @scroll="checkScrollPosition()" class="flex-1 space-y-4 overflow-y-auto p-5">
                        @foreach ($messages as $index => $message)
                            <div class="{{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }} flex items-end gap-2" wire:key="msg-{{ $index }}">
                                @if ($message['role'] === 'assistant')
                                    <div class="color-gradient flex h-8 w-8 shrink-0 items-center justify-center rounded-full shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3L14.0357 8.16153C14.2236 8.63799 14.3175 8.87622 14.4614 9.0771C14.5889 9.25516 14.7448 9.41106 14.9229 9.53859C15.1238 9.68245 15.362 9.77641 15.8385 9.96432L21 12L15.8385 14.0357C15.362 14.2236 15.1238 14.3175 14.9229 14.4614C14.7448 14.5889 14.5889 14.7448 14.4614 14.9229C14.3175 15.1238 14.2236 15.362 14.0357 15.8385L12 21L9.96432 15.8385C9.77641 15.362 9.68245 15.1238 9.53859 14.9229C9.41106 14.7448 9.25516 14.5889 9.0771 14.4614C8.87622 14.3175 8.63799 14.2236 8.16153 14.0357L3 12L8.16153 9.96432C8.63799 9.77641 8.87622 9.68245 9.0771 9.53859C9.25516 9.41106 9.41106 9.25516 9.53859 9.0771C9.68245 8.87622 9.77641 8.63799 9.96432 8.16153L12 3Z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="{{ $message['role'] === 'user' ? 'order-first' : '' }} max-w-[75%]">
                                    <div class="{{ $message['role'] === 'user' ? 'color-gradient rounded-br-sm text-white' : 'rounded-bl-sm bg-white text-zinc-800 ring-1 ring-zinc-100' }} rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-sm">
                                        @if ($message['role'] === 'assistant')
                                            {!! \Illuminate\Support\Str::markdown($message['content']) !!}
                                        @else
                                            {{ $message['content'] }}
                                        @endif
                                    </div>
                                    <p class="{{ $message['role'] === 'user' ? 'text-right' : 'text-left' }} mt-1 text-xs text-zinc-400">{{ $message['timestamp'] }}</p>
                                </div>

                                @if ($message['role'] === 'user')
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-xs font-bold text-zinc-600">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @if ($isLoading)
                            <div class="flex items-end justify-start gap-2">
                                <div class="color-gradient flex h-8 w-8 shrink-0 items-center justify-center rounded-full shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </div>
                                <div class="rounded-2xl rounded-bl-sm bg-white px-4 py-3 shadow-sm ring-1 ring-zinc-100">
                                    <div class="flex items-center gap-1">
                                        <div class="h-2 w-2 animate-bounce rounded-full bg-zinc-400 [animation-delay:0ms]"></div>
                                        <div class="h-2 w-2 animate-bounce rounded-full bg-zinc-400 [animation-delay:150ms]"></div>
                                        <div class="h-2 w-2 animate-bounce rounded-full bg-zinc-400 [animation-delay:300ms]"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Scroll to Bottom Button --}}
                    <button
                        x-show="showScrollButton"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        @click="scrollToBottom()"
                        class="absolute bottom-28 right-6 z-10 flex items-center gap-2 rounded-full bg-[#721d21] px-4 py-2 text-sm font-medium text-white shadow-lg transition hover:bg-[#5a1719] active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        Chat terbaru
                    </button>

                    {{-- Input Area --}}
                    <div class="border-t border-zinc-200 bg-white p-4">
                        @if (count($messages) === 1)
                            <div class="mb-3 flex flex-wrap gap-2">
                                <p class="mb-1 w-full text-xs text-zinc-400">Coba tanyakan:</p>
                                @foreach ($coachingMode === 'human_captain'
                                    ? [
                                        'Analisis potensi karyawan ini secara komprehensif',
                                        'Area pengembangan apa yang paling urgent?',
                                        'Rekomendasi training untuk karyawan ini'
                                    ]
                                    : ($coachingMode === 'subordinate'
                                        ? [
                                            'Apa potensi terbesar anggota tim ini?',
                                            'Kompetensi apa yang paling perlu dikembangkan?',
                                            'Seberapa besar risiko burnout anggota tim ini?'
                                        ]
                                        : [
                                            'Apa kekuatan utama saya?',
                                            'Kompetensi apa yang perlu saya tingkatkan?',
                                            'Apa yang bisa saya capai dengan profil saya saat ini?'
                                        ])
                                    as $suggestion)
                                    <button wire:click="$set('userInput', '{{ $suggestion }}')" class="rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1.5 text-xs text-zinc-500 transition hover:border-[#721d21]/30 hover:bg-[#721d21]/5 hover:text-[#721d21]">
                                        {{ $suggestion }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <form wire:submit="sendMessage" class="flex items-end gap-3">
                            <div class="relative flex-1">
                                <textarea wire:model.live="userInput" wire:keydown.enter.prevent="sendMessage" placeholder="Ketik pertanyaan Anda... (maks. {{ $maxChars }} karakter)" rows="1" maxlength="{{ $maxChars }}" :disabled="$wire.isLoading" class="h-20 w-full resize-none rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 pr-14 text-sm text-zinc-800 placeholder-zinc-400 transition focus:border-zinc-400 focus:bg-white focus:ring-2 focus:ring-zinc-200 focus:outline-none disabled:opacity-60" id="chat-input"></textarea>
                                <div class="absolute right-3 bottom-3">
                                    <span class="{{ $charsLeft <= 20 ? 'font-semibold text-red-500' : 'text-zinc-300' }} text-xs">{{ $charsLeft }}</span>
                                </div>
                            </div>
                            <button type="submit" :disabled="$wire.isLoading || $wire.userInput.trim() === ''" class="color-gradient mb-2 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white shadow-sm transition disabled:cursor-not-allowed disabled:opacity-40">
                                @if ($isLoading)
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L5.999 12z"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>