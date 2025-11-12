<div class="org-chart-container">
    <div wire:key="org-chart-main">
        <!-- Header Controls -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-sm"
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
            <a
                href="{{ route('organization-structure.table') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gradient-to-r px-4 py-2.5 text-sm font-medium shadow-sm transition-all hover:shadow-md focus:ring-2 focus:ring-[#721d21] focus:ring-offset-2 focus:outline-none"
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

        <!-- Org Chart Container -->
        <div class="relative rounded-xl border bg-white shadow-sm">
            <!-- Scrollable Area -->
            <div id="orgChartScrollArea" class="org-chart-scroll-area relative overflow-auto p-8">
                <div
                    id="orgChartWrapper"
                    class="org-chart-wrapper min-w-max"
                    style="transform: scale({{ $zoom / 100 }}); transform-origin: top center"
                >
                    <!-- Render Nodes via Blade -->
                    @php
                        $renderNode = null;
                        $renderNode = function ($nodeId, $level = 0) use (&$renderNode) {
                            $node = $this->getNode($nodeId);
                            if (! $node || ! $node['visible']) {
                                return '';
                            }

                            $children = $this->getChildNodes($nodeId);
                            $hasVisibleChildren = count($children) > 0;
                            $isCollapsed = $node['collapsed'];
                            $totalChildren = collect($this->nodes)
                                ->where('parent_id', $nodeId)
                                ->count();

                            $cardWidthClass = match ($level) {
                                0 => 'w-96',
                                1 => 'w-80',
                                2 => 'w-72',
                                default => 'w-64',
                            };

                            $textSize = match ($level) {
                                0 => 'text-lg',
                                1 => 'text-base',
                                default => 'text-sm',
                            };

                            $subtextSize = match ($level) {
                                0 => 'text-sm',
                                1 => 'text-xs',
                                default => 'text-xs',
                            };

                            $nodeHtmlId = 'node-' . $nodeId;
                            $childrenContainerId = 'children-' . $nodeId;

                            $output = '<div class="org-node">';
                            $output .= '<div class="flex flex-col items-center">';

                            // Node Card
                            $output .= '<div class="node-card-wrapper relative mb-4" id="' . $nodeHtmlId . '">';
                            $output .= '<div class="' . $cardWidthClass . ' flex cursor-default items-center gap-4 rounded-xl border border-gray-300 bg-white p-4 shadow-lg transition-all duration-200 hover:shadow-xl relative">';

                            // === FOTO PROFIL ===
                            $photoUrl = $node['photo'] ? asset('storage/' . $node['photo']) : null;
                            $output .= '<div class="flex-shrink-0">';
                            if ($photoUrl) {
                                $output .= '<img src="' . e($photoUrl) . '" alt="Profile" class="h-20 w-14 rounded-lg object-cover bg-gray-100">';
                            } else {
                                $output .= '<div class="flex h-20 w-14 items-center justify-center rounded-lg bg-gray-100 text-gray-400">';
                                $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">';
                                $output .= '<path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />';
                                $output .= '</svg>';
                                $output .= '</div>';
                            }
                            $output .= '</div>';

                            // === INFORMASI NODE ===
                            $output .= '<div class="flex flex-col text-left">';
                            $output .= '<div class="' . $textSize . ' font-bold text-gray-800">' . e($node['name']) . '</div>';
                            $output .= '<div class="mt-0.5 ' . $subtextSize . ' font-medium text-gray-600">' . e($node['title']) . '</div>';
                            $output .= '<div class="text-xs text-gray-500">' . e($node['department']) . '</div>';

                            if ($this->hasChildren($nodeId)) {
                                $output .= '<div wire:click="toggleNode(' . $node['id'] . ')" class="mt-2 cursor-pointer text-xs font-medium text-[#721d21] p-1 px-2 rounded-full hover:bg-[#721d2110]">';
                                $output .= $isCollapsed ? '► Show Team (' . $totalChildren . ')' : '▼ Hide Team';
                                $output .= '</div>';
                            }
                            $output .= '</div>';

                            // === URL ===
                            $url = route('dashboard');
                            $output .= '<a href="' . $url . '" target="_blank" class="absolute top-2 right-2 flex h-8 w-8 items-center justify-center rounded-md  text-gray-500 text-xs hover:bg-gray-200 transition-colors">';
                            $output .= '<button title="Candidate Comparison" class="w-full flex items-center justify-center">';
                            $output .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">';
                            $output .= '<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />';
                            $output .= '</svg>';
                            $output .= '</button>';
                            $output .= '</a>';

                            $output .= '</div>';

                            if ($hasVisibleChildren) {
                                $output .= '<div class="connector-bottom absolute -bottom-4 left-1/2 h-4 w-0.5 -translate-x-1/2 bg-gray-400"></div>';
                            }

                            $output .= '</div>';

                            if ($hasVisibleChildren) {
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
                        class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
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
                        class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                        {{ $zoom >= 200 ? 'disabled' : '' }}
                    >
                        <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .org-chart-scroll-area {
            overflow: auto;
            position: relative;
            max-height: 80vh;
            cursor: grab; /* Default cursor for drag */
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
            z-index: 10;
        }

        .connector-bottom,
        .connector-top,
        .horizontal-line {
            background-color: #a5a7ac !important;
        }

        .overflow-auto::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 5px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 5px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        @media (max-width: 1024px) {
            .org-chart-wrapper {
                transform-origin: top left !important;
            }
        }
    </style>

    <script>
        const wrapper = document.getElementById('orgChartWrapper');
        const scrollArea = document.getElementById('orgChartScrollArea');
        const zoomValueDisplay = document.getElementById('zoomValue');
        let isDragging = false;
        let startX, startY, scrollLeft, scrollTop;
        let zoomLevel = {{ $zoom }};

        // ======== DRAG TO PAN ========
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
            const walkX = (x - startX) * 1; // scroll speed multiplier
            const walkY = (y - startY) * 1;
            scrollArea.scrollLeft = scrollLeft - walkX;
            scrollArea.scrollTop = scrollTop - walkY;
        });

        // ======== ZOOM VIA MOUSE SCROLL ========
        scrollArea.addEventListener('wheel', (e) => {
            // Zoom hanya jika Ctrl ditekan
            if (!e.ctrlKey) return;

            e.preventDefault(); // cegah scroll default
            const delta = e.deltaY > 0 ? -10 : 10;
            zoomLevel = Math.min(Math.max(50, zoomLevel + delta), 200); // batas zoom 50% - 200%
            wrapper.style.transform = `scale(${zoomLevel / 100})`;
            if (zoomValueDisplay) zoomValueDisplay.textContent = zoomLevel;
        });

        // ======== UPDATE ORG CHART LINES ========
        function updateOrgChartLines() {
            const horizontalLines = document.querySelectorAll('.horizontal-line');
            const transform = wrapper.style.transform;
            const scaleMatch = transform.match(/scale\(([\d.]+)\)/);
            const scale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;

            horizontalLines.forEach((line) => {
                const container = line.parentElement;
                const childWrappers = container.querySelectorAll('.child-wrapper');

                if (childWrappers.length > 1) {
                    const firstChild = childWrappers[0];
                    const lastChild = childWrappers[childWrappers.length - 1];
                    const firstRect = firstChild.getBoundingClientRect();
                    const lastRect = lastChild.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();

                    const firstCenter = (firstRect.left - containerRect.left) / scale + firstRect.width / scale / 2;
                    const lastCenter = (lastRect.left - containerRect.left) / scale + lastRect.width / scale / 2;

                    line.style.left = firstCenter + 'px';
                    line.style.width = lastCenter - firstCenter + 'px';
                }
            });
        }

        const debouncedUpdate = (func, wait = 10) => {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), wait);
            };
        };

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(updateOrgChartLines, 100);
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('message.received', () => requestAnimationFrame(() => setTimeout(updateOrgChartLines, 10)));
            Livewire.hook('commit', () => requestAnimationFrame(() => setTimeout(updateOrgChartLines, 10)));
            Livewire.hook('morph.updated', () => requestAnimationFrame(() => setTimeout(updateOrgChartLines, 10)));
        });

        window.addEventListener('resize', debouncedUpdate(updateOrgChartLines));

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'style') {
                    requestAnimationFrame(() => {
                        updateOrgChartLines();
                        setTimeout(updateOrgChartLines, 1);
                        setTimeout(updateOrgChartLines, 150);
                        setTimeout(updateOrgChartLines, 350);
                    });
                }
            });
        });

        if (wrapper) {
            observer.observe(wrapper, { attributes: true, attributeFilter: ['style'] });
            wrapper.addEventListener('transitionend', (e) => {
                if (e.propertyName === 'transform') updateOrgChartLines();
            });
        }

        setTimeout(updateOrgChartLines, 50);
        setTimeout(updateOrgChartLines, 150);
        setTimeout(updateOrgChartLines, 300);
    </script>
</div>
