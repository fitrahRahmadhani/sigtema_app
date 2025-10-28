<div
    role="status"
    id="toaster"
    x-data="toasterHub(@js($toasts), @js($config))"
    @class([
        "pointer-events-none fixed z-50 flex w-full flex-col p-4 sm:p-6",
        "bottom-0" => $alignment->is("bottom"),
        "top-1/2 -translate-y-1/2" => $alignment->is("middle"),
        "top-0" => $alignment->is("top"),
        "items-start rtl:items-end" => $position->is("left"),
        "items-center" => $position->is("center"),
        "items-end rtl:items-start" => $position->is("right"),
    ])
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.isVisible"
            x-init="$nextTick(() => toast.show($el))"
            @if ($alignment->is("bottom"))
                x-transition:enter-start="translate-y-12 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
            @elseif ($alignment->is("top"))
                x-transition:enter-start="-translate-y-12 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
            @else
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
            @endif
            x-transition:leave-end="scale-90 opacity-0"
            @class(["pointer-events-auto relative w-full max-w-xs transform transition duration-300 ease-in-out"])
        >
            <div
                class="{{ $alignment->is("bottom") ? "mt-3" : "mb-3" }} flex w-full gap-3 rounded-lg bg-white p-3 shadow-lg select-none dark:border dark:border-neutral-500 dark:bg-neutral-600"
            >
                <div
                    class="h-fit rounded-md border p-1"
                    :class="toast.select({ error: 'bg-red-500/15 border-red-200 dark:border-red-400/50', info: 'bg-blue-500/15 border-blue-200 dark:border-blue-400/50', success: 'bg-green-500/15 border-green-200 dark:border-green-400/50', warning: 'bg-orange-500/15 border-orange-200 dark:border-orange-400/50' })"
                >
                    {{-- Icon --}}
                    <template x-if="toast.type === 'success'" class="text-green-500">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-5 text-green-500"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'" class="text-red-500">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-5 text-red-500"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'" class="text-blue-500">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-5 text-blue-500"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'" class="text-yellow-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-5 text-yellow-500"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </template>
                </div>
                <div class="min-w-0 flex-1">
                    {{-- Header (Title) - Default berdasarkan type --}}
                    <p
                        x-text="
                            toast.select({
                                error: 'Oops! Terjadi Kesalahan',
                                info: 'Informasi untuk Anda',
                                success: 'Yeay! Berhasil',
                                warning: 'Perlu Perhatian',
                            })
                        "
                        class="mb-0.5 font-semibold text-neutral-900 dark:text-white"
                    ></p>
                    {{-- Sub-header (Message) --}}
                    <p x-text="toast.message" class="text-sm text-neutral-600 dark:text-neutral-300"></p>
                </div>
                @if ($closeable)
                    <button
                        @click="toast.dispose()"
                        aria-label="@lang("close")"
                        class="h-fit flex-shrink-0 rounded-md p-1.5 text-gray-500 hover:bg-black/5 focus:outline-hidden focus:outline-none dark:bg-white/5 dark:text-gray-300"
                    >
                        <svg
                            class="h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </template>
</div>
