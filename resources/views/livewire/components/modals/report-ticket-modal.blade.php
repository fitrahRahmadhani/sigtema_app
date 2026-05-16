<div
    x-data="{ open: false }"
    @keydown.escape.window="if(open){ open = false; $wire.close(); }"
    @ticket-submitted.window="open = false"
>
    <button
        @click="open = true; $wire.open();"
        class="mr-8 flex cursor-pointer items-center gap-2 rounded-xl bg-gradient-to-r from-[#721d21] to-[#8b252a] px-5 py-2.5 text-sm font-medium text-white shadow-lg"
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
                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
            />
        </svg>

        Report
        <span class="hidden md:inline">Issue</span>
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak class="relative z-[10000]" style="display: none">
            {{-- Backdrop --}}
            <div
                x-show="open"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"
                @click="open = false; $wire.close();"
            ></div>

            {{-- Panel --}}
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div
                        x-show="open"
                        x-transition:enter="duration-300 ease-out"
                        x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                        x-transition:leave="duration-200 ease-in"
                        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                        x-transition:leave-end="translate-y-4 scale-95 opacity-0"
                        class="relative w-full max-w-2xl transform transition-all"
                        @click.stop
                    >
                        <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                            {{-- Modal Header --}}
                            <div class="color-gradient flex items-center justify-between px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-5 w-5 text-white"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Report an Issue</h3>
                                        <p class="text-xs text-white/70">
                                            We'll review your report as soon as possible
                                        </p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="open = false; $wire.close();"
                                    class="rounded-lg p-2 text-white/70 transition hover:bg-white/20 hover:text-white"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Body --}}
                            <div class="px-6 py-5">
                                <form wire:submit.prevent="save">
                                    {{-- Two-column layout --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- LEFT COLUMN --}}
                                        <div class="space-y-4">
                                            {{-- Title --}}
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                                    Issue Title
                                                    <span class="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    wire:model="title"
                                                    placeholder="e.g. Cannot access the employee page"
                                                    class="@error("title")
                                                        border-red-400
                                                        focus:border-red-400
                                                        focus:ring-red-400
                                                    @else
                                                        border-gray-300
                                                        focus:border-[#721d21]
                                                        focus:ring-[#721d21]
                                                    @enderror w-full rounded-lg border px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition focus:ring-1 focus:outline-none"
                                                />
                                                @error("title")
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Description --}}
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                                    Description
                                                    <span class="text-red-500">*</span>
                                                </label>
                                                <textarea
                                                    wire:model="description"
                                                    rows="6"
                                                    placeholder="Describe the issue in detail — what happened, what you expected, and steps to reproduce..."
                                                    class="@error("description")
                                                        border-red-400
                                                        focus:border-red-400
                                                        focus:ring-red-400
                                                    @else
                                                        border-gray-300
                                                        focus:border-[#721d21]
                                                        focus:ring-[#721d21]
                                                    @enderror w-full resize-none rounded-lg border px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition focus:ring-1 focus:outline-none"
                                                ></textarea>
                                                @error("description")
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- RIGHT COLUMN --}}
                                        <div class="space-y-4">
                                            {{-- Attachment --}}
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                                    Attachment
                                                    <span class="ml-1 text-xs font-normal text-gray-400">
                                                        (optional)
                                                    </span>
                                                </label>

                                                @if ($attachment)
                                                    <div
                                                        class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3"
                                                    >
                                                        @if (in_array($attachment->getClientOriginalExtension(), ["jpg", "jpeg", "png", "gif"]))
                                                            <img
                                                                src="{{ $attachment->temporaryUrl() }}"
                                                                class="h-12 w-12 rounded-md object-cover"
                                                                alt="Preview"
                                                            />
                                                        @else
                                                            <div
                                                                class="flex h-12 w-12 items-center justify-center rounded-md bg-gray-200"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-6 w-6 text-gray-500"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-sm font-medium text-gray-700">
                                                                {{ $attachment->getClientOriginalName() }}
                                                            </p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ number_format($attachment->getSize() / 1024, 1) }}
                                                                KB
                                                            </p>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            wire:click="removeAttachment"
                                                            class="shrink-0 rounded-md p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-500"
                                                        >
                                                            <svg
                                                                class="h-4 w-4"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <label
                                                        for="ticket-attachment"
                                                        class="flex h-[10.5rem] cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-5 text-center transition hover:border-[#721d21]/40 hover:bg-red-50/30"
                                                    >
                                                        <div
                                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-5 w-5 text-gray-400"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-600">
                                                                Click to upload
                                                            </p>
                                                            <p class="mt-0.5 text-xs text-gray-400">
                                                                JPG, PNG, GIF, PDF, DOC, DOCX
                                                            </p>
                                                            <p class="text-xs text-gray-400">Max 5MB</p>
                                                        </div>
                                                        <input
                                                            id="ticket-attachment"
                                                            type="file"
                                                            wire:model="attachment"
                                                            accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx"
                                                            class="sr-only"
                                                        />
                                                    </label>

                                                    <div
                                                        wire:loading
                                                        wire:target="attachment"
                                                        class="mt-2 flex w-full items-center gap-2 text-xs text-gray-500"
                                                    >
                                                        <div>
                                                            <svg
                                                                class="h-3.5 w-3.5 animate-spin"
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
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                                                ></path>
                                                            </svg>
                                                        </div>
                                                        <p class="w-fit">Uploading...</p>
                                                    </div>
                                                @endif

                                                @error("attachment")
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Reporter info (read-only) --}}
                                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                                <p
                                                    class="mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase"
                                                >
                                                    Reported by
                                                </p>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-gray-500"
                                                    >
                                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-semibold text-gray-700">
                                                            {{ Auth::user()->name }}
                                                        </p>
                                                        <p class="truncate text-xs text-gray-400">
                                                            {{ Auth::user()->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-5 flex justify-end gap-3 border-t border-gray-200 pt-4">
                                        <button
                                            type="button"
                                            @click="open = false; $wire.close();"
                                            wire:loading.attr="disabled"
                                            class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-gradient-to-r from-[#721d21] to-[#8b252a] px-5 py-2.5 text-sm font-medium text-white shadow-lg transition hover:from-[#5a1619] hover:to-[#721d21] focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            <svg
                                                wire:loading.remove
                                                wire:target="save"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"
                                                />
                                            </svg>
                                            <svg
                                                wire:loading
                                                wire:target="save"
                                                class="h-4 w-4 animate-spin"
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
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                                ></path>
                                            </svg>
                                            <span wire:loading.remove wire:target="save">Submit Report</span>
                                            <span wire:loading wire:target="save">Submitting...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
