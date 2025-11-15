@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="bg-gradient-to-r from-purple-600 to-blue-500 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">{{ $family->family_name }}</h1>
                            <p class="text-blue-100">Silsilah Keluarga</p>
                        </div>
                        <div class="flex space-x-4">
                            <button onclick="openAddModal()"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Anggota
                            </button>

                            <a href="{{ route('user.family.export.pdf', $family) }}"
                                class="inline-flex items-center px-6 py-3 border border-white text-sm font-medium rounded-xl text-white bg-transparent hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.257 3.099c.366-.446 1.12-.173 1.12.383v7.036c0 .556-.754.829-1.12.383L5.46 8.383a1 1 0 010-1.266l2.797-3.018z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Terjadi kesalahan input.</p>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Family Visualization -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Pohon Keluarga</h2>
                        <p class="text-gray-600">{{ $family->description }}</p>
                    </div>

                    <div id="family-tree"
                        class="rounded-2xl p-8 bg-gradient-to-br from-gray-50 to-blue-50 border border-gray-100">
                        <div class="family-tree-container" id="family-tree-container" style="width: 100%; height: 800px; position: relative; overflow: auto;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Family Description -->
            @if ($family->description)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Tentang Keluarga</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $family->description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div> --}}

            <!-- Enhanced Styles -->
            <style>
                .family-tree-container {
                    padding: 30px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    min-height: 400px;
                }

                .family-level {
                    display: flex;
                    justify-content: center;
                    margin-bottom: 40px;
                    gap: 30px;
                    flex-wrap: wrap;
                }

                .family-member {
                    background: linear-gradient(145deg, #ffffff, #f8fafc);
                    padding: 20px;
                    border-radius: 20px;
                    text-align: center;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    width: 180px;
                    position: relative;
                    transition: all 0.3s ease;
                    border: 2px solid transparent;
                }

                .family-member:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    border-color: #e0e7ff;
                }

                .family-member-photo {
                    width: 140px;
                    height: 140px;
                    border-radius: 20px;
                    overflow: hidden;
                    margin: 0 auto 15px;
                    background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
                    border: 4px solid #fff;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    position: relative;
                }

                .family-member-photo img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .family-member:hover .family-member-photo img {
                    transform: scale(1.05);
                }

                .family-member-name {
                    font-weight: 600;
                    margin-bottom: 5px;
                    color: #1f2937;
                    font-size: 16px;
                }

                .family-member-relation {
                    color: #6b7280;
                    font-size: 14px;
                    font-weight: 500;
                    background: #f3f4f6;
                    padding: 4px 12px;
                    border-radius: 12px;
                    display: inline-block;
                }
                
                /* Background colors for relation labels */
                .family-member-relation.bg-blue-500 {
                    background-color: #3b82f6 !important;
                    color: white;
                }
                
                .family-member-relation.bg-pink-500 {
                    background-color: #ec4899 !important;
                    color: white;
                }
                
                .family-member-relation.bg-green-500 {
                    background-color: #10b981 !important;
                    color: white;
                }

                .family-member-badge {
                    position: absolute;
                    top: -8px;
                    right: -8px;
                    background: linear-gradient(45deg, #ef4444, #dc2626);
                    color: white;
                    padding: 4px 10px;
                    border-radius: 15px;
                    font-size: 11px;
                    font-weight: 600;
                    text-transform: uppercase;
                    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
                }

                .family-connector {
                    width: 3px;
                    height: 50px;
                    background: linear-gradient(to bottom, #d1d5db, #9ca3af);
                    margin: 0 auto;
                    position: relative;
                    border-radius: 2px;
                }

                .family-connector::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 12px;
                    height: 12px;
                    background: linear-gradient(45deg, #6366f1, #8b5cf6);
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3);
                }

                /* Enhanced Action Buttons */
                .action-button {
                    position: absolute;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    border: 3px solid #fff;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    opacity: 0;
                    transform: scale(0.8);
                }

                .family-member:hover .action-button {
                    opacity: 1;
                    transform: scale(1);
                }

                .action-button.add {
                    background: linear-gradient(45deg, #10b981, #059669);
                    color: white;
                    font-size: 18px;
                    font-weight: bold;
                }

                .action-button.add:hover {
                    background: linear-gradient(45deg, #059669, #047857);
                    transform: scale(1.1);
                }

                .action-button.edit {
                    background: linear-gradient(45deg, #3b82f6, #2563eb);
                    color: white;
                }

                .action-button.edit:hover {
                    background: linear-gradient(45deg, #2563eb, #1d4ed8);
                    transform: scale(1.1);
                }

                .action-button.delete {
                    background: linear-gradient(45deg, #ef4444, #dc2626);
                    color: white;
                }

                .action-button.delete:hover {
                    background: linear-gradient(45deg, #dc2626, #b91c1c);
                    transform: scale(1.1);
                }

                .action-button.view {
                    background: linear-gradient(45deg, #8b5cf6, #7c3aed);
                    color: white;
                }

                .action-button.view:hover {
                    background: linear-gradient(45deg, #7c3aed, #6d28d9);
                    transform: scale(1.1);
                }

                .action-button-top-right {
                    top: -12px;
                    right: -12px;
                }

                .action-button-top-left {
                    top: -12px;
                    left: -12px;
                }

                .action-button-bottom-right {
                    bottom: -12px;
                    right: -12px;
                }

                .action-button-bottom-left {
                    bottom: -12px;
                    left: -12px;
                }

                .action-buttons-container {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                }

                /* Avatar Styles */
                .family-avatar {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 3px solid #e5e7eb;
                    background: #f9fafb;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .family-avatar.male {
                    border-color: #3b82f6;
                    background: linear-gradient(145deg, #dbeafe, #bfdbfe);
                }

                .family-avatar.female {
                    border-color: #ec4899;
                    background: linear-gradient(145deg, #fce7f3, #fbcfe8);
                }

                .preview-avatar-add,
                .preview-avatar-edit {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 3px solid #e5e7eb;
                    background: #f9fafb;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }

                .preview-avatar-add.male,
                .preview-avatar-edit.male {
                    border-color: #3b82f6;
                    background: linear-gradient(145deg, #dbeafe, #bfdbfe);
                }

                .preview-avatar-add.female,
                .preview-avatar-edit.female {
                    border-color: #ec4899;
                    background: linear-gradient(145deg, #fce7f3, #fbcfe8);
                }

                /* Modal Enhancements */
                .modal-content {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
                    border: 1px solid #e5e7eb;
                }

                /* Form Enhancements */
                .form-input {
                    border-radius: 12px;
                    border: 2px solid #e5e7eb;
                    transition: all 0.3s ease;
                }

                .form-input:focus {
                    border-color: #6366f1;
                    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                }

                /* Button Enhancements */
                .btn-primary {
                    background: linear-gradient(45deg, #6366f1, #8b5cf6);
                    border: none;
                    border-radius: 12px;
                    padding: 12px 24px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
                }

                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
                }

                /* Animation for empty state */
                .empty-state {
                    text-align: center;
                    padding: 60px 20px;
                    color: #6b7280;
                }

                .empty-state svg {
                    width: 80px;
                    height: 80px;
                    margin: 0 auto 20px;
                    opacity: 0.5;
                }
            </style>

            @if(isset($treeJson) && $treeJson)
                <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
                <script>
                    // Tree JSON passed from server
                    const treeData = {!! $treeJson !!};
                    const members = @json($familyMembers);

                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('family-tree-container');

                        if (!members || members.length === 0) {
                            container.innerHTML = `
                                <div class="empty-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold mb-2">Belum Ada Anggota Keluarga</h3>
                                    <p class="mb-6">Mulai membangun pohon keluarga Anda dengan menambahkan anggota pertama</p>
                                    <button onclick="openAddModal()" class="btn-primary text-white">
                                        Tambah Anggota Pertama
                                    </button>
                                </div>
                            `;
                            return;
                        }

                        if (!treeData || typeof treeData !== 'object') {
                            container.innerHTML = '<div class="empty-state"><p>Data pohon keluarga tidak valid</p></div>';
                            return;
                        }

                        // Create member lookup map by NIK and name
                        const membersMap = {};
                        members.forEach(m => {
                            if (m.nik) membersMap[m.nik] = m;
                            membersMap[m.name] = m;
                            membersMap[m.id] = m;
                        });

                        // Prepare counters for numbered labels
                        const indices = {};
                        members.forEach(m => {
                            const key = m.relation || m.role || 'anggota';
                            indices[key] = (indices[key] || 0) + 1;
                        });

                        function mapRoleLabel(key) {
                            if (!key) return 'Anggota';
                            switch (key) {
                                case 'father': return 'Ayah';
                                case 'mother': return 'Ibu';
                                case 'child': return 'Anak';
                                default: return (key.charAt(0).toUpperCase() + key.slice(1));
                            }
                        }

                        function numberedLabelFor(member) {
                            const key = member.relation || member.role || 'anggota';
                            const label = mapRoleLabel(key);
                            return label; // Return only label without numbering
                        }

                        // Helper to find member data from tree node
                        function findMemberData(nodeData) {
                            if (nodeData.nik && membersMap[nodeData.nik]) {
                                return membersMap[nodeData.nik];
                            }
                            if (nodeData.name && membersMap[nodeData.name]) {
                                return membersMap[nodeData.name];
                            }
                            if (nodeData.id && membersMap[nodeData.id]) {
                                return membersMap[nodeData.id];
                            }
                            // Return node data itself if not found in members
                            return nodeData;
                        }

                        // Enhanced D3 tree with card-style nodes
                        if (window.d3) {
                            const width = container.clientWidth || 1400;
                            const height = container.clientHeight || 800;

                            // Clear container
                            container.innerHTML = '';

                            const svg = d3.select(container)
                                .append('svg')
                                .attr('width', width)
                                .attr('height', height)
                                .style('overflow', 'visible');

                            const g = svg.append('g').attr('transform', 'translate(60,40)');

                            // Add zoom with panning
                            const zoom = d3.zoom()
                                .scaleExtent([0.3, 2])
                                .translateExtent([[-1000, -1000], [width + 1000, height + 1000]])
                                .on('zoom', (event) => {
                                    g.attr('transform', event.transform);
                                });
                            svg.call(zoom);

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
                            root.x0 = width / 2;
                            root.y0 = 60;

                            // Node card size: 180px width x 280px height
                            // Set spacing to approximately 2.5x node dimensions for better readability
                            const nodeGapX = 500; // Horizontal gap between nodes (2.5x node width + padding)
                            const siblingGapX = 250; // Horizontal gap between siblings (children from same mother) - closer
                            const levelGapY = 500; // Vertical gap between levels (node height + extra spacing)
                            const coupleGap = 400; // Gap between father and mother nodes (increased for better spacing)
                            const treeLayout = d3.tree().nodeSize([nodeGapX, levelGapY]);
                            
                            // Node edge positions for link connections
                            const nodeWidth = 180;
                            const nodeHeight = 280;
                            const nodeTop = -140; // Top edge of node
                            const nodeBottom = 140; // Bottom edge of node (nodeTop + nodeHeight)
                            const nodeLeft = -90; // Left edge of node
                            const nodeRight = 90; // Right edge of node (nodeLeft + nodeWidth)

                            function getDefaultAvatar(gender) {
                                return gender === 'female' ? '/images/female-avatar.svg' : '/images/male-avatar.svg';
                            }

                            // Helper function to handle edit button click
                            window.handleEditMember = function(memberId, familyId, memberData) {
                                document.getElementById('edit_member_id').value = memberId || '';
                                document.getElementById('edit_name').value = memberData.name || '';
                                document.getElementById('edit_nik').value = memberData.nik || '';
                                document.getElementById('edit_relation').value = memberData.relation || '';
                                document.getElementById('edit_gender').value = memberData.gender || '';
                                
                                if (memberData.birth_date) {
                                    const birthDate = new Date(memberData.birth_date);
                                    document.getElementById('edit_birth_date').value = birthDate.toISOString().split('T')[0];
                                }
                                
                                document.getElementById('edit_description').value = memberData.description || '';
                                
                                const form = document.getElementById('editForm');
                                form.action = `/user/family/${familyId}/members/${memberId}`;
                                
                                const previewDiv = document.querySelector('.preview-avatar-edit');
                                const photoUrl = memberData.photo ? `/storage/${memberData.photo}` : getDefaultAvatar(memberData.gender || 'male');
                                previewDiv.innerHTML = `<img src="${photoUrl}" class="w-full h-full object-cover">`;
                                
                                document.getElementById('editModal').classList.remove('hidden');
                            };
                            
                            // Helper function to handle delete button click
                            window.handleDeleteMember = function(memberId, familyId) {
                                if (confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')) {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = `/user/family/${familyId}/members/${memberId}`;
                                    const csrfToken = document.querySelector('meta[name=csrf-token]').content;
                                    
                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    
                                    const tokenInput = document.createElement('input');
                                    tokenInput.type = 'hidden';
                                    tokenInput.name = '_token';
                                    tokenInput.value = csrfToken;
                                    
                                    form.appendChild(methodInput);
                                    form.appendChild(tokenInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            };

                            // Create HTML node for member
                            function createMemberNodeHTML(member) {
                                const memberData = findMemberData(member);
                                const relationLabel = numberedLabelFor(memberData);
                                
                                const photoUrl = memberData.photo ? `/storage/${memberData.photo}` : getDefaultAvatar(memberData.gender || 'male');
                                const memberId = memberData.id || '';
                                const familyId = memberData.family_id || {{ $family->id }};
                                let memberName = (memberData.name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                                
                                // Truncate name if longer than 15 characters
                                if (memberName.length > 15) {
                                    memberName = memberName.substring(0, 15) + '...';
                                }
                                
                                // Determine background color based on relation
                                let relationBgColor = '';
                                if (memberData.relation === 'father') {
                                    relationBgColor = 'bg-blue-500'; // Biru untuk Ayah
                                } else if (memberData.relation === 'mother') {
                                    relationBgColor = 'bg-pink-500'; // Pink untuk Ibu
                                } else if (memberData.relation === 'child') {
                                    relationBgColor = 'bg-green-500'; // Hijau untuk Anak
                                }
                                
                                // Create safe JSON string for showMemberDetail and handleEditMember
                                const memberJson = JSON.stringify(memberData).replace(/"/g, '&quot;');
                                
                                let html = `
                                    <div class="family-member" style="width: 180px; position: relative;" data-member-id="${memberId}" data-family-id="${familyId}">
                                        <div class="action-buttons-container">
                                            <button class="action-button view action-button-top-left" onclick="showMemberDetail(${memberJson})" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                `;
                                
                                if (memberData.relation === 'father') {
                                    html += `<button class="action-button add action-button-top-right" onclick="openAddModal()" title="Tambah Anggota">+</button>`;
                                }
                                
                                html += `
                                            <button class="action-button edit action-button-bottom-right" onclick="handleEditMember('${memberId}', '${familyId}', ${memberJson})" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button class="action-button delete action-button-bottom-left" onclick="handleDeleteMember('${memberId}', '${familyId}')" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        <div class="family-member-photo">
                                            <img src="${photoUrl}" alt="${memberName}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="family-member-name">${memberName}</div>
                                        <div class="family-member-relation ${relationBgColor}">${relationLabel}</div>
                                    </div>
                                `;
                                
                                return html;
                            }

                            function update(source) {
                                const treeRoot = treeLayout(root);
                                let nodes = treeRoot.descendants();
                                const links = treeRoot.links();

                                // Adjust positions for couples - create separate father and mother positions
                                nodes.forEach(d => {
                                    d.y = d.depth * levelGapY;

                                    // If this is a couple node, create separate father and mother positions
                                    if (d.data.type === 'couple' && d.data.father_data) {
                                        // Create father node (left side) - horizontally separated, same y level
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
                                            d.mother = {
                                                x: d.x + coupleGap / 2,
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
                                        const totalSiblingWidth = siblings.length * 180 + (siblings.length - 1) * siblingGapX;
                                        
                                        // Calculate center x of the group
                                        const centerX = siblings.reduce((sum, n) => sum + n.x, 0) / siblings.length;
                                        
                                        // Reposition siblings starting from left
                                        const startX = centerX - totalSiblingWidth / 2 + 90; // 90 = half of node width
                                        siblings.forEach((sibling, index) => {
                                            sibling.x = startX + index * (180 + siblingGapX);
                                        });
                                    }
                                });
                                
                                // Modify existing links to point to mother nodes when applicable
                                const modifiedLinks = links.map(link => {
                                    const targetData = link.target.data;
                                    const childId = targetData.id || targetData.name;
                                    const childName = targetData.name;
                                    
                                    // Check both ID and name
                                    let motherNode = motherChildrenMap.get(childId);
                                    if (!motherNode && childName) {
                                        motherNode = motherChildrenMap.get(childName);
                                    }
                                    
                                    if (motherNode) {
                                        return {
                                            ...link,
                                            motherSource: motherNode
                                        };
                                    }
                                    return link;
                                });
                                
                                const allLinks = modifiedLinks;
                                
                                // Handle links
                                const link = g.selectAll('path.link')
                                    .data(allLinks, d => {
                                        // Create unique ID for each link
                                        const sourceId = d.source.id || (d.source.data && d.source.data.id) || Math.random().toString(36).slice(2);
                                        const targetId = d.target.id || (d.target.data && d.target.data.id) || Math.random().toString(36).slice(2);
                                        return sourceId + '_' + targetId;
                                    });

                                const linkEnter = link.enter().insert('path', 'g')
                                    .attr('class', 'link')
                                    .attr('fill', 'none')
                                    .attr('stroke', '#94a3b8')
                                    .attr('stroke-width', 2)
                                    .attr('d', _ => {
                                        const o = {x: source.x0, y: source.y0};
                                        const sourceConnectY = o.y + nodeBottom;
                                        const targetConnectY = source.y0 + nodeTop;
                                        const midY = (sourceConnectY + targetConnectY) / 2;
                                        return `M ${o.x},${sourceConnectY} C ${o.x},${midY} ${source.x0},${midY} ${source.x0},${targetConnectY}`;
                                    });

                                linkEnter.merge(link).transition()
                                    .duration(750)
                                    .attr('d', d => {
                                        // Link from edge of nodes, not center
                                        let sourceX = d.source.x;
                                        let sourceY = d.source.y;
                                        let targetX = d.target.x;
                                        let targetY = d.target.y;
                                        
                                        // Calculate connection points from edges
                                        // Source node: connect from bottom edge (for parent to child)
                                        const sourceConnectY = sourceY + nodeBottom;
                                        
                                        // Target node: connect to top edge (for child receiving from parent)
                                        const targetConnectY = targetY + nodeTop;
                                        
                                        // For horizontal positioning, use center of node width
                                        // If source is a couple, check if this link comes from a specific mother
                                        if (d.motherSource) {
                                            // Link from specific mother node
                                            sourceX = d.motherSource.x;
                                        } else if (d.source.data.type === 'couple' && d.source.father && (d.source.mother || d.source.mothers)) {
                                            if (d.source.mother) {
                                                sourceX = (d.source.father.x + d.source.mother.x) / 2;
                                            } else if (d.source.mothers && d.source.mothers.length > 0) {
                                                const lastMother = d.source.mothers[d.source.mothers.length - 1];
                                                sourceX = (d.source.father.x + lastMother.x) / 2;
                                            }
                                        }
                                        
                                        // Create curved path from bottom of source to top of target
                                        const midY = (sourceConnectY + targetConnectY) / 2;
                                        return `M ${sourceX},${sourceConnectY} C ${sourceX},${midY} ${targetX},${midY} ${targetX},${targetConnectY}`;
                                    });

                                // Handle regular person nodes (non-couple)
                                const personNodes = nodes.filter(d => d.data.type === 'person');
                                const node = g.selectAll('g.person-node')
                                    .data(personNodes, d => d.id || (d.id = Math.random().toString(36).slice(2)));

                                const nodeEnter = node.enter().append('g')
                                    .attr('class', 'person-node')
                                    .attr('transform', d => `translate(${source.x0},${source.y0})`);

                                // Add foreignObject for person nodes
                                const personForeignObject = nodeEnter.append('foreignObject')
                                    .attr('width', 180)
                                    .attr('height', 280)
                                    .attr('x', -90)
                                    .attr('y', -140)
                                    .style('overflow', 'visible');

                                personForeignObject.append('xhtml:div')
                                    .html(d => createMemberNodeHTML(d.data));

                                // Update person node positions
                                const personNodeUpdate = nodeEnter.merge(node);
                                personNodeUpdate.transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${d.x},${d.y})`);

                                // Handle couple nodes - create father nodes
                                const coupleNodes = nodes.filter(d => d.data.type === 'couple' && d.father);
                                
                                // Father nodes
                                const fatherNode = g.selectAll('g.father-node')
                                    .data(coupleNodes, d => d.id + '_father');

                                const fatherEnter = fatherNode.enter().append('g')
                                    .attr('class', 'father-node')
                                    .attr('transform', d => `translate(${d.father.x},${d.father.y})`);

                                const fatherForeignObject = fatherEnter.append('foreignObject')
                                    .attr('width', 180)
                                    .attr('height', 280)
                                    .attr('x', -90)
                                    .attr('y', -140)
                                    .style('overflow', 'visible');

                                fatherForeignObject.append('xhtml:div')
                                    .html(d => createMemberNodeHTML(d.father.data));

                                const fatherUpdate = fatherEnter.merge(fatherNode);
                                fatherUpdate.transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${d.father.x},${d.father.y})`);

                                // Mother nodes - handle both single and multiple mothers
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
                                    .data(motherFlat, d => d.parentId + '_mother_' + (d.mother.index ?? (d.mother.data ? d.mother.data.id : Math.random())));

                                const motherEnter = motherNode.enter().append('g')
                                    .attr('class', 'mother-node')
                                    .attr('transform', d => `translate(${d.mother.x},${d.mother.y})`);

                                const motherForeignObject = motherEnter.append('foreignObject')
                                    .attr('width', 180)
                                    .attr('height', 280)
                                    .attr('x', -90)
                                    .attr('y', -140)
                                    .style('overflow', 'visible');

                                motherForeignObject.append('xhtml:div')
                                    .html(d => createMemberNodeHTML(d.mother.data));

                                const motherUpdate = motherEnter.merge(motherNode);
                                motherUpdate.transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${d.mother.x},${d.mother.y})`);

                                // Add marriage lines between nodes in the chain: father->mother1->mother2->...
                                const marriageLines = g.selectAll('line.marriage')
                                    .data(motherFlat, d => d.parentId + '_marriage_' + (d.mother.index ?? (d.mother.data ? d.mother.data.id : Math.random())));

                                marriageLines.enter().append('line')
                                    .attr('class', 'marriage')
                                    .attr('stroke', '#94a3b8')
                                    .attr('stroke-width', 2)
                                    .attr('x1', d => {
                                        // If this is first mother (index 0), connect from father
                                        if (d.mother.index === 0) {
                                            return d.node.father.x + nodeRight; // From right edge of father node
                                        } else {
                                            // Connect from previous mother (right edge)
                                            const prevMotherIndex = d.mother.index - 1;
                                            const prevMother = d.node.mothers[prevMotherIndex];
                                            return prevMother ? prevMother.x + nodeRight : d.node.father.x + nodeRight;
                                        }
                                    })
                                    .attr('y1', d => d.mother.y) // Center vertical
                                    .attr('x2', d => d.mother.x + nodeLeft) // To left edge of current mother node
                                    .attr('y2', d => d.mother.y); // Center vertical

                                marriageLines.transition()
                                    .duration(750)
                                    .attr('x1', d => {
                                        if (d.mother.index === 0) {
                                            return d.node.father.x + nodeRight;
                                        } else {
                                            const prevMotherIndex = d.mother.index - 1;
                                            const prevMother = d.node.mothers[prevMotherIndex];
                                            return prevMother ? prevMother.x + nodeRight : d.node.father.x + nodeRight;
                                        }
                                    })
                                    .attr('y1', d => d.mother.y)
                                    .attr('x2', d => d.mother.x + nodeLeft)
                                    .attr('y2', d => d.mother.y);

                                // Remove exiting nodes
                                node.exit().transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${source.x},${source.y})`)
                                    .remove();

                                fatherNode.exit().transition()
                                    .duration(750)
                                    .remove();

                                motherNode.exit().transition()
                                    .duration(750)
                                    .remove();

                                nodes.forEach(d => {
                                    d.x0 = d.x;
                                    d.y0 = d.y;
                                });
                            }

                            // Center root horizontally
                            root.x0 = height / 2;
                            root.y0 = 60;

                            update(root);

                        } else {
                            container.innerHTML = '<div class="empty-state"><p>D3.js library tidak dimuat. Silakan refresh halaman.</p></div>';
                        }
                    });
                </script>
            @endif

            <!-- Enhanced Add Member Modal -->
            <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-md p-6">
                        <button onclick="closeAddModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Tambah Anggota Keluarga</h3>
                            <p class="text-gray-600 mt-2">Lengkapi informasi anggota keluarga baru</p>
                        </div>

                        <form id="addForm" action="{{ route('user.family.members.store', $family) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div>
                                <label for="add_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="add_name" required value="{{ old('name') }}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK (Nomor
                                    Induk Kependudukan)</label>
                                <input type="text" name="nik" id="add_nik" maxlength="16" pattern="[0-9]{16}"
                                    value="{{ old('nik') }}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Masukkan 16 digit NIK">
                                <p class="mt-1 text-sm text-gray-500">NIK digunakan untuk menghubungkan anggota keluarga
                                    secara otomatis</p>
                                @error('nik')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_relation" class="block text-sm font-semibold text-gray-700 mb-2">Hubungan
                                    Keluarga</label>
                                @php
                                    $hasMembers = $familyMembers->count() > 0;
                                    $hasFather = $familyMembers->where('relation', 'father')->count() > 0;
                                @endphp
                                <select name="relation" id="add_relation" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="father" {{ old('relation', !$hasMembers ? 'father' : '') == 'father' ? 'selected' : '' }} {{ $hasFather ? 'disabled' : '' }}>Ayah
                                    </option>
                                    <option value="mother" {{ old('relation') == 'mother' ? 'selected' : '' }} {{ !$hasMembers ? 'disabled' : '' }}>Ibu
                                    </option>
                                    <option value="child" {{ old('relation') == 'child' ? 'selected' : '' }} {{ !$hasMembers ? 'disabled' : '' }}>Anak
                                    </option>
                                </select>
                                @if(!$hasMembers)
                                    <p class="mt-1 text-sm text-blue-600">Anggota pertama harus seorang ayah.</p>
                                @elseif($hasFather)
                                    <p class="mt-1 text-sm text-gray-500">Ayah sudah ada. Anda dapat menambahkan ibu atau anak.</p>
                                @endif
                                @error('relation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Field "Anak Dari" - hanya muncul ketika relation = "child" -->
                            <div id="parent_mother_field" style="display: none;">
                                <label for="add_parent_id" class="block text-sm font-semibold text-gray-700 mb-2">Anak Dari</label>
                                <select name="parent_id" id="add_parent_id"
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Ibu</option>
                                    @foreach($familyMembers->where('relation', 'mother') as $mother)
                                        <option value="{{ $mother->id }}" {{ old('parent_id') == $mother->id ? 'selected' : '' }}>
                                            {{ $mother->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Pilih ibu untuk menghubungkan anak dengan ibu yang bersangkutan</p>
                                @error('parent_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Field "Anak Ke-" - hanya muncul ketika relation = "child" -->
                            <div id="child_order_field" style="display: none;">
                                <label for="add_child_order" class="block text-sm font-semibold text-gray-700 mb-2">Anak Ke-</label>
                                <input type="number" name="child_order" id="add_child_order" min="1" 
                                    value="{{ old('child_order') }}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Masukkan urutan anak (1, 2, 3, ...)">
                                <p class="mt-1 text-sm text-gray-500">Urutan ini menentukan posisi tampilan anak di diagram (1 = paling kiri)</p>
                                @error('child_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_gender" class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                    Kelamin</label>
                                @php
                                    $hasMembers = $familyMembers->count() > 0;
                                    $hasFather = $familyMembers->where('relation', 'father')->count() > 0;
                                    $defaultGender = old('gender', !$hasMembers ? 'male' : '');
                                @endphp
                                <!-- Hidden input untuk memastikan value terkirim saat disabled -->
                                <input type="hidden" name="gender" id="add_gender_hidden" value="{{ $defaultGender }}">
                                <select id="add_gender" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" {{ $defaultGender == 'male' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="female" {{ $defaultGender == 'female' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500" id="gender-hint">Pilih hubungan keluarga terlebih dahulu</p>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_birth_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal
                                    Lahir</label>
                                <input type="date" name="birth_date" id="add_birth_date" required
                                    value="{{ old('birth_date') }}" class="form-input w-full px-4 py-3 text-gray-900">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_description"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="add_description" rows="3"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Ceritakan tentang anggota keluarga ini...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="add_photo" class="block text-sm font-semibold text-gray-700 mb-2">Foto</label>
                                <input type="file" name="photo" id="add_photo" accept="image/*"
                                    class="form-input w-full px-4 py-3 text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <div class="mt-4 flex justify-center">
                                    <div class="preview-avatar-add">
                                        <div class="family-avatar-placeholder">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM12 5c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 19.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-4 pt-4">
                                <button type="button" onclick="closeAddModal()"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 btn-primary text-white">
                                    Tambah Anggota
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Edit Member Modal -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-md p-6">
                        <button onclick="closeEditModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Edit Anggota Keluarga</h3>
                            <p class="text-gray-600 mt-2">Perbarui informasi anggota keluarga</p>
                        </div>

                        <form id="editForm"
                            action="{{ route('user.family.members.update', [$family, $selectedMember ?? '']) }}"
                            method="POST" class="space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="member_id" id="edit_member_id"
                                value="{{ $selectedMember->id ?? '' }}">

                            <div>
                                <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="edit_name" required
                                    value="{{ $selectedMember->name ?? '' }}"
                                    class="form-input w-full px-4 py-3 text-gray-900">
                            </div>

                            <div>
                                <label for="edit_nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK (Nomor
                                    Induk Kependudukan)</label>
                                <input type="text" name="nik" id="edit_nik" maxlength="16" pattern="[0-9]{16}"
                                    value="{{ $selectedMember->nik ?? '' }}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Masukkan 16 digit NIK">
                                <p class="mt-1 text-sm text-gray-500">NIK digunakan untuk menghubungkan anggota keluarga
                                    secara otomatis</p>
                            </div>

                            <div>
                                <label for="edit_relation" class="block text-sm font-semibold text-gray-700 mb-2">Hubungan
                                    Keluarga</label>
                                <select name="relation" id="edit_relation" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="father"
                                        {{ ($selectedMember->relation ?? '') == 'father' ? 'selected' : '' }}>
                                        Ayah</option>
                                    <option value="mother"
                                        {{ ($selectedMember->relation ?? '') == 'mother' ? 'selected' : '' }}>
                                        Ibu</option>
                                    <option value="child"
                                        {{ ($selectedMember->relation ?? '') == 'child' ? 'selected' : '' }}>
                                        Anak</option>
                                </select>
                            </div>

                            <!-- Field "Anak Ke-" - hanya muncul ketika relation = "child" -->
                            <div id="edit_child_order_field" style="display: {{ ($selectedMember->relation ?? '') == 'child' ? 'block' : 'none' }};">
                                <label for="edit_child_order" class="block text-sm font-semibold text-gray-700 mb-2">Anak Ke-</label>
                                <input type="number" name="child_order" id="edit_child_order" min="1" 
                                    value="{{ $selectedMember->child_order ?? old('child_order') }}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Masukkan urutan anak (1, 2, 3, ...)">
                                <p class="mt-1 text-sm text-gray-500">Urutan ini menentukan posisi tampilan anak di diagram (1 = paling kiri)</p>
                                @error('child_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="edit_gender" class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                    Kelamin</label>
                                <select name="gender" id="edit_gender" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male"
                                        {{ ($selectedMember->gender ?? '') == 'male' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="female"
                                        {{ ($selectedMember->gender ?? '') == 'female' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="edit_birth_date"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Tanggal
                                    Lahir</label>
                                <input type="date" name="birth_date" id="edit_birth_date" required
                                    value="{{ $selectedMember->birth_date ?? '' }}"
                                    class="form-input w-full px-4 py-3 text-gray-900">
                            </div>

                            <div>
                                <label for="edit_description"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="edit_description" rows="3"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Ceritakan tentang anggota keluarga ini...">{{ $selectedMember->description ?? '' }}</textarea>
                            </div>

                            <div>
                                <label for="edit_photo"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Foto</label>
                                <input type="file" name="photo" id="edit_photo" accept="image/*"
                                    class="form-input w-full px-4 py-3 text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <div class="mt-4 flex justify-center">
                                    <div class="preview-avatar-edit">
                                        @if (isset($selectedMember) && $selectedMember->photo)
                                            <img src="{{ asset('storage/' . $selectedMember->photo) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="family-avatar-placeholder">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM12 5c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 19.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 text-center">
                                    Biarkan kosong jika tidak ingin mengubah foto
                                </p>
                            </div>

                            <div class="flex space-x-4 pt-4">
                                <button type="button" onclick="closeEditModal()"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 btn-primary text-white">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Member Detail Modal -->
            <div id="detailModal"
                class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-lg p-6">
                        <button onclick="closeDetailModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Detail Anggota Keluarga</h3>
                            <p class="text-gray-600 mt-2">Informasi lengkap anggota keluarga</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Photo Section -->
                            <div class="flex justify-center">
                                <div id="detail-photo"
                                    class="w-32 h-32 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                    <!-- Photo will be inserted here -->
                                </div>
                            </div>

                            <!-- Information Grid -->
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                                    <p id="detail-name" class="text-lg font-medium text-gray-900">-</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">NIK</label>
                                        <p id="detail-nik" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Hubungan</label>
                                        <p id="detail-relation" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Kelamin</label>
                                        <p id="detail-gender" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Lahir</label>
                                        <p id="detail-birth-date" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Usia</label>
                                        <p id="detail-age" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Deskripsi</label>
                                    <p id="detail-description" class="text-gray-900 leading-relaxed">-</p>
                                </div>

                                <div id="detail-death-section" class="bg-red-50 rounded-xl p-4 hidden">
                                    <label class="block text-sm font-semibold text-red-600 mb-1">Tanggal Wafat</label>
                                    <p id="detail-death-date" class="text-lg font-medium text-red-900">-</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-4 pt-4">
                                <button type="button" onclick="closeDetailModal()"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                    Tutup
                                </button>
                                <button type="button" id="detail-edit-btn" class="flex-1 btn-primary text-white">
                                    Edit Informasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Function to show member detail
                function showMemberDetail(member) {
                    // Update photo
                    const photoDiv = document.getElementById('detail-photo');
                    if (member.photo) {
                        photoDiv.innerHTML =
                            `<img src="/storage/${member.photo}" class="w-full h-full object-cover" alt="${member.name}">`;
                    } else {
                        const defaultAvatar = member.gender === 'male' ? '/images/male-avatar.svg' : '/images/female-avatar.svg';
                        photoDiv.innerHTML = `<img src="${defaultAvatar}" class="w-full h-full object-cover" alt="${member.name}">`;
                    }

                    // Update information
                    document.getElementById('detail-name').textContent = member.name;
                    document.getElementById('detail-nik').textContent = member.nik || '-';
                    document.getElementById('detail-relation').textContent =
                        member.relation === 'father' ? 'Ayah' :
                        member.relation === 'mother' ? 'Ibu' : 'Anak';
                    document.getElementById('detail-gender').textContent =
                        member.gender === 'male' ? 'Laki-laki' : 'Perempuan';

                    // Format birth date
                    const birthDate = new Date(member.birth_date);
                    const formattedBirthDate = birthDate.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    document.getElementById('detail-birth-date').textContent = formattedBirthDate;

                    // Calculate and display age
                    const today = new Date();
                    const endDate = member.death_date ? new Date(member.death_date) : today;
                    const age = Math.floor((endDate - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
                    document.getElementById('detail-age').textContent = age + ' tahun';

                    // Update description
                    document.getElementById('detail-description').textContent = member.description || 'Tidak ada deskripsi';

                    // Handle death date
                    const deathSection = document.getElementById('detail-death-section');
                    if (member.death_date) {
                        const deathDate = new Date(member.death_date);
                        const formattedDeathDate = deathDate.toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        document.getElementById('detail-death-date').textContent = formattedDeathDate;
                        deathSection.classList.remove('hidden');
                    } else {
                        deathSection.classList.add('hidden');
                    }

                    // Set up edit button
                    const editBtn = document.getElementById('detail-edit-btn');
                    editBtn.onclick = () => {
                        closeDetailModal();
                        // Trigger edit modal
                        document.getElementById('edit_member_id').value = member.id;
                        document.getElementById('edit_name').value = member.name;
                        document.getElementById('edit_relation').value = member.relation;
                        document.getElementById('edit_gender').value = member.gender;
                        const birthDateFormatted = new Date(member.birth_date).toISOString().split('T')[0];
                        document.getElementById('edit_birth_date').value = birthDateFormatted;
                        
                        // Show/hide child_order field based on relation
                        const editChildOrderField = document.getElementById('edit_child_order_field');
                        const editChildOrderInput = document.getElementById('edit_child_order');
                        if (editChildOrderField && editChildOrderInput) {
                            if (member.relation === 'child') {
                                editChildOrderField.style.display = 'block';
                                editChildOrderInput.value = member.child_order || '';
                            } else {
                                editChildOrderField.style.display = 'none';
                                editChildOrderInput.value = '';
                            }
                        }
                        document.getElementById('edit_description').value = member.description || '';

                        const form = document.getElementById('editForm');
                        form.action = `/user/family/${member.family_id}/members/${member.id}`;

                        const previewDiv = document.querySelector('.preview-avatar-edit');
                        if (member.photo) {
                            previewDiv.innerHTML = `<img src="/storage/${member.photo}" class="w-full h-full object-cover">`;
                        } else {
                            const defaultAvatar = member.gender === 'male' ? '/images/male-avatar.svg' :
                                '/images/female-avatar.svg';
                            previewDiv.innerHTML = `<img src="${defaultAvatar}" class="w-full h-full object-cover">`;
                        }

                        document.getElementById('editModal').classList.remove('hidden');
                    };

                    // Show modal
                    document.getElementById('detailModal').classList.remove('hidden');
                }

                // Function to close Detail Modal
                function closeDetailModal() {
                    document.getElementById('detailModal').classList.add('hidden');
                }

                // Function to open Add Modal
                function openAddModal() {
                    document.getElementById('addModal').classList.remove('hidden');
                    // Ensure gender is set based on current relation selection
                    setTimeout(function() {
                        updateGenderBasedOnRelation();
                    }, 100);
                }

                // Function to close Add Modal
                function closeAddModal() {
                    document.getElementById('addModal').classList.add('hidden');
                    document.getElementById('addForm').reset();
                    const preview = document.querySelector('.preview-avatar-add');
                    if (preview) {
                        preview.innerHTML = `<div class="family-avatar-placeholder">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM12 5c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 19.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                    </svg>
                </div>`;
                    }
                    // Reset gender field state
                    const genderSelect = document.getElementById('add_gender');
                    if (genderSelect) {
                        genderSelect.disabled = false;
                    }
                }

                // Override the button click to ensure gender is set when modal opens
                const originalButtonClick = window.onclick;
                document.addEventListener('click', function(e) {
                    // Check if the clicked element opens the add modal
                    if (e.target.closest('button[onclick*="addModal"]') || 
                        e.target.closest('button')?.onclick?.toString().includes('addModal')) {
                        setTimeout(function() {
                            updateGenderBasedOnRelation();
                        }, 200);
                    }
                });

                // Function to close Edit Modal
                function closeEditModal() {
                    document.getElementById('editModal').classList.add('hidden');
                    document.getElementById('editForm').reset();
                    const preview = document.querySelector('.preview-avatar-edit');
                    if (preview) {
                        preview.innerHTML = `<div class="family-avatar-placeholder">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM12 5c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 19.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                    </svg>
                </div>`;
                    }
                }

                // Auto-set gender based on relation selection
                function updateGenderBasedOnRelation() {
                    const relationSelect = document.getElementById('add_relation');
                    const genderSelect = document.getElementById('add_gender');
                    const genderHidden = document.getElementById('add_gender_hidden');
                    const genderHint = document.getElementById('gender-hint');

                    if (!relationSelect || !genderSelect || !genderHidden || !genderHint) return;

                    const relation = relationSelect.value;
                    
                    if (relation === 'father') {
                        genderSelect.value = 'male';
                        genderHidden.value = 'male';
                        genderSelect.disabled = true;
                        genderHint.textContent = 'Ayah otomatis berjenis kelamin Laki-laki';
                        genderHint.className = 'mt-1 text-sm text-gray-500';
                    } else if (relation === 'mother') {
                        genderSelect.value = 'female';
                        genderHidden.value = 'female';
                        genderSelect.disabled = true;
                        genderHint.textContent = 'Ibu otomatis berjenis kelamin Perempuan';
                        genderHint.className = 'mt-1 text-sm text-gray-500';
                    } else if (relation === 'child') {
                        genderSelect.disabled = false;
                        if (!genderSelect.value) {
                            genderSelect.value = '';
                            genderHidden.value = '';
                        } else {
                            genderHidden.value = genderSelect.value;
                        }
                        genderHint.textContent = 'Pilih jenis kelamin anak';
                        genderHint.className = 'mt-1 text-sm text-gray-500';
                    } else {
                        genderSelect.value = '';
                        genderHidden.value = '';
                        genderSelect.disabled = false;
                        genderHint.textContent = 'Pilih hubungan keluarga terlebih dahulu';
                        genderHint.className = 'mt-1 text-sm text-gray-500';
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const relationSelect = document.getElementById('add_relation');
                    const genderSelect = document.getElementById('add_gender');
                    const genderHidden = document.getElementById('add_gender_hidden');

                    if (relationSelect && genderSelect && genderHidden) {
                        // Set initial state when page loads
                        updateGenderBasedOnRelation();
                        updateParentMotherField();

                        // Update when relation changes
                        relationSelect.addEventListener('change', function() {
                            updateGenderBasedOnRelation();
                            updateParentMotherField();
                        });

                        // Update hidden input when gender select changes (for child)
                        genderSelect.addEventListener('change', function() {
                            if (!genderSelect.disabled) {
                                genderHidden.value = this.value;
                            }
                        });
                    }

                    // Function to show/hide "Anak Dari" and "Anak Ke-" fields
                    function updateParentMotherField() {
                        const relationSelect = document.getElementById('add_relation');
                        const parentMotherField = document.getElementById('parent_mother_field');
                        const childOrderField = document.getElementById('child_order_field');
                        const parentIdSelect = document.getElementById('add_parent_id');
                        const childOrderInput = document.getElementById('add_child_order');
                        
                        if (relationSelect && parentMotherField && childOrderField && parentIdSelect && childOrderInput) {
                            if (relationSelect.value === 'child') {
                                parentMotherField.style.display = 'block';
                                childOrderField.style.display = 'block';
                                parentIdSelect.required = true;
                            } else {
                                parentMotherField.style.display = 'none';
                                childOrderField.style.display = 'none';
                                parentIdSelect.required = false;
                                parentIdSelect.value = '';
                                childOrderInput.value = '';
                            }
                        }
                    }

                    // Also update when modal is opened (in case it's opened via button click)
                    const addModal = document.getElementById('addModal');
                    if (addModal) {
                        // Use MutationObserver to detect when modal is shown
                        const observer = new MutationObserver(function(mutations) {
                            mutations.forEach(function(mutation) {
                                if (!addModal.classList.contains('hidden')) {
                                    // Modal is now visible, update gender and parent field
                                    setTimeout(function() {
                                        updateGenderBasedOnRelation();
                                        updateParentMotherField();
                                    }, 100);
                                }
                            });
                        });
                        observer.observe(addModal, { attributes: true, attributeFilter: ['class'] });
                    }

                    // Close modals when clicking outside
                    window.addEventListener('click', function(event) {
                        const modals = ['addModal', 'editModal', 'detailModal'];
                        modals.forEach(modalId => {
                            const modal = document.getElementById(modalId);
                            if (event.target === modal) {
                                if (modalId === 'addModal') {
                                    closeAddModal();
                                } else if (modalId === 'editModal') {
                                    closeEditModal();
                                } else if (modalId === 'detailModal') {
                                    closeDetailModal();
                                }
                            }
                        });
                    });

                    // Add event listener for edit photo preview
                    const editPhotoInput = document.getElementById('edit_photo');
                    if (editPhotoInput) {
                        editPhotoInput.addEventListener('change', function() {
                            previewImage(this, '.preview-avatar-edit');
                        });
                    }

                    // Add event listener for edit relation change to show/hide child_order field
                    const editRelationSelect = document.getElementById('edit_relation');
                    const editChildOrderField = document.getElementById('edit_child_order_field');
                    const editChildOrderInput = document.getElementById('edit_child_order');
                    if (editRelationSelect && editChildOrderField && editChildOrderInput) {
                        editRelationSelect.addEventListener('change', function() {
                            if (this.value === 'child') {
                                editChildOrderField.style.display = 'block';
                            } else {
                                editChildOrderField.style.display = 'none';
                                editChildOrderInput.value = '';
                            }
                        });
                    }
                });
            </script>
        @endsection
