<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        @hasanyrole('superadmin|admin')
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        @endhasanyrole

        <flux:breadcrumbs.item>Activity Logs</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="color-gradient flex h-12 w-12 items-center justify-center self-start rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Activity Logs</h1>
                    <p class="mt-1 text-sm text-gray-500">Track all system activity and user actions</p>
                </div>
            </div>

            {{-- Total count badge --}}
            <div class="hidden items-center gap-1.5 rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1.5 sm:flex">
                <div class="h-1.5 w-1.5 rounded-full bg-[#721d21]"></div>
                <span class="text-xs font-medium text-zinc-500">
                    {{ $logs->total() }} {{ Str::plural('entry', $logs->total()) }}
                </span>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- YEARLY CHART                                                   --}}
        {{-- ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
            {{-- Chart header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Activity Overview</p>
                    <p class="text-xs text-gray-400">Monthly activity count for {{ $activeYear }}</p>
                </div>
                <span class="rounded-full border border-[#721d21]/20 bg-red-50 px-3 py-1 text-xs font-semibold text-[#721d21]">
                    {{ $activeYear }}
                </span>
            </div>

            {{-- Chart body --}}
            <div class="p-5">
                <div
                    class="activity-chart"
                    data-values="{{ json_encode($chartData) }}"
                    data-year="{{ $activeYear }}"
                    data-active-month="{{ $monthFilter ? (int) substr($monthFilter, 5, 2) : 0 }}"
                >
                    {{-- Rendered by JS below --}}
                    <div class="flex h-40 items-end gap-1.5" id="chart-bars">
                        @php
                            $maxVal = max(max($chartData), 1);
                            $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            $activeMonth = $monthFilter ? (int) substr($monthFilter, 5, 2) : 0;
                        @endphp
                        @foreach ($chartData as $i => $count)
                            @php
                                $heightPct = round(($count / $maxVal) * 100);
                                $isActive  = ($i + 1) === $activeMonth;
                            @endphp
                            <div class="group relative flex flex-1 flex-col items-center gap-1">
                                {{-- Tooltip --}}
                                <div class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-zinc-800 px-2 py-1 text-xs text-white opacity-0 shadow transition-opacity group-hover:opacity-100">
                                    {{ $count }} {{ Str::plural('entry', $count) }}
                                </div>

                                {{-- Bar --}}
                                <div class="relative w-full overflow-hidden rounded-t-md" style="height: 130px;">
                                    <div
                                        class="absolute bottom-0 w-full rounded-t-md transition-all duration-500 {{ $isActive ? 'bg-[#721d21]' : 'bg-[#721d21]/20 group-hover:bg-[#721d21]/50' }}"
                                        style="height: {{ max($heightPct, $count > 0 ? 4 : 0) }}%"
                                    ></div>
                                </div>

                                {{-- Month label --}}
                                <span class="text-[10px] font-medium {{ $isActive ? 'text-[#721d21]' : 'text-gray-400' }}">
                                    {{ $months[$i] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- FILTERS                                                        --}}
        {{-- ============================================================ --}}
        <div class="mb-6 space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div class="grid gap-4 md:grid-cols-4">

                {{-- Month Filter --}}
                <div class="col-span-3">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Month</label>
                    <input
                        type="month"
                        wire:model.live="monthFilter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                    />
                </div>

                {{-- Event Filter --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Event</label>
                    <div class="relative">
                        <select
                            wire:model.live="logFilter"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm text-gray-700 focus:border-[#721d21] focus:ring-1 focus:ring-[#721d21] focus:outline-none"
                        >
                            <option value="">All Events</option>
                            @foreach ($logNames as $name)
                                <option value="{{ $name }}">
                                    {{ Str::title(str_replace(['-', '_'], ' ', $name)) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Results count --}}
            <div class="border-t border-gray-200 pt-3 text-sm text-gray-600">
                Showing
                <span class="font-semibold text-gray-900">{{ $logs->count() }}</span>
                of
                <span class="font-semibold text-gray-900">{{ $logs->total() }}</span>
                entries

                @if ($monthFilter)
                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs text-[#721d21] ring-1 ring-[#721d21]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        {{ \Carbon\Carbon::parse($monthFilter . '-01')->format('F Y') }}
                    </span>
                @endif

                @if ($logFilter)
                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs text-[#721d21] ring-1 ring-[#721d21]/20">
                        {{ Str::title(str_replace(['-', '_'], ' ', $logFilter)) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LOG LIST                                                       --}}
        {{-- ============================================================ --}}
        <div class="space-y-3 overflow-x-auto">

            {{-- Column Headers --}}
            @if ($logs->isNotEmpty())
                <div class="color-gradient hidden grid-cols-12 gap-4 rounded-lg px-4 py-3 text-white md:grid">
                    <div class="col-span-1 text-sm font-semibold">#</div>
                    <div class="col-span-2 text-sm font-semibold">Event</div>
                    <div class="col-span-4 text-sm font-semibold">Description</div>
                    <div class="col-span-4 text-sm font-semibold">Details</div>
                    <div class="col-span-1 text-sm font-semibold">Timestamp</div>
                </div>
            @endif

            @forelse ($logs as $log)
                @php
                    $props = is_array($log->properties) ? $log->properties : $log->properties->toArray();
                @endphp

                {{-- Desktop Row --}}
                <div class="hidden grid-cols-12 items-center gap-4 rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:grid">

                    {{-- Row number --}}
                    <div class="col-span-1 text-xs text-gray-400">
                        {{ $logs->firstItem() + $loop->index }}
                    </div>

                    {{-- Event badge --}}
                    <div class="col-span-2">
                        @if ($log->event)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white px-2.5 py-1 text-xs font-medium text-zinc-600 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#721d21]"></span>
                                {{ Str::title(str_replace(['-', '_'], ' ', $log->event)) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="col-span-4 flex flex-col justify-center">
                        <p class="line-clamp-2 text-sm font-medium text-gray-700">
                            {{ $log->description ?? '—' }}
                        </p>
                    </div>

                    {{-- Properties --}}
                    <div class="col-span-4">
                        @if (!empty($props))
                            <div class="space-y-1">
                                @foreach ($props as $key => $value)
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="shrink-0 text-xs font-semibold text-gray-400">
                                            {{ Str::ucfirst($key) }}:
                                        </span>
                                        <span class="line-clamp-1 text-sm leading-snug text-gray-700">
                                            @if (is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs italic text-gray-300">No details</span>
                        @endif
                    </div>

                    {{-- Timestamp --}}
                    <div class="col-span-1 flex flex-col justify-center">
                        <p class="text-xs text-gray-700">{{ $log->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                    </div>
                </div>

                {{-- Mobile Row --}}
                <div class="block rounded-lg border border-gray-200 bg-white p-4 transition-shadow duration-200 hover:border-[#721d21] hover:shadow-md md:hidden">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                @if ($log->event)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white px-2.5 py-1 text-xs font-medium text-zinc-600 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#721d21]"></span>
                                        {{ Str::title(str_replace(['-', '_'], ' ', $log->event)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $log->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                            </div>
                        </div>

                        @if ($log->description)
                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">Description</p>
                                <p class="mt-0.5 text-sm text-gray-700">{{ $log->description }}</p>
                            </div>
                        @endif

                        @if (!empty($props))
                            <div>
                                <p class="mb-1 text-xs font-medium uppercase text-gray-400">Details</p>
                                <div class="space-y-1">
                                    @foreach ($props as $key => $value)
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="shrink-0 text-xs font-semibold text-gray-400">
                                                {{ Str::ucfirst($key) }}:
                                            </span>
                                            <span class="text-sm leading-snug text-gray-700">
                                                @if (is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <p class="mt-4 font-medium text-gray-500">No activity logs found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your month or event filter</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif

    </div>
</div>