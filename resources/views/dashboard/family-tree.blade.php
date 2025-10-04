<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pohon Keluarga</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">Pohon Keluarga gabungan berdasarkan NIK (disatukan antar keluarga yang memiliki NIK sama).</p>

                <div id="family-tree-container" style="height:70vh; min-height:500px; width:100%; overflow:hidden;">
                    <!-- D3 tree will mount here -->
                </div>

                <script>
                    // Tree JSON passed from server
                    const treeData = {!! $treeJson !!};

                    document.addEventListener('DOMContentLoaded', function () {
                        const container = document.getElementById('family-tree-container');
                        if (!treeData || typeof treeData !== 'object') {
                            console.warn('Tree data malformed', treeData);
                        }

                        // Minimal D3 tree with spouse support (side-by-side labels)
                        if (window.d3) {
                            const width = container.clientWidth || 1000;
                            const height = container.clientHeight || 600;

                            const svg = d3.select(container)
                                .append('svg')
                                .attr('width', width)
                                .attr('height', height)
                                .call(d3.zoom().scaleExtent([0.3, 2]).on('zoom', (event) => {
                                    g.attr('transform', event.transform);
                                }));

                            const g = svg.append('g').attr('transform', 'translate(40,20)');

                            const root = d3.hierarchy(treeData, d => d.children || []);
                            root.x0 = height / 2; root.y0 = 40;

                            // Horizontal gap between siblings (x), vertical gap between levels (y)
                            const nodeGapX = 120;
                            const levelGapY = 140;
                            const treeLayout = d3.tree().nodeSize([nodeGapX, levelGapY]);

                            function update(source) {
                                const treeRoot = treeLayout(root);
                                const nodes = treeRoot.descendants();
                                const links = treeRoot.links();

                                // Depth increases vertical position (downwards)
                                nodes.forEach(d => d.y = d.depth * levelGapY);

                                const node = g.selectAll('g.node')
                                    .data(nodes, d => d.id || (d.id = Math.random().toString(36).slice(2)));

                                const nodeEnter = node.enter().append('g')
                                    .attr('class', 'node')
                                    .attr('transform', d => `translate(${source.x0},${source.y0})`)
                                    .on('click', (_, d) => {
                                        if (d.children) {
                                            d._children = d.children; d.children = null;
                                        } else {
                                            d.children = d._children; d._children = null;
                                        }
                                        update(d);
                                    });

                                nodeEnter.append('circle').attr('r', 6).attr('fill', '#22c55e');

                                // Name + spouse inline
                                nodeEnter.append('text')
                                    .attr('dy', -10)
                                    .attr('x', 0)
                                    .style('text-anchor', 'middle')
                                    .text(d => d.data.spouse ? `${d.data.name}  ⇄  ${d.data.spouse}` : d.data.name)
                                    .append('title').text(d => `${d.data.name}${d.data.spouse ? ' & ' + d.data.spouse : ''}`);

                                const nodeUpdate = nodeEnter.merge(node);
                                nodeUpdate.transition().attr('transform', d => `translate(${d.x},${d.y})`);

                                const link = g.selectAll('path.link')
                                    .data(links, d => d.target.id);

                                const linkEnter = link.enter().insert('path', 'g')
                                    .attr('class', 'link')
                                    .attr('fill', 'none')
                                    .attr('stroke', '#94a3b8')
                                    .attr('stroke-width', 1.5)
                                    .attr('d', _ => diagonal({
                                        source: {x: source.x0, y: source.y0},
                                        target: {x: source.x0, y: source.y0}
                                    }));

                                linkEnter.merge(link).transition().attr('d', d => diagonal(d));

                                nodes.forEach(d => { d.x0 = d.x; d.y0 = d.y; });
                            }

                            function diagonal(d) {
                                return `M ${d.source.x},${d.source.y}
                                        C ${d.source.x},${(d.source.y + d.target.y) / 2}
                                          ${d.target.x},${(d.source.y + d.target.y) / 2}
                                          ${d.target.x},${d.target.y}`;
                            }

                            // Center root horizontally, start at top
                            root.x0 = width / 2;
                            root.y0 = 40;

                            // Collapse only nodes deeper than level 2 (keep grandchildren visible)
                            root.each(d => {
                                if (d.depth >= 3 && d.children) {
                                    d._children = d.children;
                                    d.children = null;
                                }
                            });
                            update(root);
                        } else {
                            const pre = document.createElement('pre');
                            pre.textContent = JSON.stringify(treeData, null, 2);
                            container.appendChild(pre);
                        }
                    });
                </script>
                <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
            </div>
        </div>
    </div>
</x-app-layout>