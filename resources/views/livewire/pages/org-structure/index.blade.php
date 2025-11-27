<div>
    <div class="org-chart-container">
        <div wire:key="org-chart-main">
            <x-ui.modals.delete-confirmation />
            <!-- Header Controls -->
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#721d21] to-[#8b252a] shadow-sm"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="currentColor"
                                class="size-7 text-white"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 1-1 0v-1z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Organization Structure</h1>
                            <p class="mt-1 text-sm text-gray-500">Manage your company's organization structure</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a
                        href="{{ route('organization-structure.table') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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
                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"
                            />
                        </svg>
                        Table View
                    </a>
                </div>
            </div>

            <!-- Org Chart Container -->
            <div class="relative rounded-xl border border-gray-200 bg-white shadow-sm">
                <!-- Scrollable Area -->
                <div id="orgChartScrollArea" class="org-chart-scroll-area relative overflow-auto p-8">
                    <div
                        id="orgChartWrapper"
                        class="org-chart-wrapper min-w-max"
                        style="transform: scale({{ $zoom / 100 }}); transform-origin: top center"
                    >
                        <!-- Render Nodes via Blade -->
                        @php
                            $renderNode = function ($nodeId, $level = 0) use (&$renderNode, &$nodes) {
                                $node = $this->getNode($nodeId);
                                if (! $node || ! $node['visible']) {
                                    return '';
                                }

                                $children = $this->getChildNodes($nodeId);
                                $hasVisibleChildren = count($children) > 0;
                                $isCollapsed = $node['collapsed'] ?? false;
                                $totalChildren = collect($this->nodes)
                                    ->where('parent_id', $nodeId)
                                    ->count();

                                // Node styling
                                $cardWidthClass = 'w-80';
                                $textSize = 'text-base';
                                $subtextSize = 'text-sm';

                                $nodeHtmlId = 'node-' . $nodeId;
                                $childrenContainerId = 'children-' . $nodeId;
                                $isVacant = $node['name'] == 'Vacant Position';

                                $output = '<div class="org-node relative">';
                                $output .= '<div class="flex flex-col items-center">';

                                // Node Card
                                $output .= '<div class="node-card-wrapper relative mb-4 mx-4" id="' . $nodeHtmlId . '">';
                                $output .= '<div class="' . $cardWidthClass . ' flex cursor-default items-center gap-4 rounded-xl border ' . ($isVacant ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300') . ' p-4 shadow-lg transition-all duration-200 hover:shadow-xl relative">';

                                // Profile Photo
                                $photoUrl = $node['photo'] ? asset('storage/' . $node['photo']) : null;
                                $output .= '<div class="flex-shrink-0">';
                                if ($photoUrl) {
                                    $output .= '<img src="' . e($photoUrl) . '" alt="Profile" class="h-20 w-14 rounded-lg object-cover bg-gray-100 border border-gray-200">';
                                } else {
                                    $output .= '<div class="flex h-20 w-14 items-center justify-center rounded-lg ' . ($isVacant ? 'bg-red-100 text-red-500' : 'bg-gray-100 text-gray-400') . '">';
                                    $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">';
                                    $output .= '<path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />';
                                    $output .= '</svg>';
                                    $output .= '</div>';
                                }
                                $output .= '</div>';

                                // Node Information
                                $output .= '<div class="flex flex-col text-left flex-1 min-w-0">';
                                $output .= '<div class="' . $textSize . ' font-bold' . ($isVacant ? ' text-red-500 italic' : ' text-gray-800') . '">' . e($node['name']) . '</div>';
                                $output .= '<div class="mt-0.5 ' . $subtextSize . ' font-medium ' . ($isVacant ? 'text-red-500' : 'text-gray-600') . ' truncate">' . e($node['title']) . '</div>';
                                $output .= '<div class="text-xs ' . ($isVacant ? 'text-red-500' : 'text-gray-500') . ' truncate">' . e($node['department']) . '</div>';

                                // Collapse/Expand Button
                                if ($this->hasChildren($nodeId)) {
                                    $output .= '<div wire:click="toggleNode(' . $node['id'] . ')" class="mt-2 cursor-pointer text-xs font-medium p-1 px-2 rounded-full inline-block ' . ($isVacant ? (! $isCollapsed ? 'text-red-500 bg-red-100' : 'text-red-500 hover:bg-red-100') : (! $isCollapsed ? 'text-[#721d21] bg-[#721d2110]' : 'text-[#721d21] hover:bg-[#721d2110]')) . '">';
                                    $output .= $isCollapsed ? '► Show (' . $totalChildren . ')' : '▼ Hide';
                                    $output .= '</div>';
                                }
                                $output .= '</div>';

                                // Popover Menu Button with Alpine.js - FIXED Z-INDEX
                                $output .= '<div class="relative flex flex-col self-start ml-2" x-data=\'{ open: false }\' @click.outside="open = false">';

                                // Toggle Button
                                $output .= '<button @click="open = !open" class="flex h-8 w-8 items-center justify-center rounded-md ' . ($isVacant ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200') . ' transition-colors" title="Menu">';
                                $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />';
                                $output .= '</svg>';
                                $output .= '</button>';

                                // Popover Menu - FIXED Z-INDEX
                                $output .= '<div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-10 z-50 w-56 rounded-lg border ' . ($isVacant ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200') . ' shadow-xl" style="display: none;" @click="open = false">';
                                $output .= '<div class="p-2 space-y-1">';

                                // Candidate Comparison
                                $comparisonUrl = route('dashboard');
                                $output .= '<a href="' . $comparisonUrl . '" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />';
                                $output .= '</svg>';
                                $output .= '<span class="text-sm font-medium">Candidate Comparison</span>';
                                $output .= '</a>';

                                // Employee Profile
                                if ($node['slug'] ?? false) {
                                    $profileUrl = route('employees.show', $node['slug']);
                                    $output .= '<a href="' . $profileUrl . '" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                    $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                    $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />';
                                    $output .= '</svg>';
                                    $output .= '<span class="text-sm font-medium">Employee Profile</span>';
                                    $output .= '</a>';
                                }

                                // Add Staff
                                $output .= '<button wire:click="openAddStaffModal(' . $node['position_id'] . ')" class="w-full flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />';
                                $output .= '</svg>';
                                $output .= '<span class="text-sm font-medium">Add Staff</span>';
                                $output .= '</button>';

                                // Remove hierarchy
                                $output .= '<button wire:click="confirmDelete(' . $node['hierarchy_id'] . ', \'' . addslashes($node['title']) . '\')" class="w-full flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                $output .= ' <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />';
                                $output .= '</svg>';
                                $output .= '<span class="text-sm font-medium">Remove Hierarchy</span>';
                                $output .= '</button>';

                                $output .= '</div>';
                                $output .= '</div>';
                                $output .= '</div>';

                                $output .= '</div>';

                                // Bottom Connector
                                if ($hasVisibleChildren) {
                                    $output .= '<div class="connector-bottom absolute -bottom-4 left-1/2 h-4 w-0.5 -translate-x-1/2 bg-gray-400"></div>';
                                }

                                $output .= '</div>';

                                // Children Container
                                if ($hasVisibleChildren && ! $isCollapsed) {
                                    $childCount = count($children);
                                    $output .= '<div class="children-container relative flex gap-8" id="' . $childrenContainerId . '">';
                                    if ($childCount > 1) {
                                        $output .= '<div class="horizontal-line absolute top-0 h-0.5 bg-gray-400" data-parent="' . $nodeId . '"></div>';
                                    }
                                    foreach ($children as $child) {
                                        $output .= '<div class="child-wrapper relative flex flex-col items-center" data-child-id="' . $child['id'] . '">';
                                        $output .= '<div class="connector-top h-8 w-0.5 bg-gray-400"></div>';
                                        $output .= $renderNode($child['id'], $level + 1);
                                        $output .= '</div>';
                                    }
                                    $output .= '</div>';
                                }

                                $output .= '</div></div>';
                                return $output;
                            };

                            $roots = collect($this->nodes)
                                ->whereNull('parent_id')
                                ->where('visible', true);
                        @endphp

                        <div class="flex justify-center p-5">
                            @foreach ($roots as $root)
                                {!! $renderNode($root['id']) !!}
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Zoom Controls -->
                <div class="absolute right-4 bottom-4 z-20">
                    <div class="flex items-center gap-2 rounded-lg bg-gray-100 p-2 shadow-lg">
                        <button
                            wire:click="zoomOut"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                            {{ $zoom <= 50 ? 'disabled' : '' }}
                        >
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>

                        <div class="flex min-w-[60px] flex-col items-center">
                            <div>
                                <span id="zoomValue" class="text-lg font-semibold text-gray-800">
                                    {{ $zoom }}
                                </span>
                                <span>%</span>
                            </div>
                            <span class="text-xs text-gray-500">Zoom</span>
                        </div>

                        <button
                            wire:click="zoomIn"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                            {{ $zoom >= 200 ? 'disabled' : '' }}
                        >
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add Staff Modal -->
        <div
            x-data="{
                show: @entangle('showAddStaffModal'),
                init() {
                    this.$watch('show', (value) => {
                        if (value) {
                            document.body.classList.add('overflow-hidden')
                        } else {
                            document.body.classList.remove('overflow-hidden')
                        }
                    })
                },
            }"
            x-show="show"
            x-cloak
            class="relative z-50"
        >
            <!-- Background overlay -->
            <div
                x-show="show"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"
                aria-hidden="true"
                @click="$wire.closeAddStaffModal()"
            ></div>

            <!-- Modal panel -->
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div
                        x-show="show"
                        x-transition:enter="duration-300 ease-out"
                        x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                        x-transition:leave="duration-200 ease-in"
                        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                        x-transition:leave-end="translate-y-4 scale-95 opacity-0"
                        class="relative w-full max-w-2xl transform transition-all"
                        @click.outside="$wire.closeAddStaffModal()"
                        @keydown.escape.window="$wire.closeAddStaffModal()"
                    >
                        <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                            <div class="bg-white px-6 pt-6 pb-4">
                                <div class="flex items-start">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100"
                                        >
                                            <svg
                                                class="h-6 w-6 text-green-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4.5v15m7.5-7.5h-15"
                                                />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-xl font-bold text-gray-900">Add Staff to Organization</h3>
                                            <button
                                                type="button"
                                                @click="$wire.closeAddStaffModal()"
                                                class="rounded-lg p-2 text-gray-400 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-600"
                                            >
                                                <svg
                                                    class="h-5 w-5"
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

                                        <div class="mt-4">
                                            <!-- Form untuk Add Staff -->
                                            <form wire:submit.prevent="saveStaff">
                                                <div class="grid grid-cols-1 gap-5">
                                                    <!-- Company -->
                                                    <div>
                                                        <label
                                                            for="company_id"
                                                            class="mb-2 block text-sm font-medium text-gray-700"
                                                        >
                                                            Company
                                                        </label>
                                                        <div class="relative">
                                                            <select
                                                                wire:model="form.company_id"
                                                                id="company_id"
                                                                class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                            >
                                                                <option value="">Select Company</option>
                                                                @foreach ($companies as $company)
                                                                    <option value="{{ $company->id }}">
                                                                        {{ $company->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700"
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
                                                                        d="M19 9l-7 7-7-7"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        @error('form.company_id')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Superior Position - HIDDEN & DISPLAY ONLY -->
                                                    <input type="hidden" wire:model="form.superior_id">
                                                    @if($selectedSuperiorName)
                                                    <div class="rounded-lg bg-blue-50 p-4 border border-blue-200">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0">
                                                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                </svg>
                                                            </div>
                                                            <div class="ml-3">
                                                                <h3 class="text-sm font-medium text-blue-800">Reporting Structure</h3>
                                                                <div class="mt-1 text-sm text-blue-700">
                                                                    <p>This staff will report to: <strong>{{ $selectedSuperiorName }}</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <!-- Position -->
                                                    <div>
                                                        <label
                                                            for="position_id"
                                                            class="mb-2 block text-sm font-medium text-gray-700"
                                                        >
                                                            Staff Position
                                                        </label>
                                                        <div class="relative">
                                                            <select
                                                                wire:model="form.position_id"
                                                                id="position_id"
                                                                class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                            >
                                                                <option value="">Select Position</option>
                                                                @foreach ($positions as $position)
                                                                    <option value="{{ $position->id }}">
                                                                        {{ $position->name }} -
                                                                        {{ $position->getWorkUnitAttribute() ?? 'No Department' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700"
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
                                                                        d="M19 9l-7 7-7-7"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        @error('form.position_id')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Relation Type -->
                                                    <div>
                                                        <label
                                                            for="relation_type"
                                                            class="mb-2 block text-sm font-medium text-gray-700"
                                                        >
                                                            Relation Type
                                                        </label>
                                                        <div class="relative">
                                                            <select
                                                                wire:model="form.relation_type"
                                                                id="relation_type"
                                                                class="focus:ring-opacity-20 w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm transition-all duration-200 focus:border-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:outline-none"
                                                            >
                                                                <option value="direct">Direct Report</option>
                                                                <option value="indirect">Indirect Report</option>
                                                                <option value="dotted_line">Dotted Line</option>
                                                            </select>
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700"
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
                                                                        d="M19 9l-7 7-7-7"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        @error('form.relation_type')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6">
                                                    <button
                                                        type="button"
                                                        @click="$wire.closeAddStaffModal()"
                                                        wire:loading.attr="disabled"
                                                        class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button
                                                        type="submit"
                                                        wire:loading.attr="disabled"
                                                        class="w-fit transform rounded-xl bg-gradient-to-r from-[#721d21] to-[#8b252a] px-6 py-2.5 font-medium text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-[#5a1619] hover:to-[#721d21] focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none active:scale-[0.98] disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                                    >
                                                        <!-- Normal State -->
                                                        <span
                                                            wire:loading.remove
                                                            class="inline-flex items-center gap-2"
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
                                                                    d="M12 4.5v15m7.5-7.5h-15"
                                                                />
                                                            </svg>
                                                            Add Staff
                                                        </span>

                                                        <!-- Loading State -->
                                                        <span wire:loading class="inline-flex items-center gap-2">
                                                            Processing...
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth transitions for all elements */
        * {
            transition-property: color, background-color, border-color, transform, opacity;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .org-chart-scroll-area {
            overflow: auto;
            position: relative;
            max-height: 80vh;
            cursor: grab;
        }

        .org-chart-scroll-area:active {
            cursor: grabbing;
        }

        .org-chart-wrapper {
            min-height: 600px;
            transition: transform 0.3s ease-in-out;
            transform-origin: top center;
        }

        .org-node {
            display: inline-block;
        }

        /* FIXED: Higher z-index for node cards and popovers */
        .node-card-wrapper > div {
            position: relative;
            /* z-index: 1;
        }

        /* FIXED: Ensure popover appears above all nodes */
        .relative[x-data] {
            position: relative;
            /* z-index: 2; */
        }

        .connector-bottom,
        .connector-top,
        .horizontal-line {
            background-color: #a5a7ac !important;
        }

        /* Modal specific transitions */
        .modal-backdrop {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-content {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Hide elements with x-cloak until Alpine initializes */
        [x-cloak] {
            display: none !important;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for modal */
        .overflow-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Ensure consistent node sizes */
        .node-card-wrapper {
            min-width: 320px;
        }

        /* Backdrop blur effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Button hover effects */
        .btn-hover {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow:
                0 10px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 1024px) {
            .org-chart-wrapper {
                transform-origin: top left !important;
            }

            /* Mobile optimizations for modal */
            .modal-mobile {
                padding: 1rem;
            }
        }

        /* Loading animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Focus states */
        .focus-smooth:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(114, 29, 33, 0.1);
        }
    </style>

    <script>
        // ======== DRAG TO PAN ========
        const scrollArea = document.getElementById('orgChartScrollArea');
        let isDragging = false;
        let startX, startY, scrollLeft, scrollTop;

        if (scrollArea) {
            scrollArea.addEventListener('mousedown', (e) => {
                isDragging = true;
                scrollArea.classList.add('active');
                startX = e.pageX - scrollArea.offsetLeft;
                startY = e.pageY - scrollArea.offsetTop;
                scrollLeft = scrollArea.scrollLeft;
                scrollTop = scrollArea.scrollTop;
            });

            scrollArea.addEventListener('mouseleave', () => {
                isDragging = false;
                scrollArea.classList.remove('active');
            });

            scrollArea.addEventListener('mouseup', () => {
                isDragging = false;
                scrollArea.classList.remove('active');
            });

            scrollArea.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - scrollArea.offsetLeft;
                const y = e.pageY - scrollArea.offsetTop;
                const walkX = (x - startX) * 1;
                const walkY = (y - startY) * 1;
                scrollArea.scrollLeft = scrollLeft - walkX;
                scrollArea.scrollTop = scrollTop - walkY;
            });
        }

        // ======== ZOOM VIA MOUSE SCROLL ========
        const wrapper = document.getElementById('orgChartWrapper');
        const zoomValueDisplay = document.getElementById('zoomValue');
        let zoomLevel = {{ $zoom }};

        if (scrollArea && wrapper) {
            scrollArea.addEventListener('wheel', (e) => {
                if (!e.ctrlKey) return;

                e.preventDefault();
                const delta = e.deltaY > 0 ? -10 : 10;
                zoomLevel = Math.min(Math.max(50, zoomLevel + delta), 200);
                wrapper.style.transform = `scale(${zoomLevel / 100})`;
                if (zoomValueDisplay) zoomValueDisplay.textContent = zoomLevel;

                // Update chart lines setelah zoom
                setTimeout(updateOrgChartLines, 10);
            });
        }

        // ======== IMPROVED ORG CHART LINES ========
        function updateOrgChartLines() {
            const horizontalLines = document.querySelectorAll('.horizontal-line');
            if (!wrapper || horizontalLines.length === 0) return;

            const transform = wrapper.style.transform;
            const scaleMatch = transform.match(/scale\(([\d.]+)\)/);
            const scale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;

            horizontalLines.forEach((line) => {
                const container = line.parentElement;
                const childWrappers = container.querySelectorAll('.child-wrapper');

                if (childWrappers.length > 1) {
                    const firstChild = childWrappers[0];
                    const lastChild = childWrappers[childWrappers.length - 1];

                    // Get positions relative to container
                    const firstRect = firstChild.getBoundingClientRect();
                    const lastRect = lastChild.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();

                    // Calculate centers relative to container
                    const firstCenter = (firstRect.left - containerRect.left) / scale + firstRect.width / scale / 2;
                    const lastCenter = (lastRect.left - containerRect.left) / scale + lastRect.width / scale / 2;

                    // Update line position and width
                    line.style.left = firstCenter + 'px';
                    line.style.width = (lastCenter - firstCenter) + 'px';

                    // Make sure line is visible
                    line.style.opacity = '1';
                    line.style.display = 'block';
                }
            });
        }

        // Debounce function untuk optimasi performance
        const debounce = (func, wait = 10) => {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), wait);
            };
        };

        const debouncedUpdate = debounce(updateOrgChartLines, 10);

        // IMPROVED: Better initialization and event handling
        document.addEventListener('DOMContentLoaded', () => {
            // Multiple attempts to ensure lines are drawn
            setTimeout(updateOrgChartLines, 50);
            setTimeout(updateOrgChartLines, 150);
            setTimeout(updateOrgChartLines, 300);
            setTimeout(updateOrgChartLines, 500);

            // Force redraw after fonts and images load
            window.addEventListener('load', () => {
                setTimeout(updateOrgChartLines, 100);
            });
        });

        window.addEventListener('resize', debouncedUpdate);

        // IMPROVED: Livewire hooks with better timing
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:initialized', () => {
                Livewire.hook('message.received', () => {
                    // Schedule line update after DOM updates
                    requestAnimationFrame(() => {
                        setTimeout(updateOrgChartLines, 50);
                    });
                });

                Livewire.hook('commit', ({ component, commit }) => {
                    if (component.name === 'organization-chart') {
                        requestAnimationFrame(() => {
                            setTimeout(updateOrgChartLines, 100);
                        });
                    }
                });

                Livewire.hook('morph.updated', () => {
                    requestAnimationFrame(() => {
                        setTimeout(updateOrgChartLines, 150);
                    });
                });
            });
        }

        // IMPROVED: Observer dengan lebih banyak event types
        if (wrapper) {
            const observer = new MutationObserver((mutations) => {
                let shouldUpdate = false;
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'style' ||
                        mutation.type === 'childList' ||
                        mutation.type === 'subtree') {
                        shouldUpdate = true;
                    }
                });

                if (shouldUpdate) {
                    requestAnimationFrame(() => {
                        setTimeout(updateOrgChartLines, 50);
                    });
                }
            });

            observer.observe(wrapper, {
                attributes: true,
                attributeFilter: ['style'],
                childList: true,
                subtree: true
            });

            // Update lines after any transitions
            wrapper.addEventListener('transitionend', (e) => {
                if (e.propertyName === 'transform') {
                    requestAnimationFrame(() => {
                        setTimeout(updateOrgChartLines, 10);
                    });
                }
            });
        }

        // IMPROVED: Manual trigger untuk update lines
        function forceUpdateOrgChartLines() {
            if (typeof updateOrgChartLines === 'function') {
                requestAnimationFrame(() => {
                    updateOrgChartLines();
                    setTimeout(updateOrgChartLines, 100);
                });
            }
        }

        // Expose function globally untuk debugging
        window.forceUpdateOrgChartLines = forceUpdateOrgChartLines;
    </script>
</div>
