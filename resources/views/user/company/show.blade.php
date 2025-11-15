@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-blue-500 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">{{ $company->company_name }}</h1>
                            <p class="text-blue-100">Struktur Perusahaan</p>
                        </div>
                        <div class="flex space-x-4">
                            <button onclick="document.getElementById('addCompanyModal').classList.remove('hidden')"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Anggota Perusahaan
                            </button>
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

            <!-- Company Visualization -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Struktur Perusahaan</h2>
                            <p class="text-gray-600">{{ $company->description }}</p>
                        </div>
                    </div>

                    <div id="family-tree"
                        class="rounded-2xl p-8 bg-gradient-to-br from-gray-50 to-blue-50 border border-gray-100">
                        <div class="family-tree-container" id="family-tree-container" style="width: 100%; height: 800px; position: relative; overflow: auto;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Styles -->
            <style>
                .family-tree-container {
                    padding: 30px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    min-height: 400px;
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

                .modal-content {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
                    border: 1px solid #e5e7eb;
                }

                .form-input {
                    border-radius: 12px;
                    border: 2px solid #e5e7eb;
                    transition: all 0.3s ease;
                }

                .form-input:focus {
                    border-color: #6366f1;
                    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                }

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
            </style>

            @if(isset($treeJson) && $treeJson)
                <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
                <script>
                    // Tree JSON passed from server for company
                    const treeData = {!! $treeJson !!};
                    const members = @json($members);

                    // Define global functions before DOMContentLoaded
                    window.showCompanyMemberDetail = function(member) {
                        // Update photo
                        const photoDiv = document.getElementById('company-detail-photo');
                        if (member.photo) {
                            photoDiv.innerHTML =
                                `<img src="/storage/${member.photo}" class="w-full h-full object-cover" alt="${member.name}">`;
                        } else {
                            const defaultAvatar = member.gender === 'male' ? '/images/male-avatar.svg' : '/images/female-avatar.svg';
                            photoDiv.innerHTML = `<img src="${defaultAvatar}" class="w-full h-full object-cover" alt="${member.name}">`;
                        }

                        // Update information
                        document.getElementById('company-detail-name').textContent = member.name || '-';
                        document.getElementById('company-detail-nik').textContent = member.nik || '-';
                        document.getElementById('company-detail-position').textContent = member.position || '-';
                        document.getElementById('company-detail-gender').textContent =
                            member.gender === 'male' ? 'Laki-laki' : 'Perempuan';

                        // Format birth date
                        if (member.birth_date) {
                            const birthDate = new Date(member.birth_date);
                            const formattedBirthDate = birthDate.toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                            document.getElementById('company-detail-birth-date').textContent = formattedBirthDate;

                            // Calculate and display age
                            const today = new Date();
                            const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
                            document.getElementById('company-detail-age').textContent = age + ' tahun';
                        } else {
                            document.getElementById('company-detail-birth-date').textContent = '-';
                            document.getElementById('company-detail-age').textContent = '-';
                        }

                        // Update description
                        document.getElementById('company-detail-description').textContent = member.description || 'Tidak ada deskripsi';

                        // Set up edit button
                        const editBtn = document.getElementById('company-detail-edit-btn');
                        if (editBtn) {
                            editBtn.onclick = () => {
                                window.closeCompanyDetailModal();
                                window.openEditCompanyModal(member.id);
                            };
                        }

                        // Show modal
                        document.getElementById('companyDetailModal').classList.remove('hidden');
                    };

                    window.handleDeleteCompanyMember = function(memberId, companyId) {
                        if (confirm('Apakah Anda yakin ingin menghapus anggota perusahaan ini?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/user/company/${companyId}/members/${memberId}`;
                            const token = document.querySelector('meta[name="csrf-token"]').content;
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            const tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = '_token';
                            tokenInput.value = token;
                            form.appendChild(methodInput);
                            form.appendChild(tokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    };

                    window.closeCompanyDetailModal = function() {
                        document.getElementById('companyDetailModal').classList.add('hidden');
                    };

                    window.openAddChildModal = function(parentId) {
                        const form = document.getElementById('addCompanyForm');
                        form.action = `/user/company/{{ $company->id }}/members/${parentId}/add-child`;
                        document.getElementById('addCompanyModal').classList.remove('hidden');
                    };

                    window.openEditCompanyModal = function(memberId) {
                        const member = members.find(m => m.id == memberId);
                        if (!member) return;
                        
                        document.getElementById('edit_company_name').value = member.name || '';
                        document.getElementById('edit_company_nik').value = member.nik || '';
                        document.getElementById('edit_company_gender').value = member.gender || '';
                        if (member.birth_date) {
                            const birthDate = new Date(member.birth_date);
                            document.getElementById('edit_company_birth_date').value = birthDate.toISOString().split('T')[0];
                        }
                        document.getElementById('edit_company_position').value = member.position || '';
                        document.getElementById('edit_company_description').value = member.description || '';
                        
                        const form = document.getElementById('editCompanyForm');
                        form.action = `/user/company/{{ $company->id }}/members/${memberId}`;
                        
                        const previewDiv = document.querySelector('#editCompanyModal .preview-avatar-edit');
                        const photoUrl = member.photo ? `/storage/${member.photo}` : (member.gender === 'female' ? '/images/female-avatar.svg' : '/images/male-avatar.svg');
                        previewDiv.innerHTML = `<img src="${photoUrl}" class="w-full h-full object-cover">`;
                        
                        document.getElementById('editCompanyModal').classList.remove('hidden');
                    };

                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('family-tree-container');

                        if (!members || members.length === 0) {
                            container.innerHTML = `
                                <div class="empty-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold mb-2">Belum Ada Anggota Perusahaan</h3>
                                    <p class="mb-6">Mulai membangun struktur perusahaan Anda dengan menambahkan anggota pertama</p>
                                    <button onclick="document.getElementById('addCompanyModal').classList.remove('hidden')" class="btn-primary text-white">
                                        Tambah Anggota Pertama
                                    </button>
                                </div>
                            `;
                            return;
                        }

                        if (!treeData || typeof treeData !== 'object') {
                            container.innerHTML = '<div class="empty-state"><p>Data pohon perusahaan tidak valid</p></div>';
                            return;
                        }

                        // Create member lookup map
                        const membersMap = {};
                        members.forEach(m => {
                            if (m.nik) membersMap[m.nik] = m;
                            membersMap[m.name] = m;
                            membersMap[m.id] = m;
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
                            // Use position if available, otherwise use role or relation
                            if (member.position) {
                                return member.position;
                            }
                            const key = member.role || member.relation || 'anggota';
                            const label = mapRoleLabel(key);
                            return label;
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
                            return nodeData;
                        }

                        function getDefaultAvatar(gender) {
                            return gender === 'female' ? '/images/female-avatar.svg' : '/images/male-avatar.svg';
                        }

                        // Enhanced D3 tree for company
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

                            const processedTreeData = JSON.parse(JSON.stringify(treeData));
                            const root = d3.hierarchy(processedTreeData, d => d.children || []);
                            root.x0 = width / 2;
                            root.y0 = 60;

                            // Node card size: 180px width x 280px height
                            const nodeGapX = 500; // Horizontal gap between nodes
                            const siblingGapX = 250; // Horizontal gap between siblings
                            const levelGapY = 500; // Vertical gap between levels
                            const treeLayout = d3.tree().nodeSize([nodeGapX, levelGapY]);
                            
                            // Node edge positions for link connections
                            const nodeWidth = 180;
                            const nodeHeight = 280;
                            const nodeTop = -140;
                            const nodeBottom = 140;
                            const nodeLeft = -90;
                            const nodeRight = 90;

                            // Helper function to truncate name
                            function truncateName(name) {
                                if (!name) return '';
                                if (name.length <= 15) return name;
                                return name.substring(0, 15) + '...';
                            }

                            // Create HTML node for company member
                            function createCompanyMemberNodeHTML(member) {
                                const memberData = findMemberData(member);
                                const positionLabel = numberedLabelFor(memberData);
                                
                                const photoUrl = memberData.photo ? `/storage/${memberData.photo}` : getDefaultAvatar(memberData.gender || 'male');
                                const memberId = memberData.id || '';
                                const companyId = memberData.company_id || {{ $company->id }};
                                let memberName = (memberData.name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                                const truncatedName = truncateName(memberName);
                                
                                // Create safe JSON string
                                const memberJson = JSON.stringify(memberData).replace(/"/g, '&quot;');
                                
                                let html = `
                                    <div class="family-member" style="width: 180px; position: relative;" data-member-id="${memberId}" data-company-id="${companyId}">
                                        <div class="action-buttons-container">
                                            <button class="action-button view action-button-top-left" onclick="showCompanyMemberDetail(${memberJson})" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <button class="action-button add action-button-top-right" onclick="openAddChildModal(${memberId})" title="Tambah Child">+</button>
                                            <button class="action-button edit action-button-bottom-right" onclick="openEditCompanyModal(${memberId})" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button class="action-button delete action-button-bottom-left" onclick="handleDeleteCompanyMember(${memberId}, ${companyId})" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        <div class="family-member-photo" style="width: 140px; height: 140px; border-radius: 20px; overflow: hidden; margin: 0 auto 10px; background: linear-gradient(145deg, #f1f5f9, #e2e8f0); border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); position: relative;">
                                            <img src="${photoUrl}" alt="${memberName}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div style="text-align: center; margin-bottom: 6px;">
                                            <div class="family-member-name" style="font-weight: 600; color: #1f2937; font-size: 12px; text-align: center; background: white; padding: 4px 8px; border-radius: 4px; display: inline-block; max-width: 100%;">${truncatedName}</div>
                                        </div>
                                        <div style="text-align: center;">
                                            <div class="family-member-relation" style="color: #6b7280; font-size: 14px; font-weight: 500; background: #f3f4f6; padding: 4px 12px; border-radius: 12px; display: inline-block;">${positionLabel}</div>
                                        </div>
                                    </div>
                                `;
                                
                                return html;
                            }

                            function update(source) {
                                const treeRoot = treeLayout(root);
                                const nodes = treeRoot.descendants();
                                const links = treeRoot.links();

                                // Adjust positions for siblings
                                const childrenByParentId = new Map();
                                nodes.forEach(node => {
                                    if (node.data.type === 'person' && node.parent && node.parent.data) {
                                        const parentId = node.parent.data.id;
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
                                        siblings.sort((a, b) => a.x - b.x);
                                        const totalSiblingWidth = siblings.length * 180 + (siblings.length - 1) * siblingGapX;
                                        const centerX = siblings.reduce((sum, n) => sum + n.x, 0) / siblings.length;
                                        const startX = centerX - totalSiblingWidth / 2 + 90;
                                        siblings.forEach((sibling, index) => {
                                            sibling.x = startX + index * (180 + siblingGapX);
                                        });
                                    }
                                });

                                // Handle links
                                const link = g.selectAll('path.link')
                                    .data(links, d => {
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
                                        const sourceConnectY = d.source.y + nodeBottom;
                                        const targetConnectY = d.target.y + nodeTop;
                                        const midY = (sourceConnectY + targetConnectY) / 2;
                                        return `M ${d.source.x},${sourceConnectY} C ${d.source.x},${midY} ${d.target.x},${midY} ${d.target.x},${targetConnectY}`;
                                    });

                                // Handle person nodes
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
                                    .html(d => createCompanyMemberNodeHTML(d.data));

                                // Update person node positions
                                const personNodeUpdate = nodeEnter.merge(node);
                                personNodeUpdate.transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${d.x},${d.y})`);

                                // Remove exiting nodes
                                node.exit().transition()
                                    .duration(750)
                                    .attr('transform', d => `translate(${source.x},${source.y})`)
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

            <!-- Add Company Member Modal -->
            <div id="addCompanyModal"
                class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-md p-6">
                        <button onclick="closeAddCompanyModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                            &times;
                        </button>
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold">Tambah Anggota Perusahaan</h3>
                            <p class="text-gray-600">Tambahkan anggota sesuai peran</p>
                        </div>
                        <form id="addCompanyForm" action="{{ route('user.company.members.store', $company) }}"
                            method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="form-input w-full px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK (16 digit)</label>
                                <input type="text" name="nik" maxlength="16" pattern="[0-9]{16}" 
                                    placeholder="1234567890123456" class="form-input w-full px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="gender" required class="form-input w-full px-3 py-2">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" name="birth_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Posisi</label>
                                <div class="relative">
                                    <input type="text" name="position" id="add_company_position" 
                                        list="positions_list" placeholder="Masukkan atau pilih posisi" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900 placeholder-gray-400">
                                    <datalist id="positions_list">
                                        <!-- Will be populated by JavaScript -->
                                    </datalist>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Ketik untuk mencari atau pilih dari daftar yang tersedia</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto (opsional)</label>
                                <input type="file" name="photo" id="add_company_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <div class="mt-2 preview-avatar-add" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 2px solid #e5e7eb; background: #f3f4f6;"></div>
                            </div>
                            <div class="flex space-x-3 justify-end">
                                <button type="button" onclick="closeAddCompanyModal()"
                                    class="px-4 py-2 border rounded">Batal</button>
                                <button type="submit" class="px-4 py-2 btn-primary text-white">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Company Member Modal -->
            <div id="editCompanyModal"
                class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-md p-6">
                        <button onclick="closeEditCompanyModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">&times;</button>
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold">Edit Anggota Perusahaan</h3>
                        </div>
                        <form id="editCompanyForm" action="#" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="edit_company_name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK (16 digit)</label>
                                <input type="text" name="nik" id="edit_company_nik" maxlength="16" pattern="[0-9]{16}" 
                                    placeholder="1234567890123456" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900 placeholder-gray-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="gender" id="edit_company_gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" name="birth_date" id="edit_company_birth_date" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Posisi</label>
                                <div class="relative">
                                    <input type="text" name="position" id="edit_company_position" 
                                        list="edit_positions_list" placeholder="Masukkan atau pilih posisi" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900 placeholder-gray-400">
                                    <datalist id="edit_positions_list">
                                        <!-- Will be populated by JavaScript -->
                                    </datalist>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Ketik untuk mencari atau pilih dari daftar yang tersedia</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="edit_company_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto (opsional)</label>
                                <input type="file" name="photo" id="edit_company_photo" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <div class="mt-2 preview-avatar-edit" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 2px solid #e5e7eb; background: #f3f4f6;"></div>
                            </div>
                            <div class="flex space-x-3 justify-end">
                                <button type="button" onclick="closeEditCompanyModal()"
                                    class="px-4 py-2 border rounded">Batal</button>
                                <button type="submit" class="px-4 py-2 btn-primary text-white">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Company Member Detail Modal -->
            <div id="companyDetailModal"
                class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 backdrop-blur-sm">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content relative w-full max-w-lg p-6">
                        <button onclick="closeCompanyDetailModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-teal-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Detail Anggota Perusahaan</h3>
                            <p class="text-gray-600 mt-2">Informasi lengkap anggota perusahaan</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Photo Section -->
                            <div class="flex justify-center">
                                <div id="company-detail-photo"
                                    class="w-32 h-32 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                    <!-- Photo will be inserted here -->
                                </div>
                            </div>

                            <!-- Information Grid -->
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                                    <p id="company-detail-name" class="text-lg font-medium text-gray-900">-</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">NIK</label>
                                        <p id="company-detail-nik" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Posisi</label>
                                        <p id="company-detail-position" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Kelamin</label>
                                        <p id="company-detail-gender" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Lahir</label>
                                        <p id="company-detail-birth-date" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Usia</label>
                                        <p id="company-detail-age" class="text-lg font-medium text-gray-900">-</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Deskripsi</label>
                                    <p id="company-detail-description" class="text-gray-900 leading-relaxed">-</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-4 pt-4">
                                <button type="button" onclick="closeCompanyDetailModal()"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                    Tutup
                                </button>
                                <button type="button" id="company-detail-edit-btn" class="flex-1 btn-primary text-white">
                                    Edit Informasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Load positions list for company members
                function loadPositions() {
                    fetch(`/user/company/{{ $company->id }}/members/positions`)
                        .then(r => r.json())
                        .then(positions => {
                            const positionsList = document.getElementById('positions_list');
                            const editPositionsList = document.getElementById('edit_positions_list');
                            if (positionsList) {
                                positionsList.innerHTML = '';
                                positions.forEach(pos => {
                                    const option = document.createElement('option');
                                    option.value = pos;
                                    positionsList.appendChild(option);
                                });
                            }
                            if (editPositionsList) {
                                editPositionsList.innerHTML = '';
                                positions.forEach(pos => {
                                    const option = document.createElement('option');
                                    option.value = pos;
                                    editPositionsList.appendChild(option);
                                });
                            }
                        })
                        .catch(() => console.error('Failed to load positions'));
                }

                function closeAddCompanyModal() {
                    document.getElementById('addCompanyModal').classList.add('hidden');
                    document.getElementById('addCompanyForm').reset();
                    const form = document.getElementById('addCompanyForm');
                    form.action = '{{ route("user.company.members.store", $company) }}';
                }

                function closeEditCompanyModal() {
                    document.getElementById('editCompanyModal').classList.add('hidden');
                }

                // closeCompanyDetailModal is already defined in the D3.js script block above

                document.addEventListener('DOMContentLoaded', function() {
                    loadPositions();
                    
                    // Photo preview for add company form
                    const addPhotoInput = document.getElementById('add_company_photo');
                    if (addPhotoInput) {
                        addPhotoInput.addEventListener('change', function() {
                            const preview = document.querySelector('#addCompanyModal .preview-avatar-add');
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }

                    // Photo preview for edit company form
                    const editPhotoInput = document.getElementById('edit_company_photo');
                    if (editPhotoInput) {
                        editPhotoInput.addEventListener('change', function() {
                            const preview = document.querySelector('#editCompanyModal .preview-avatar-edit');
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>
@endsection

