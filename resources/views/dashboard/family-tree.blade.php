<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pohon Keluarga</h2>
    </x-slot>

    @push('styles')
        <link href="{{ asset('css/enhanced-family-tree.css') }}" rel="stylesheet">
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 family-tree-container">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Pohon Keluarga
                            {{ $family->family_name ?? 'Anda' }}</h3>
                        <p class="text-sm text-gray-600">Pohon keluarga dengan foto profil dan pasangan terpisah</p>
                    </div>
                    <div class="flex space-x-2">
                        <button id="zoom-in"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                        <button id="zoom-out"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                </path>
                            </svg>
                        </button>
                        <button id="reset-zoom"
                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                            Reset
                        </button>
                    </div>
                </div>

                <div id="family-tree-container"
                    style="height:75vh; min-height:600px; width:100%; overflow:hidden; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div class="family-tree-loading">
                        Memuat pohon keluarga...
                    </div>
                </div>

                <script>
                    // Tree JSON passed from server
                    const treeData = {!! $treeJson !!};

                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('family-tree-container');

                        console.log('Tree data received:', treeData);

                        if (!treeData || typeof treeData !== 'object') {
                            console.warn('Tree data malformed', treeData);
                            const loadingEl = container.querySelector('.family-tree-loading');
                            if (loadingEl) {
                                loadingEl.innerHTML = 'Error: Data pohon keluarga tidak valid';
                            }
                            return;
                        }

                        // Enhanced D3 tree with separate nodes for couples and profile images
                        if (window.d3) {
                            const width = container.clientWidth || 1200;
                            const height = container.clientHeight || 700;

                            const svg = d3.select(container)
                                .append('svg')
                                .attr('width', width)
                                .attr('height', height)
                                .call(d3.zoom().scaleExtent([0.2, 3]).on('zoom', (event) => {
                                    g.attr('transform', event.transform);
                                }));

                            const g = svg.append('g').attr('transform', 'translate(60,40)');

                            // Preprocess tree data: add children from mother_data to tree structure
                            function preprocessTreeData(data) {
                                if (!data || typeof data !== 'object') return data;
                                
                                // If this is a couple node with mother_data that has children
                                if (data.type === 'couple' && data.mother_data) {
                                    // Handle single mother
                                    if (!Array.isArray(data.mother_data) && data.mother_data.children && data.mother_data.children.length > 0) {
                                        // Store reference to mother with children for link generation
                                        data._motherWithChildren = {
                                            motherId: data.mother_data.id,
                                            children: data.mother_data.children.map(child => preprocessTreeData(child))
                                        };
                                        // Add children to couple node's children array so D3.js processes them
                                        if (!data.children) data.children = [];
                                        data.children = [...data.children, ...data._motherWithChildren.children];
                                        // Remove children from mother_data (already added to couple node)
                                        delete data.mother_data.children;
                                    }
                                    // Handle multiple mothers
                                    else if (Array.isArray(data.mother_data)) {
                                        data._mothersWithChildren = [];
                                        data.mother_data.forEach((mother, idx) => {
                                            if (mother.children && mother.children.length > 0) {
                                                const processedChildren = mother.children.map(child => preprocessTreeData(child));
                                                data._mothersWithChildren.push({
                                                    index: idx,
                                                    motherId: mother.id,
                                                    children: processedChildren
                                                });
                                                // Add children to couple node's children array so D3.js processes them
                                                if (!data.children) data.children = [];
                                                data.children = [...data.children, ...processedChildren];
                                                // Remove children from mother_data (already added to couple node)
                                                delete mother.children;
                                            }
                                        });
                                    }
                                }
                                
                                // Recursively process children
                                if (data.children && Array.isArray(data.children)) {
                                    data.children = data.children.map(child => preprocessTreeData(child));
                                }
                                
                                return data;
                            }
                            
                            const processedTreeData = preprocessTreeData(JSON.parse(JSON.stringify(treeData)));
                            const root = d3.hierarchy(processedTreeData, d => d.children || []);
                            root.x0 = height / 2;
                            root.y0 = 60;

                            // Increased gaps for better spacing with profile images
                            const nodeGapX = 180;
                            const siblingGapX = 90; // Closer spacing for siblings (children from same mother)
                            const levelGapY = 160;
                            const coupleGap = 180; // Gap between couple nodes (increased for better spacing)
                            const treeLayout = d3.tree().nodeSize([nodeGapX, levelGapY]);

                            function getDefaultAvatar(gender) {
                                return gender === 'female' ? '/images/female-avatar.svg' : '/images/male-avatar.svg';
                            }

                            function getPhotoUrl(photo) {
                                if (!photo) return null;
                                return photo.startsWith('http') ? photo : `/storage/${photo}`;
                            }

                            function truncateName(name) {
                                if (!name) return '';
                                if (name.length <= 15) return name;
                                return name.substring(0, 15) + '...';
                            }

                            function update(source) {
                                const treeRoot = treeLayout(root);
                                const nodes = treeRoot.descendants();
                                const links = treeRoot.links();

                                // Adjust positions for couples
                                nodes.forEach(d => {
                                    d.y = d.depth * levelGapY;

                                    // If this is a couple node, create separate father and mother positions
                                    if (d.data.type === 'couple' && d.data.father_data) {
                                        // Create father node (left side, blue)
                                        d.father = {
                                            x: d.x - coupleGap / 2,
                                            y: d.y,
                                            data: d.data.father_data
                                        };

                                        // mother_data may be single object or an array (multiple mothers)
                                        if (Array.isArray(d.data.mother_data)) {
                                            // Position mothers in a chain: father -> mother1 -> mother2 -> mother3...
                                            // Each mother is connected to the previous one (father or previous mother)
                                            let currentX = d.father.x + coupleGap; // Start from right of father
                                            d.mothers = d.data.mother_data.map((md, i) => {
                                                const motherPos = {
                                                    x: currentX,
                                                    y: d.y,
                                                    data: md,
                                                    index: i
                                                };
                                                // Move to next position for next mother
                                                currentX = currentX + coupleGap;
                                                return motherPos;
                                            });
                                        } else if (d.data.mother_data) {
                                            // Single mother: position to the right of father
                                            d.mother = {
                                                x: d.father.x + coupleGap,
                                                y: d.y,
                                                data: d.data.mother_data
                                            };
                                        }
                                    }
                                });

                                // Replace links for children that come from specific mothers
                                // First, identify which children belong to which mother
                                const motherChildrenMap = new Map(); // Map: childId -> mother node
                                const parentIdMap = new Map(); // Map: childId -> parentId (motherId) for grouping siblings
                                nodes.forEach(d => {
                                    if (d.data.type === 'couple' && d.data._motherWithChildren) {
                                        // Single mother with children
                                        const mother = d.mother || (d.mothers && d.mothers[0]);
                                        const motherId = d.data._motherWithChildren.motherId;
                                        if (mother && d.data._motherWithChildren.children) {
                                            d.data._motherWithChildren.children.forEach(childData => {
                                                // Use multiple identifiers to match child
                                                const childId = childData.id || childData.name;
                                                const childName = childData.name;
                                                if (childId) {
                                                    motherChildrenMap.set(childId, mother);
                                                    parentIdMap.set(childId, motherId);
                                                }
                                                if (childName && childName !== childId) {
                                                    motherChildrenMap.set(childName, mother);
                                                    parentIdMap.set(childName, motherId);
                                                }
                                            });
                                        }
                                    } else if (d.data.type === 'couple' && d.data._mothersWithChildren) {
                                        // Multiple mothers with children
                                        d.data._mothersWithChildren.forEach(mwc => {
                                            const mother = d.mothers && d.mothers[mwc.index];
                                            const motherId = mwc.motherId;
                                            if (mother && mwc.children) {
                                                mwc.children.forEach(childData => {
                                                    // Use multiple identifiers to match child
                                                    const childId = childData.id || childData.name;
                                                    const childName = childData.name;
                                                    if (childId) {
                                                        motherChildrenMap.set(childId, mother);
                                                        parentIdMap.set(childId, motherId);
                                                    }
                                                    if (childName && childName !== childId) {
                                                        motherChildrenMap.set(childName, mother);
                                                        parentIdMap.set(childName, motherId);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                                
                                // Adjust positions for siblings (children with same parent_id)
                                // Group children by parent_id and adjust spacing
                                const childrenByParentId = new Map(); // Map: parentId -> array of child nodes
                                nodes.forEach(node => {
                                    if (node.data.type === 'person' && node.data.relation === 'child') {
                                        const childId = node.data.id || node.data.name;
                                        const parentId = parentIdMap.get(childId);
                                        if (parentId) {
                                            if (!childrenByParentId.has(parentId)) {
                                                childrenByParentId.set(parentId, []);
                                            }
                                            childrenByParentId.get(parentId).push(node);
                                        }
                                    }
                                });
                                
                                // Adjust x positions for siblings to be closer together
                                childrenByParentId.forEach((siblings, parentId) => {
                                    if (siblings.length > 1) {
                                        // Sort siblings by current x position
                                        siblings.sort((a, b) => a.x - b.x);
                                        
                                        // Calculate total width needed for siblings with closer spacing
                                        const radius = 30; // Circle radius
                                        const totalSiblingWidth = siblings.length * (radius * 2) + (siblings.length - 1) * siblingGapX;
                                        
                                        // Calculate center x of the group
                                        const centerX = siblings.reduce((sum, n) => sum + n.x, 0) / siblings.length;
                                        
                                        // Reposition siblings starting from left
                                        const startX = centerX - totalSiblingWidth / 2 + radius;
                                        siblings.forEach((sibling, index) => {
                                            sibling.x = startX + index * (radius * 2 + siblingGapX);
                                        });
                                    }
                                });

                                // Handle regular person nodes (non-couple)
                                const personNodes = nodes.filter(d => d.data.type === 'person');
                                const node = g.selectAll('g.person-node')
                                    .data(personNodes, d => d.id || (d.id = Math.random().toString(36).slice(2)));

                                const nodeEnter = node.enter().append('g')
                                    .attr('class', 'person-node')
                                    .attr('transform', d => `translate(${source.x0},${source.y0})`)
                                    .on('click', (_, d) => {
                                        if (d.children) {
                                            d._children = d.children;
                                            d.children = null;
                                        } else {
                                            d.children = d._children;
                                            d._children = null;
                                        }
                                        update(d);
                                    });

                                // Add profile image for person nodes
                                nodeEnter.append('circle')
                                    .attr('r', 30)
                                    .attr('fill', '#f3f4f6')
                                    .attr('stroke', d => d.data.gender === 'female' ? '#ec4899' : '#3b82f6')
                                    .attr('stroke-width', 3);

                                nodeEnter.append('image')
                                    .attr('x', -25)
                                    .attr('y', -25)
                                    .attr('width', 50)
                                    .attr('height', 50)
                                    .attr('href', d => {
                                        const photoUrl = getPhotoUrl(d.data.photo);
                                        return photoUrl || getDefaultAvatar(d.data.gender);
                                    })
                                    .attr('clip-path', 'circle(25px at 25px 25px)')
                                    .attr('preserveAspectRatio', 'xMidYMid slice')
                                    .style('cursor', 'pointer');

                                // Add background rectangle for name
                                const personNameBg = nodeEnter.append('rect')
                                    .attr('x', d => {
                                        const name = truncateName(d.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return -textWidth / 2 - 4;
                                    })
                                    .attr('y', 33)
                                    .attr('width', d => {
                                        const name = truncateName(d.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return textWidth + 8;
                                    })
                                    .attr('height', 16)
                                    .attr('fill', 'white')
                                    .attr('stroke', 'none')
                                    .attr('rx', 3);

                                nodeEnter.append('text')
                                    .attr('dy', 45)
                                    .attr('x', 0)
                                    .style('text-anchor', 'middle')
                                    .style('font-size', '12px')
                                    .style('font-weight', 'bold')
                                    .style('fill', '#1f2937')
                                    .text(d => truncateName(d.data.name))
                                    .append('title')
                                    .text(d => `${d.data.name}${d.data.description ? '\n' + d.data.description : ''}`);

                                // Handle couple nodes - create father nodes
                                const coupleNodes = nodes.filter(d => d.data.type === 'couple' && d.father);

                                // Father nodes
                                const fatherNode = g.selectAll('g.father-node')
                                    .data(coupleNodes, d => d.id + '_father');

                                const fatherEnter = fatherNode.enter().append('g')
                                    .attr('class', 'father-node')
                                    .attr('transform', d => `translate(${d.father.x},${d.father.y})`);

                                // Father profile image (blue border)
                                fatherEnter.append('circle')
                                    .attr('r', 30)
                                    .attr('fill', '#f3f4f6')
                                    .attr('stroke', '#3b82f6')
                                    .attr('stroke-width', 3);

                                fatherEnter.append('image')
                                    .attr('x', -25)
                                    .attr('y', -25)
                                    .attr('width', 50)
                                    .attr('height', 50)
                                    .attr('href', d => {
                                        const photoUrl = getPhotoUrl(d.father.data.photo);
                                        return photoUrl || getDefaultAvatar(d.father.data.gender);
                                    })
                                    .attr('clip-path', 'circle(25px at 25px 25px)')
                                    .attr('preserveAspectRatio', 'xMidYMid slice')
                                    .style('cursor', 'pointer');

                                // Add background rectangle for father name
                                const fatherNameBg = fatherEnter.append('rect')
                                    .attr('x', d => {
                                        const name = truncateName(d.father.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return -textWidth / 2 - 4;
                                    })
                                    .attr('y', 33)
                                    .attr('width', d => {
                                        const name = truncateName(d.father.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return textWidth + 8;
                                    })
                                    .attr('height', 16)
                                    .attr('fill', 'white')
                                    .attr('stroke', 'none')
                                    .attr('rx', 3);

                                fatherEnter.append('text')
                                    .attr('dy', 45)
                                    .attr('x', 0)
                                    .style('text-anchor', 'middle')
                                    .style('font-size', '12px')
                                    .style('font-weight', 'bold')
                                    .style('fill', '#1f2937')
                                    .text(d => truncateName(d.father.data.name))
                                    .append('title')
                                    .text(d =>
                                        `${d.father.data.name}${d.father.data.description ? '\n' + d.father.data.description : ''}`
                                    );

                                // Mothers: could be single .mother or array .mothers
                                // Flatten mothers for data-binding: create virtual items per mother with parent id
                                let motherFlat = [];
                                coupleNodes.forEach(d => {
                                    if (d.mothers) {
                                        d.mothers.forEach(m => motherFlat.push({
                                            parentId: d.id,
                                            node: d,
                                            mother: m
                                        }));
                                    } else if (d.mother) {
                                        motherFlat.push({
                                            parentId: d.id,
                                            node: d,
                                            mother: d.mother
                                        });
                                    }
                                });

                                const motherNode = g.selectAll('g.mother-node')
                                    .data(motherFlat, d => d.parentId + '_mother_' + (d.mother.index ?? (d.mother.data ? d
                                        .mother.data.id : Math.random())));

                                const motherEnter = motherNode.enter().append('g')
                                    .attr('class', 'mother-node')
                                    .attr('transform', d => `translate(${d.mother.x},${d.mother.y})`);

                                // Mother profile image (pink border)
                                motherEnter.append('circle')
                                    .attr('r', 30)
                                    .attr('fill', '#f3f4f6')
                                    .attr('stroke', '#ec4899')
                                    .attr('stroke-width', 3);

                                motherEnter.append('image')
                                    .attr('x', -25)
                                    .attr('y', -25)
                                    .attr('width', 50)
                                    .attr('height', 50)
                                    .attr('href', d => {
                                        const photoUrl = getPhotoUrl(d.mother.data.photo || d.mother.data.photo);
                                        return photoUrl || getDefaultAvatar(d.mother.data.gender || d.mother.data.gender);
                                    })
                                    .attr('clip-path', 'circle(25px at 25px 25px)')
                                    .attr('preserveAspectRatio', 'xMidYMid slice')
                                    .style('cursor', 'pointer');

                                // Add background rectangle for mother name
                                const motherNameBg = motherEnter.append('rect')
                                    .attr('x', d => {
                                        const name = truncateName(d.mother.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return -textWidth / 2 - 4;
                                    })
                                    .attr('y', 33)
                                    .attr('width', d => {
                                        const name = truncateName(d.mother.data.name);
                                        const textWidth = name.length * 7; // Approximate width
                                        return textWidth + 8;
                                    })
                                    .attr('height', 16)
                                    .attr('fill', 'white')
                                    .attr('stroke', 'none')
                                    .attr('rx', 3);

                                motherEnter.append('text')
                                    .attr('dy', 45)
                                    .attr('x', 0)
                                    .style('text-anchor', 'middle')
                                    .style('font-size', '12px')
                                    .style('font-weight', 'bold')
                                    .style('fill', '#1f2937')
                                    .text(d => truncateName(d.mother.data.name))
                                    .append('title')
                                    .text(d =>
                                        `${d.mother.data.name}${d.mother.data.description ? '\n' + d.mother.data.description : ''}`
                                    );

                                // Add marriage lines: father -> mother1 -> mother2 -> mother3...
                                // Create connection data: father->first mother, then each mother to next mother
                                const connectionData = [];
                                coupleNodes.forEach(d => {
                                    if (d.mothers && d.mothers.length > 0) {
                                        // Connect father to first mother
                                        connectionData.push({
                                            parentId: d.id,
                                            node: d,
                                            from: d.father,
                                            to: d.mothers[0],
                                            index: 0
                                        });
                                        // Connect each mother to the next one
                                        for (let i = 0; i < d.mothers.length - 1; i++) {
                                            connectionData.push({
                                                parentId: d.id,
                                                node: d,
                                                from: d.mothers[i],
                                                to: d.mothers[i + 1],
                                                index: i + 1
                                            });
                                        }
                                    } else if (d.mother) {
                                        // Single mother: connect father to mother
                                        connectionData.push({
                                            parentId: d.id,
                                            node: d,
                                            from: d.father,
                                            to: d.mother,
                                            index: 0
                                        });
                                    }
                                });

                                const marriageLines = g.selectAll('line.marriage')
                                    .data(connectionData, d => d.parentId + '_marriage_' + d.index);

                                marriageLines.enter().append('line')
                                    .attr('class', 'marriage')
                                    .attr('stroke', '#6b7280')
                                    .attr('stroke-width', 2)
                                    .attr('x1', d => d.from.x + 30)
                                    .attr('y1', d => d.from.y)
                                    .attr('x2', d => d.to.x - 30)
                                    .attr('y2', d => d.to.y);

                                // Update positions for all nodes
                                const nodeUpdate = nodeEnter.merge(node);
                                nodeUpdate.transition().attr('transform', d => `translate(${d.x},${d.y})`);

                                const fatherUpdate = fatherEnter.merge(fatherNode);
                                fatherUpdate.transition().attr('transform', d => `translate(${d.father.x},${d.father.y})`);

                                const motherUpdate = motherEnter.merge(motherNode);
                                motherUpdate.transition().attr('transform', d => `translate(${d.mother.x},${d.mother.y})`);

                                // Update marriage lines
                                marriageLines.transition()
                                    .attr('x1', d => d.from.x + 30)
                                    .attr('y1', d => d.from.y)
                                    .attr('x2', d => d.to.x - 30)
                                    .attr('y2', d => d.to.y);

                                // Modify existing links to point to mother nodes when applicable
                                const modifiedLinks = links.map(linkItem => {
                                    const targetData = linkItem.target.data;
                                    const childId = targetData.id || targetData.name;
                                    const childName = targetData.name;
                                    
                                    // Check both ID and name
                                    let motherNode = motherChildrenMap.get(childId);
                                    if (!motherNode && childName) {
                                        motherNode = motherChildrenMap.get(childName);
                                    }
                                    
                                    if (motherNode) {
                                        return {
                                            ...linkItem,
                                            motherSource: motherNode
                                        };
                                    }
                                    return linkItem;
                                });

                                // Handle family tree links
                                const link = g.selectAll('path.link')
                                    .data(modifiedLinks, d => {
                                        const sourceId = d.source.id || (d.source.data && d.source.data.id) || Math.random().toString(36).slice(2);
                                        const targetId = d.target.id || (d.target.data && d.target.data.id) || Math.random().toString(36).slice(2);
                                        return sourceId + '_' + targetId;
                                    });

                                const linkEnter = link.enter().insert('path', 'g')
                                    .attr('class', 'link')
                                    .attr('fill', 'none')
                                    .attr('stroke', '#94a3b8')
                                    .attr('stroke-width', 2)
                                    .attr('d', _ => diagonal({
                                        source: {
                                            x: source.x0,
                                            y: source.y0
                                        },
                                        target: {
                                            x: source.x0,
                                            y: source.y0
                                        }
                                    }));

                                linkEnter.merge(link).transition().attr('d', d => {
                                    // Adjust link positions for couples
                                    let sourceX = d.source.x;
                                    let targetX = d.target.x;

                                    // If source is a couple, check if this link comes from a specific mother
                                    if (d.motherSource) {
                                        // Link from specific mother node
                                        sourceX = d.motherSource.x;
                                    } else if (d.source.data.type === 'couple' && d.source.father && (d.source.mother || (d.source.mothers && d.source.mothers.length > 0))) {
                                        if (d.source.mother) {
                                            sourceX = (d.source.father.x + d.source.mother.x) / 2;
                                        } else if (d.source.mothers && d.source.mothers.length > 0) {
                                            const lastMother = d.source.mothers[d.source.mothers.length - 1];
                                            sourceX = (d.source.father.x + lastMother.x) / 2;
                                        }
                                    }

                                    return diagonal({
                                        source: {
                                            x: sourceX,
                                            y: d.source.y + 30
                                        },
                                        target: {
                                            x: targetX,
                                            y: d.target.y - 30
                                        }
                                    });
                                });

                                nodes.forEach(d => {
                                    d.x0 = d.x;
                                    d.y0 = d.y;
                                });
                            }

                            function diagonal(d) {
                                return `M ${d.source.x},${d.source.y}
                                        C ${d.source.x},${(d.source.y + d.target.y) / 2}
                                          ${d.target.x},${(d.source.y + d.target.y) / 2}
                                          ${d.target.x},${d.target.y}`;
                            }

                            // Center root horizontally, start at top
                            root.x0 = width / 2;
                            root.y0 = 60;

                            // Collapse only nodes deeper than level 2 (keep grandchildren visible)
                            root.each(d => {
                                if (d.depth >= 3 && d.children) {
                                    d._children = d.children;
                                    d.children = null;
                                }
                            });

                            // Remove loading indicator
                            const loadingEl = container.querySelector('.family-tree-loading');
                            if (loadingEl) {
                                loadingEl.remove();
                            }

                            update(root);

                            // Add zoom controls
                            const zoom = d3.zoom().scaleExtent([0.2, 3]).on('zoom', (event) => {
                                g.attr('transform', event.transform);
                            });

                            svg.call(zoom);

                            // Zoom control buttons
                            document.getElementById('zoom-in').addEventListener('click', () => {
                                svg.transition().call(zoom.scaleBy, 1.5);
                            });

                            document.getElementById('zoom-out').addEventListener('click', () => {
                                svg.transition().call(zoom.scaleBy, 1 / 1.5);
                            });

                            document.getElementById('reset-zoom').addEventListener('click', () => {
                                svg.transition().call(zoom.transform, d3.zoomIdentity.translate(60, 40));
                            });

                        } else {
                            const pre = document.createElement('pre');
                            pre.textContent = JSON.stringify(treeData, null, 2);
                            container.appendChild(pre);

                            // Remove loading indicator
                            const loadingEl = container.querySelector('.family-tree-loading');
                            if (loadingEl) {
                                loadingEl.remove();
                            }
                        }
                    });
                </script>
                <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
            </div>
        </div>
    </div>
</x-app-layout>
