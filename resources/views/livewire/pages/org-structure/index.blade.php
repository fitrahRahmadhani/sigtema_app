<div>
    <div class="org-chart-container">
        <div wire:key="org-chart-main">
            <x-ui.modals.delete-confirmation />
                <livewire:components.modals.add-staff-modal />
                <livewire:components.modals.unassign-employee-modal />
                <livewire:components.modals.assign-employee-modal />
            <!-- Header Controls -->
            <div class="mb-8 flex flex-col gap-4">
                <!-- Header Section -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Title and Icon -->
                    <div class="flex items-center gap-4">
                        <div class="color-gradient flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#721d21] to-[#8b252a] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="size-7 text-white" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 1-1 0v-1z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl lg:text-3xl">Organization Structure</h1>
                            @if($isSuperadmin && !$hasSelectedCompany)
                                <p class="mt-1 text-xs text-gray-500 sm:text-sm">Please select a company to view organization structure</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                                    @if($isSuperadmin)
                                        Viewing: <strong class="text-gray-700">{{ $companies->firstWhere('id', $selectedCompanyId)->name ?? 'Unknown Company' }}</strong>
                                    @else
                                        Managing your company organization structure
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Controls Section -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Company Selector (Superadmin Only) -->
                    @if($isSuperadmin)
                        <div class="flex-1 sm:max-w-md">
                            @if($hasSelectedCompany)
                                <!-- Current Company Badge -->
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-300 bg-white px-4 py-2.5 shadow-sm">
                                    <div class="flex min-w-0 flex-1 items-center gap-2">
                                        <svg class="h-4 w-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="truncate text-sm font-medium text-gray-700">
                                            {{ $companies->firstWhere('id', $selectedCompanyId)->name ?? 'Unknown Company' }}
                                        </span>
                                    </div>
                                    <button
                                        wire:click="changeCompany"
                                        class="flex flex-shrink-0 items-center gap-1 rounded-lg bg-red-50 px-2 py-1 text-xs text-red-500 transition-colors hover:bg-red-100 hover:text-red-700"
                                        title="Change Company"
                                    >
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        <span class="hidden sm:inline">Change</span>
                                    </button>
                                </div>
                            @else
                                <!-- Company Dropdown -->
                                <div class="relative">
                                    <select
                                        wire:model="selectedCompanyId"
                                        wire:change="selectCompany($event.target.value)"
                                        class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm shadow-sm transition-all duration-200 hover:border-gray-400 focus:border-[#721d21] focus:outline-none focus:ring-2 focus:ring-[#721d21]"
                                    >
                                        <option value="" class="text-gray-500">Select a Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" class="text-gray-900">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Spacer for non-superadmin -->
                        <div class="flex-1"></div>
                    @endif

                    <!-- Table View Button -->
                    @if($hasSelectedCompany)
                        <div class="flex justify-end sm:justify-start">
                            <a href="{{ route('organization-structure.table') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 sm:w-auto"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                                </svg>
                                <span>Table View</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Org Chart Container -->
            @if($hasSelectedCompany)
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
                                    if (!$isVacant) {
                                        $profileUrl = route('employees.show', $node['slug']);
                                        $output .= '<a href="' . $profileUrl . '" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                        $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                        $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />';
                                        $output .= '</svg>';
                                        $output .= '<span class="text-sm font-medium">Employee Profile</span>';
                                        $output .= '</a>';
                                    }

                                    if($isVacant) {
                                        // Assign employee
                                        $output .= '<button wire:click="openAssignEmployeeModal(' . $node['position_id'] . ')" class="w-full flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                        $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                        $output .= '<path strokeLinecap="round" strokeLinejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />';
                                        $output .= '</svg>';
                                        $output .= '<span class="text-sm font-medium">Assign Employee</span>';
                                        $output .= '</button>';
                                    }

                                    // Unassign employee
                                    $output .= '<button wire:click="openUnassignEmployeeModal(' . $node['position_id'] . ')" class="w-full flex items-center gap-3 px-3 py-2 rounded-md ' . ($isVacant ? 'text-red-700 hover:bg-red-100' : 'text-gray-700 hover:bg-gray-100') . ' transition-colors">';
                                    $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">';
                                    $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />';
                                    $output .= '</svg>';
                                    $output .= '<span class="text-sm font-medium">Unassign Employee</span>';
                                    $output .= '</button>';

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
                                    $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />';
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
            @else
                <!-- Empty State untuk Superadmin yang belum pilih company -->
                <div class="relative rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex flex-col items-center justify-center p-12 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Company Selected</h3>
                        <p class="mt-2 text-sm text-gray-500">Please select a company from the dropdown above to view the organization structure.</p>
                    </div>
                </div>
            @endif
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

        .node-card-wrapper > div {
            position: relative;
        }

        .relative[x-data] {
            position: relative;
        }

        .connector-bottom,
        .connector-top,
        .horizontal-line {
            background-color: #a5a7ac !important;
        }

        /* CRITICAL: Force horizontal lines to be visible */
        .horizontal-line {
            position: absolute !important;
            top: 0 !important;
            height: 2px !important;
            display: block !important;
            opacity: 1 !important;
            z-index: 1 !important;
            background-color: #a5a7ac !important;
        }

        .children-container {
            position: relative;
        }

        .child-wrapper {
            position: relative;
        }

        .modal-backdrop {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-content {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

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

        .node-card-wrapper {
            min-width: 320px;
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

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

            .modal-mobile {
                padding: 1rem;
            }
        }

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

        .focus-smooth:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(114, 29, 33, 0.1);
        }
    </style>

    <script>
        // ======== DRAG TO PAN ========
        const scrollArea = document.getElementById('orgChartScrollArea');
        const wrapper = document.getElementById('orgChartWrapper');
        const zoomValueDisplay = document.getElementById('zoomValue');
        let isDragging = false;
        let startX, startY, scrollLeft, scrollTop;
        let zoomLevel = {{ $zoom }};

        if (scrollArea) {
            scrollArea.addEventListener('mousedown', (e) => {
                isDragging = true;
                startX = e.pageX - scrollArea.offsetLeft;
                startY = e.pageY - scrollArea.offsetTop;
                scrollLeft = scrollArea.scrollLeft;
                scrollTop = scrollArea.scrollTop;
            });

            ['mouseleave', 'mouseup'].forEach(event => {
                scrollArea.addEventListener(event, () => {
                    isDragging = false;
                });
            });

            scrollArea.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - scrollArea.offsetLeft;
                const y = e.pageY - scrollArea.offsetTop;
                scrollArea.scrollLeft = scrollLeft - (x - startX);
                scrollArea.scrollTop = scrollTop - (y - startY);
            });

            scrollArea.addEventListener('wheel', (e) => {
                if (!e.ctrlKey) return;
                e.preventDefault();

                const delta = e.deltaY > 0 ? -10 : 10;
                zoomLevel = Math.min(Math.max(50, zoomLevel + delta), 200);
                wrapper.style.transform = `scale(${zoomLevel / 100})`;
                if (zoomValueDisplay) zoomValueDisplay.textContent = zoomLevel;

                updateOrgChartLines();
            }, { passive: false });
        }

        // ======== UPDATE LINES FUNCTION ========
        function updateOrgChartLines() {
            const horizontalLines = document.querySelectorAll('.horizontal-line');
            if (!horizontalLines.length) {
                return;
            }

            let successCount = 0;

            horizontalLines.forEach((line, index) => {
                const container = line.parentElement;
                if (!container) {
                    return;
                }

                const childWrappers = container.querySelectorAll('.child-wrapper');
                if (childWrappers.length <= 1) {
                    line.style.display = 'none';
                    return;
                }

                const firstChild = childWrappers[0];
                const lastChild = childWrappers[childWrappers.length - 1];

                // Force reflow
                void firstChild.offsetWidth;

                const firstLeft = firstChild.offsetLeft;
                const firstWidth = firstChild.offsetWidth;
                const lastLeft = lastChild.offsetLeft;
                const lastWidth = lastChild.offsetWidth;
                const firstCenter = firstLeft + (firstWidth / 2);
                const lastCenter = lastLeft + (lastWidth / 2);

                if (firstCenter > 0 && lastCenter > firstCenter) {
                    line.style.left = `${firstCenter}px`;
                    line.style.width = `${lastCenter - firstCenter}px`;
                    line.style.display = 'block';
                    line.style.opacity = '1';
                    successCount++;
                }
            });
        }

        // ======== LIVEWIRE HOOKS (CRITICAL!) ========
        document.addEventListener('livewire:initialized', () => {
            // Hook ke semua Livewire events
            Livewire.hook('morph.updated', ({ el, component }) => {
                setTimeout(() => {
                    updateOrgChartLines();
                }, 100);
            });

            Livewire.hook('message.received', ({ component, message }) => {
                setTimeout(() => {
                    updateOrgChartLines();
                }, 150);
            });

            Livewire.hook('commit', ({ component, commit, respond }) => {
                setTimeout(() => {
                    updateOrgChartLines();
                }, 200);
            });
        });

        // ======== MUTATION OBSERVER ========
        if (wrapper) {
            const observer = new MutationObserver((mutations) => {
                // Check if horizontal-line was added
                const hasLineAdded = Array.from(mutations).some(mutation => {
                    return Array.from(mutation.addedNodes).some(node => {
                        return node.classList && node.classList.contains('horizontal-line') ||
                            (node.querySelectorAll && node.querySelectorAll('.horizontal-line').length > 0);
                    });
                });

                if (hasLineAdded) {
                    setTimeout(() => {
                        updateOrgChartLines();
                    }, 50);
                    setTimeout(() => {
                        updateOrgChartLines();
                    }, 200);
                }
            });

            observer.observe(wrapper, {
                childList: true,
                subtree: true
            });
        }

        // ======== RESIZE ========
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(updateOrgChartLines, 100);
        });

        // ======== EXPOSE GLOBAL ========
        window.forceUpdateOrgChartLines = updateOrgChartLines;

        // ======== INITIAL CALL ========
        setTimeout(() => {
            updateOrgChartLines();
        }, 500);
    </script>
</div>
