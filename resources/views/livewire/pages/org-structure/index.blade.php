<div>
    <div class="min-h-screen bg-gray-50 p-4" wire:key="org-chart-main">
        <!-- Header Controls -->
        <div class="mb-6 rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Organization Chart</h1>
                    <p class="mt-1 text-gray-600">Struktur organisasi interaktif dengan fitur zoom dan toggle</p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Zoom Controls -->
                    <div class="flex items-center gap-2 rounded-lg bg-gray-100 p-2">
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
                            <span class="text-lg font-semibold text-gray-800">{{ $zoom }}%</span>
                            <span class="text-xs text-gray-500">Zoom</span>
                        </div>

                        <button
                            wire:click="zoomIn"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
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

                    <button
                        wire:click="resetZoom"
                        class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition-colors hover:bg-blue-700"
                    >
                        Reset View
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-blue-50 p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ count($nodes) }}</div>
                    <div class="text-sm text-blue-800">Total Karyawan</div>
                </div>
                <div class="rounded-lg bg-green-50 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ collect($nodes)->where('visible', true)->count() }}
                    </div>
                    <div class="text-sm text-green-800">Terlihat</div>
                </div>
                <div class="rounded-lg bg-purple-50 p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $zoom }}%</div>
                    <div class="text-sm text-purple-800">Level Zoom</div>
                </div>
                <div class="rounded-lg bg-orange-50 p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">
                        {{ collect($nodes)->where('visible', false)->count() }}
                    </div>
                    <div class="text-sm text-orange-800">Tersembunyi</div>
                </div>
            </div>
        </div>

        <!-- Org Chart Container -->
        <div class="overflow-auto rounded-xl border bg-white p-8 shadow-sm">
            <div
                id="orgChartWrapper"
                class="org-chart-wrapper min-w-max"
                style="transform: scale({{ $zoom / 100 }}); transform-origin: top center"
            >
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
                            0 => 'w-80',
                            1 => 'w-64',
                            2 => 'w-56',
                            default => 'w-48',
                        };

                        $textSize = match ($level) {
                            0 => 'text-xl',
                            1 => 'text-lg',
                            default => 'text-base',
                        };

                        $subtextSize = match ($level) {
                            0 => 'text-base',
                            1 => 'text-sm',
                            default => 'text-xs',
                        };

                        $nodeHtmlId = 'node-' . $nodeId;
                        $childrenContainerId = 'children-' . $nodeId;

                        $output = '<div class="org-node">';

                        // Main container
                        $output .= '<div class="flex flex-col items-center">';

                        // Node Card
                        $output .= '<div class="node-card-wrapper relative mb-4" id="' . $nodeHtmlId . '">';
                        $output .= '<div wire:click="toggleNode(' . $node['id'] . ')" ';
                        $output .= 'class="' . $node['color'] . ' ' . $cardWidthClass . ' cursor-pointer rounded-xl p-4 text-white shadow-lg transition-all duration-200 ">';
                        $output .= '<div class="text-center">';
                        $output .= '<div class="' . $textSize . ' font-bold">' . e($node['name']) . '</div>';
                        $output .= '<div class="mt-1 ' . $subtextSize . ' font-medium opacity-90">' . e($node['title']) . '</div>';
                        $output .= '<div class="' . ($level <= 1 ? 'text-sm' : 'text-xs') . ' opacity-80">' . e($node['department']) . '</div>';

                        if ($this->hasChildren($nodeId)) {
                            $output .= '<div class="mt-2 text-xs opacity-90">';
                            $output .= $isCollapsed ? '► Tampilkan Tim (' . $totalChildren . ')' : '▼ Sembunyikan Tim';
                            $output .= '</div>';
                        }

                        $output .= '</div></div>';

                        // Bottom connector
                        if ($hasVisibleChildren) {
                            $output .= '<div class="connector-bottom absolute -bottom-4 left-1/2 h-4 w-0.5 -translate-x-1/2 bg-gray-400"></div>';
                        }

                        $output .= '</div>';

                        // Children Container
                        if ($hasVisibleChildren) {
                            $childCount = count($children);

                            $output .= '<div class="children-container relative flex gap-8" id="' . $childrenContainerId . '">';

                            // Horizontal connector (will be positioned by JS)
                            if ($childCount > 1) {
                                $output .= '<div class="horizontal-line absolute top-0 h-0.5 bg-gray-400" data-parent="' . $nodeId . '"></div>';
                            }

                            // Render children
                            foreach ($children as $index => $child) {
                                $childHtmlId = 'node-' . $child['id'];
                                $output .= '<div class="child-wrapper relative flex flex-col items-center" data-child-id="' . $child['id'] . '">';

                                // Top connector
                                $output .= '<div class="connector-top h-8 w-0.5 bg-gray-400"></div>';

                                // Recursive child
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
    </div>

    <style>
        .org-chart-wrapper {
            min-height: 600px;
            transition: transform 0.3s ease-in-out;
        }

        .org-node {
            display: inline-block;
        }

        /* Card hover effects */
        .node-card-wrapper > div {
            position: relative;
            z-index: 10;
        }

        /* Connector colors */
        .connector-bottom,
        .connector-top,
        .horizontal-line {
            background-color: #9ca3af !important;
        }

        /* Custom scrollbar */
        .overflow-auto::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 5px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media (max-width: 1024px) {
            .org-chart-wrapper {
                transform-origin: top left !important;
            }
        }
    </style>

    <script>
        function updateOrgChartLines() {
            // Get the wrapper to account for zoom
            const wrapper = document.getElementById('orgChartWrapper');
            if (!wrapper) return;

            // Get current zoom level from transform scale
            const transform = wrapper.style.transform;
            const scaleMatch = transform.match(/scale\(([\d.]+)\)/);
            const scale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;

            // Get all horizontal lines
            const horizontalLines = document.querySelectorAll('.horizontal-line');

            horizontalLines.forEach((line) => {
                const container = line.parentElement;
                const childWrappers = container.querySelectorAll('.child-wrapper');

                if (childWrappers.length > 1) {
                    // Get first and last child positions
                    const firstChild = childWrappers[0];
                    const lastChild = childWrappers[childWrappers.length - 1];

                    // Get their positions relative to container (using offsetLeft for accuracy)
                    const firstChildRect = firstChild.getBoundingClientRect();
                    const lastChildRect = lastChild.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();

                    // Calculate center positions accounting for scale
                    const firstCenter =
                        (firstChildRect.left - containerRect.left) / scale + firstChildRect.width / scale / 2;
                    const lastCenter =
                        (lastChildRect.left - containerRect.left) / scale + lastChildRect.width / scale / 2;

                    // Set line position and width
                    line.style.left = firstCenter + 'px';
                    line.style.width = lastCenter - firstCenter + 'px';
                }
            });
        }

        // Debounce function to prevent too many calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Create debounced version for resize only
        const debouncedUpdate = debounce(updateOrgChartLines, 10);

        // Run on page load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(updateOrgChartLines, 100);
        });

        // Run after Livewire updates
        document.addEventListener('livewire:initialized', () => {
            // Hook into message received (when server responds)
            Livewire.hook('message.received', (message, component) => {
                // Use requestAnimationFrame for smooth updates
                requestAnimationFrame(() => {
                    setTimeout(updateOrgChartLines, 10);
                });
            });

            // Hook into commit (before DOM updates)
            Livewire.hook('commit', ({ component, commit, respond }) => {
                requestAnimationFrame(() => {
                    setTimeout(updateOrgChartLines, 10);
                });
            });

            // Hook into morph updated (after DOM updates)
            Livewire.hook('morph.updated', ({ el, component }) => {
                requestAnimationFrame(() => {
                    setTimeout(updateOrgChartLines, 10);
                });
            });
        });

        // Run on window resize
        window.addEventListener('resize', debouncedUpdate);

        // Watch for style changes on wrapper (zoom changes) - IMMEDIATE UPDATE
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    // Use requestAnimationFrame for immediate smooth update
                    requestAnimationFrame(() => {
                        updateOrgChartLines();
                        // Double check after transition
                        setTimeout(updateOrgChartLines, 50);
                        setTimeout(updateOrgChartLines, 150);
                        setTimeout(updateOrgChartLines, 350);
                    });
                }
            });
        });

        // Start observing when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('orgChartWrapper');
            if (wrapper) {
                observer.observe(wrapper, {
                    attributes: true,
                    attributeFilter: ['style'],
                });
            }
        });

        // Watch for transitionend on wrapper
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('orgChartWrapper');
            if (wrapper) {
                wrapper.addEventListener('transitionend', (e) => {
                    if (e.propertyName === 'transform') {
                        updateOrgChartLines();
                    }
                });
            }
        });

        // Initial calls with multiple delays to ensure proper rendering
        setTimeout(updateOrgChartLines, 50);
        setTimeout(updateOrgChartLines, 150);
        setTimeout(updateOrgChartLines, 300);
    </script>
</div>
