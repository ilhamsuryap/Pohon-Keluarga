@extends('layouts.app')

@section('content')
    <style>
        html, body {
            overflow: hidden !important;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            position: fixed;
            width: 100vw;
            height: 100vh;
        }
        #app {
            overflow: hidden !important;
        }
    </style>
    
    <div class="bg-gradient-to-br from-teal-50 via-green-50 to-emerald-50 w-full" style="height: calc(100vh - 64px); display: flex; flex-direction: column; overflow: hidden; max-height: calc(100vh - 64px);">
        <!-- Header Bar with Identity -->
        <div class="bg-white border-b border-teal-200 shadow-sm px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Icon & Badge -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-green-600 flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 text-xs font-bold text-white rounded-full bg-gradient-to-r from-teal-500 to-green-600 shadow-md">
                                    PERUSAHAAN
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $company->company_name }}</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                <a href="{{ route('user.dashboard') }}" class="hover:text-teal-600">Dashboard</a>
                                <span class="mx-2">/</span>
                                <a href="{{ route('user.company.index') }}" class="hover:text-teal-600">Perusahaan</a>
                                <span class="mx-2">/</span>
                                <span class="text-gray-700">{{ Str::limit($company->company_name, 30) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Quick Actions -->
                <div class="flex items-center space-x-3" x-data="{ exportOpen: false }">
                    <a href="{{ route('user.company.edit', $company) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                    <!-- Export Dropdown -->
                    <div class="relative" @click.away="exportOpen = false">
                        <button @click="exportOpen = !exportOpen"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-green-600 text-white rounded-lg text-sm font-medium hover:from-teal-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="exportOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-50"
                             style="display: none;">
                            <button onclick="exportCompanyAsImage()"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Export sebagai Gambar
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Success/Error Messages -->
        @if (session('success'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-green-50 border border-green-200 rounded-2xl p-4 shadow-2xl max-w-md animate-fade-in-up">
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
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-50 border border-red-200 rounded-2xl p-4 shadow-2xl max-w-md animate-fade-in-up">
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
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-50 border border-red-200 rounded-2xl p-4 shadow-2xl max-w-md animate-fade-in-up">
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

        <!-- Diagram Container with thin margins -->
        <div class="flex-1 m-2 mb-2 bg-white rounded-2xl shadow-xl overflow-hidden relative" style="min-width: 0;">
            <div id="family-tree" class="w-full h-full" style="overflow: hidden;">
                <div class="family-tree-container" id="family-tree-container" style="width: 100%; height: 100%; position: relative; overflow: auto; overflow-x: auto; overflow-y: auto;">
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
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
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
                        
                        // Handle position - check if it's in the select options
                        const positionSelect = document.getElementById('edit_company_position');
                        const customPositionField = document.getElementById('edit_custom_position_field');
                        const customPositionInput = document.getElementById('edit_custom_position');
                        
                        if (member.position) {
                            // Check if position exists in select options
                            const positionOption = Array.from(positionSelect.options).find(
                                option => option.value === member.position
                            );
                            
                            if (positionOption) {
                                // Position is in the list
                                positionSelect.value = member.position;
                                if (customPositionField) customPositionField.style.display = 'none';
                                if (customPositionInput) customPositionInput.value = '';
                            } else {
                                // Position is not in the list, use custom
                                positionSelect.value = '==Custom==';
                                if (customPositionField) customPositionField.style.display = 'block';
                                if (customPositionInput) customPositionInput.value = member.position;
                            }
                        } else {
                            positionSelect.value = '';
                            if (customPositionField) customPositionField.style.display = 'none';
                            if (customPositionInput) customPositionInput.value = '';
                        }
                        
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
                                <div class="empty-state flex flex-col items-center justify-center h-full">
                                    <button onclick="document.getElementById('addCompanyModal').classList.remove('hidden')"
                                        class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-200 transform hover:scale-110 text-4xl font-bold">
                                        +
                                    </button>
                                    <h3 class="text-xl font-semibold mb-2 mt-4">Belum Ada Anggota Perusahaan</h3>
                                    <p class="mb-6">Mulai membangun struktur perusahaan Anda dengan menambahkan anggota pertama</p>
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
                            const width = container.clientWidth;
                            const height = container.clientHeight;

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
                                <select name="position" id="add_company_position" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                                    <option value="">Pilih Posisi</option>
                                    <optgroup label="Pimpinan">
                                        <option value="CEO / Direktur Utama">CEO / Direktur Utama</option>
                                        <option value="Direktur">Direktur</option>
                                        <option value="Direktur Operasional">Direktur Operasional</option>
                                        <option value="Direktur Keuangan">Direktur Keuangan</option>
                                        <option value="Direktur Pemasaran">Direktur Pemasaran</option>
                                        <option value="Direktur Sumber Daya Manusia">Direktur Sumber Daya Manusia</option>
                                    </optgroup>
                                    <optgroup label="Manajemen">
                                        <option value="General Manager">General Manager</option>
                                        <option value="Senior Manager">Senior Manager</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Manager Operasional">Manager Operasional</option>
                                        <option value="Manager Keuangan">Manager Keuangan</option>
                                        <option value="Manager Pemasaran">Manager Pemasaran</option>
                                        <option value="Manager Sumber Daya Manusia">Manager Sumber Daya Manusia</option>
                                        <option value="Manager IT">Manager IT</option>
                                        <option value="Manager Produksi">Manager Produksi</option>
                                    </optgroup>
                                    <optgroup label="Supervisi">
                                        <option value="Senior Supervisor">Senior Supervisor</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Supervisor Operasional">Supervisor Operasional</option>
                                        <option value="Supervisor Produksi">Supervisor Produksi</option>
                                        <option value="Supervisor Quality Control">Supervisor Quality Control</option>
                                    </optgroup>
                                    <optgroup label="Staf">
                                        <option value="Senior Staff">Senior Staff</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Staff Administrasi">Staff Administrasi</option>
                                        <option value="Staff Keuangan">Staff Keuangan</option>
                                        <option value="Staff Pemasaran">Staff Pemasaran</option>
                                        <option value="Staff Sumber Daya Manusia">Staff Sumber Daya Manusia</option>
                                        <option value="Staff IT">Staff IT</option>
                                        <option value="Staff Produksi">Staff Produksi</option>
                                        <option value="Staff Customer Service">Staff Customer Service</option>
                                        <option value="Staff Gudang">Staff Gudang</option>
                                    </optgroup>
                                    <optgroup label="Lainnya">
                                        <option value="Analyst">Analyst</option>
                                        <option value="Senior Analyst">Senior Analyst</option>
                                        <option value="Specialist">Specialist</option>
                                        <option value="Coordinator">Coordinator</option>
                                        <option value="Assistant">Assistant</option>
                                        <option value="Intern / Magang">Intern / Magang</option>
                                    </optgroup>
                                    <option value="==Custom==">==Custom==</option>
                                </select>
                                <div id="add_custom_position_field" style="display: none;" class="mt-2">
                                    <input type="text" name="custom_position" id="add_custom_position" 
                                        placeholder="Masukkan posisi custom" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900 placeholder-gray-400">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Pilih posisi dari daftar atau pilih ==Custom== untuk memasukkan posisi custom</p>
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
                                <select name="position" id="edit_company_position" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900">
                                    <option value="">Pilih Posisi</option>
                                    <optgroup label="Pimpinan">
                                        <option value="CEO / Direktur Utama">CEO / Direktur Utama</option>
                                        <option value="Direktur">Direktur</option>
                                        <option value="Direktur Operasional">Direktur Operasional</option>
                                        <option value="Direktur Keuangan">Direktur Keuangan</option>
                                        <option value="Direktur Pemasaran">Direktur Pemasaran</option>
                                        <option value="Direktur Sumber Daya Manusia">Direktur Sumber Daya Manusia</option>
                                    </optgroup>
                                    <optgroup label="Manajemen">
                                        <option value="General Manager">General Manager</option>
                                        <option value="Senior Manager">Senior Manager</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Manager Operasional">Manager Operasional</option>
                                        <option value="Manager Keuangan">Manager Keuangan</option>
                                        <option value="Manager Pemasaran">Manager Pemasaran</option>
                                        <option value="Manager Sumber Daya Manusia">Manager Sumber Daya Manusia</option>
                                        <option value="Manager IT">Manager IT</option>
                                        <option value="Manager Produksi">Manager Produksi</option>
                                    </optgroup>
                                    <optgroup label="Supervisi">
                                        <option value="Senior Supervisor">Senior Supervisor</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Supervisor Operasional">Supervisor Operasional</option>
                                        <option value="Supervisor Produksi">Supervisor Produksi</option>
                                        <option value="Supervisor Quality Control">Supervisor Quality Control</option>
                                    </optgroup>
                                    <optgroup label="Staf">
                                        <option value="Senior Staff">Senior Staff</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Staff Administrasi">Staff Administrasi</option>
                                        <option value="Staff Keuangan">Staff Keuangan</option>
                                        <option value="Staff Pemasaran">Staff Pemasaran</option>
                                        <option value="Staff Sumber Daya Manusia">Staff Sumber Daya Manusia</option>
                                        <option value="Staff IT">Staff IT</option>
                                        <option value="Staff Produksi">Staff Produksi</option>
                                        <option value="Staff Customer Service">Staff Customer Service</option>
                                        <option value="Staff Gudang">Staff Gudang</option>
                                    </optgroup>
                                    <optgroup label="Lainnya">
                                        <option value="Analyst">Analyst</option>
                                        <option value="Senior Analyst">Senior Analyst</option>
                                        <option value="Specialist">Specialist</option>
                                        <option value="Coordinator">Coordinator</option>
                                        <option value="Assistant">Assistant</option>
                                        <option value="Intern / Magang">Intern / Magang</option>
                                    </optgroup>
                                    <option value="==Custom==">==Custom==</option>
                                </select>
                                <div id="edit_custom_position_field" style="display: none;" class="mt-2">
                                    <input type="text" name="custom_position" id="edit_custom_position" 
                                        placeholder="Masukkan posisi custom" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white text-gray-900 placeholder-gray-400">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Pilih posisi dari daftar atau pilih ==Custom== untuk memasukkan posisi custom</p>
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
                // Handle custom position field visibility
                function handlePositionChange(positionSelectId, customFieldId, customInputId) {
                    const positionSelect = document.getElementById(positionSelectId);
                    const customField = document.getElementById(customFieldId);
                    const customInput = document.getElementById(customInputId);
                    
                    if (!positionSelect || !customField || !customInput) return;
                    
                    positionSelect.addEventListener('change', function() {
                        if (this.value === '==Custom==') {
                            customField.style.display = 'block';
                            customInput.required = true;
                            customInput.focus();
                        } else {
                            customField.style.display = 'none';
                            customInput.required = false;
                            customInput.value = '';
                        }
                    });
                }
                
                // Handle form submission for custom position
                function handlePositionSubmit(formId, positionSelectId, customInputId) {
                    const form = document.getElementById(formId);
                    const positionSelect = document.getElementById(positionSelectId);
                    const customInput = document.getElementById(customInputId);
                    
                    if (!form || !positionSelect || !customInput) return;
                    
                    form.addEventListener('submit', function(e) {
                        // Remove any existing hidden position input first
                        const existingHidden = form.querySelector('input[name="position"][type="hidden"]');
                        if (existingHidden) {
                            existingHidden.remove();
                        }
                        
                        if (positionSelect.value === '==Custom==') {
                            if (!customInput.value.trim()) {
                                e.preventDefault();
                                alert('Mohon masukkan posisi custom');
                                customInput.focus();
                                return false;
                            }
                            // Temporarily disable the select so it doesn't submit
                            positionSelect.disabled = true;
                            // Create hidden input with custom value
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'position';
                            hiddenInput.value = customInput.value.trim();
                            form.appendChild(hiddenInput);
                        } else {
                            // Ensure select is enabled
                            positionSelect.disabled = false;
                        }
                    });
                }

                function closeAddCompanyModal() {
                    document.getElementById('addCompanyModal').classList.add('hidden');
                    document.getElementById('addCompanyForm').reset();
                    // Reset custom position field visibility
                    const customField = document.getElementById('add_custom_position_field');
                    if (customField) customField.style.display = 'none';
                    // Re-enable select and remove any hidden inputs
                    const positionSelect = document.getElementById('add_company_position');
                    if (positionSelect) positionSelect.disabled = false;
                    const form = document.getElementById('addCompanyForm');
                    const existingHidden = form.querySelector('input[name="position"][type="hidden"]');
                    if (existingHidden) existingHidden.remove();
                    form.action = '{{ route("user.company.members.store", $company) }}';
                }

                function closeEditCompanyModal() {
                    document.getElementById('editCompanyModal').classList.add('hidden');
                    // Reset custom position field visibility
                    const customField = document.getElementById('edit_custom_position_field');
                    if (customField) customField.style.display = 'none';
                    // Re-enable select and remove any hidden inputs
                    const positionSelect = document.getElementById('edit_company_position');
                    if (positionSelect) positionSelect.disabled = false;
                    const form = document.getElementById('editCompanyForm');
                    const existingHidden = form.querySelector('input[name="position"][type="hidden"]');
                    if (existingHidden) existingHidden.remove();
                }

                // closeCompanyDetailModal is already defined in the D3.js script block above

                document.addEventListener('DOMContentLoaded', function() {
                    // Handle custom position for add form
                    handlePositionChange('add_company_position', 'add_custom_position_field', 'add_custom_position');
                    handlePositionSubmit('addCompanyForm', 'add_company_position', 'add_custom_position');
                    
                    // Handle custom position for edit form
                    handlePositionChange('edit_company_position', 'edit_custom_position_field', 'edit_custom_position');
                    handlePositionSubmit('editCompanyForm', 'edit_company_position', 'edit_custom_position');
                    
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

            <!-- html2canvas for image export -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                function exportCompanyAsImage() {
                    const container = document.getElementById('family-tree-container');
                    const companyName = '{{ $company->company_name }}';
                    
                    // Show loading indicator
                    const loading = document.createElement('div');
                    loading.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                    loading.innerHTML = '<div class="bg-white rounded-lg p-6"><p class="text-gray-700">Mengekspor diagram...</p></div>';
                    document.body.appendChild(loading);
                    
                    html2canvas(container, {
                        backgroundColor: '#ffffff',
                        scale: 2,
                        logging: false,
                        useCORS: true
                    }).then(canvas => {
                        // Create download link
                        const link = document.createElement('a');
                        link.download = companyName + '_struktur.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        
                        // Remove loading indicator
                        document.body.removeChild(loading);
                    }).catch(err => {
                        console.error('Export error:', err);
                        alert('Gagal mengekspor diagram. Silakan coba lagi.');
                        document.body.removeChild(loading);
                    });
                }
            </script>
        </div>
    </div>
@endsection

