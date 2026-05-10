<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Issue Reports</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl shadow-sm">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6 text-white"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Issue Reports</h1>
                    <p class="mt-1 text-sm text-gray-500">Review and manage submitted reports from users</p>
                </div>
            </div>

            {{-- Total badge --}}
            <div class="hidden items-center gap-1.5 rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1.5 sm:flex">
                <div class="h-1.5 w-1.5 rounded-full bg-[#721d21]"></div>
                <span class="text-xs font-medium text-zinc-500">
                    {{ $tickets->total() }} {{ Str::plural('report', $tickets->total()) }}
                </span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div class="grid gap-4 md:grid-cols-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Search</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by title, description, or reporter..."
                            class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 text-sm focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        />
                    </div>
                </div>

                {{-- Month --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Month</label>
                    <input
                        type="month"
                        wire:model.live="monthFilter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                    />
                </div>

                {{-- Sort --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sort By</label>
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title_asc">Title (A-Z)</option>
                            <option value="title_desc">Title (Z-A)</option>
                        </select>
                        <svg
                            class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Results info --}}
            <div class="border-t border-gray-200 pt-4 text-sm text-gray-600">
                Showing
                <span class="font-semibold text-gray-900">{{ $tickets->count() }}</span>
                of
                <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span>
                reports

                @if ($monthFilter)
                    <span
                        class="ml-2 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs text-[#721d21] ring-1 ring-[#721d21]/20"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3 w-3"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"
                            />
                        </svg>
                        {{ \Carbon\Carbon::parse($monthFilter . '-01')->format('F Y') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-3 overflow-x-auto">
            {{-- Column headers --}}
            @if ($tickets->isNotEmpty())
                <div class="color-gradient hidden grid-cols-12 gap-4 rounded-lg px-4 py-3 text-white md:grid">
                    <div class="col-span-1 text-sm font-semibold">#</div>
                    <div class="col-span-2 text-sm font-semibold">Reporter</div>
                    <div class="col-span-3 text-sm font-semibold">Title</div>
                    <div class="col-span-4 text-sm font-semibold">Description</div>
                    <div class="col-span-1 text-center text-sm font-semibold">File</div>
                    <div class="col-span-1 text-center text-sm font-semibold">Action</div>
                </div>
            @endif

            @forelse ($tickets as $ticket)
                {{-- Desktop row --}}
                <div
                    class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid"
                >
                    {{-- # --}}
                    <div class="col-span-1 text-xs text-gray-400">
                        {{ $tickets->firstItem() + $loop->index }}
                    </div>

                    {{-- Reporter --}}
                    <div class="col-span-2 flex flex-col justify-center">
                        <p class="truncate text-sm font-semibold text-gray-800">
                            {{ $ticket->user?->name ?? 'Unknown' }}
                        </p>
                        <p class="truncate text-xs text-gray-400">{{ $ticket->user?->email }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">{{ $ticket->created_at->format('d M Y · H:i') }}</p>
                    </div>

                    {{-- Title --}}
                    <div class="col-span-3 flex flex-col justify-center">
                        <p class="line-clamp-2 text-sm font-medium text-gray-800">{{ $ticket->title }}</p>
                    </div>

                    {{-- Description --}}
                    <div class="col-span-4 flex flex-col justify-center">
                        <p class="line-clamp-3 text-sm text-gray-600">{{ $ticket->description }}</p>
                    </div>

                    {{-- Attachment --}}
                    <div class="col-span-1 flex items-center justify-center">
                        @if ($ticket->attachment)
                            <a
                                href="{{ Storage::url($ticket->attachment) }}"
                                target="_blank"
                                title="View attachment"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-500 transition hover:border-[#721d21]/30 hover:bg-red-50 hover:text-[#721d21]"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"
                                    />
                                </svg>
                            </a>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </div>

                    {{-- Action --}}
                    <div class="col-span-1 flex items-center justify-center">
                        <button
                            wire:click="confirmDelete({{ $ticket->id }}, '{{ addslashes($ticket->title) }}')"
                            class="rounded-lg p-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                            title="Delete report"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile card --}}
                <div
                    class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden"
                >
                    <div class="space-y-3">
                        {{-- Header --}}
                        <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-3">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-gray-800">{{ $ticket->title }}</p>
                                <p class="text-xs text-gray-400">{{ $ticket->created_at->format('d M Y · H:i') }}</p>
                            </div>
                            <button
                                wire:click="confirmDelete({{ $ticket->id }}, '{{ addslashes($ticket->title) }}')"
                                class="shrink-0 rounded-lg p-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>
                            </button>
                        </div>

                        {{-- Reporter --}}
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase">Reporter</p>
                            <p class="mt-0.5 text-sm font-semibold text-gray-700">
                                {{ $ticket->user?->name ?? 'Unknown' }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $ticket->user?->email }}</p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase">Description</p>
                            <p class="mt-0.5 text-sm text-gray-600">{{ $ticket->description }}</p>
                        </div>

                        {{-- Attachment --}}
                        @if ($ticket->attachment)
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase">Attachment</p>
                                <a
                                    href="{{ Storage::url($ticket->attachment) }}"
                                    target="_blank"
                                    class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-[#721d21] underline underline-offset-2"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"
                                        />
                                    </svg>
                                    View file
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="mx-auto h-12 w-12 text-gray-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                        />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No reports found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your search or month filter</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($tickets->hasPages())
            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    {{-- Delete confirmation modal (same as Users pattern) --}}
    <x-ui.modals.delete-confirmation />
</div>
