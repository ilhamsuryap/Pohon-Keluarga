// Family Tree Class
class FamilyTree {
    constructor(container, data) {
        this.container = container;
        this.data = data;
        this.svg = d3.select(container)
            .append('svg')
            .attr('width', '100%')
            .attr('height', '100%')
            .append('g');

        // Add zoom behavior
        const zoom = d3.zoom()
            .on('zoom', (event) => {
                this.svg.attr('transform', event.transform);
            });

        d3.select(container).select('svg').call(zoom);
    }

    // Draw the tree
    draw() {
        const treeLayout = d3.tree()
            .nodeSize([80, 160])
            .separation((a, b) => a.parent === b.parent ? 1 : 1.2);

        const root = d3.hierarchy(this.data);
        treeLayout(root);

        // Draw links
        const links = this.svg.selectAll('.link')
            .data(root.links())
            .join('path')
            .attr('class', 'link')
            .attr('d', d3.linkHorizontal()
                .x(d => d.y)
                .y(d => d.x))
            .attr('fill', 'none')
            .attr('stroke', '#ccc')
            .attr('stroke-width', 1.5);

        // Draw nodes
        const nodes = this.svg.selectAll('.node')
            .data(root.descendants())
            .join('g')
            .attr('class', 'node')
            .attr('transform', d => `translate(${d.y},${d.x})`);

        // Node rectangles
        nodes.append('rect')
            .attr('x', -60)
            .attr('y', -25)
            .attr('width', 120)
            .attr('height', 50)
            .attr('rx', 5)
            .attr('ry', 5)
            .attr('fill', 'white')
            .attr('stroke', d => this.getNodeColor(d.data))
            .attr('stroke-width', 2);

        // Node text (name)
        nodes.append('text')
            .attr('dy', '-5')
            .attr('text-anchor', 'middle')
            .attr('class', 'name')
            .text(d => d.data.name)
            .attr('fill', '#333')
            .style('font-size', '12px');

        // Node text (relation)
        nodes.append('text')
            .attr('dy', '15')
            .attr('text-anchor', 'middle')
            .attr('class', 'relation')
            .text(d => this.getRelationText(d.data))
            .attr('fill', '#666')
            .style('font-size', '10px');

        // Center the tree
        const bounds = this.svg.node().getBBox();
        const containerWidth = this.container.clientWidth;
        const containerHeight = this.container.clientHeight;
        const scale = Math.min(
            containerWidth / bounds.width,
            containerHeight / bounds.height
        ) * 0.9;

        const transform = d3.zoomIdentity
            .translate(
                (containerWidth - bounds.width * scale) / 2 - bounds.x * scale,
                (containerHeight - bounds.height * scale) / 2 - bounds.y * scale
            )
            .scale(scale);

        d3.select(this.container)
            .select('svg')
            .call(d3.zoom().transform, transform);
    }

    // Get node color based on gender
    getNodeColor(data) {
        return data.gender === 'male' ? '#4299e1' : '#ed64a6';
    }

    // Get relation text
    getRelationText(data) {
        const relations = {
            'father': 'Ayah',
            'mother': 'Ibu',
            'child': 'Anak'
        };
        return relations[data.relation] || data.relation;
    }
}

// Helper function to transform flat data to hierarchical
function transformToHierarchy(members) {
    // Find parents (father and mother)
    const parents = members.filter(m => m.relation === 'father' || m.relation === 'mother');
    const children = members.filter(m => m.relation === 'child');

    // Create root node with parents
    const root = {
        name: 'Keluarga',
        children: [...parents, ...children].map(member => ({
            name: member.name,
            relation: member.relation,
            gender: member.gender,
            birthDate: member.birth_date,
        }))
    };

    return root;
}
