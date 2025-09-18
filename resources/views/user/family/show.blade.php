@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">{{ $family->family_name }}</h1>
                            <p class="text-indigo-100">Silsilah Keluarga</p>
                        </div>
                        <div class="flex space-x-4">
                            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
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

            <!-- Family Tree Visualization -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Pohon Keluarga</h2>
                        <p class="text-gray-600">{{ $family->description }}</p>
                    </div>

                    <div id="family-tree"
                        class="rounded-2xl p-8 bg-gradient-to-br from-gray-50 to-blue-50 border border-gray-100">
                        <div class="family-tree-container">
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

            <script>
                // Initialize family tree visualization
                document.addEventListener('DOMContentLoaded', function() {
                    const members = @json($familyMembers);
                    const container = document.querySelector('.family-tree-container');

                    if (members.length === 0) {
                        container.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Anggota Keluarga</h3>
                        <p class="mb-6">Mulai membangun pohon keluarga Anda dengan menambahkan anggota pertama</p>
                        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-primary text-white">
                            Tambah Anggota Pertama
                        </button>
                    </div>
                `;
                        return;
                    }

                    // Group members by relation
                    const parents = members.filter(m => m.relation === 'father' || m.relation === 'mother');
                    const children = members.filter(m => m.relation === 'child');

                    // Create parents level
                    if (parents.length > 0) {
                        const parentsDiv = document.createElement('div');
                        parentsDiv.className = 'family-level';
                        parents.forEach(parent => {
                            parentsDiv.appendChild(createMemberNode(parent));
                        });
                        container.appendChild(parentsDiv);

                        // Add connector if there are children
                        if (children.length > 0) {
                            const connector = document.createElement('div');
                            connector.className = 'family-connector';
                            container.appendChild(connector);
                        }
                    }

                    // Create children level
                    if (children.length > 0) {
                        const childrenDiv = document.createElement('div');
                        childrenDiv.className = 'family-level';
                        children.forEach(child => {
                            childrenDiv.appendChild(createMemberNode(child));
                        });
                        container.appendChild(childrenDiv);
                    }
                });

                function createMemberNode(member) {
                    const node = document.createElement('div');
                    node.className = 'family-member';

                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'family-member-photo';

                    if (member.photo) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + member.photo;
                        img.alt = member.name;
                        photoDiv.appendChild(img);
                    } else {
                        // Default avatar based on gender
                        const img = document.createElement('img');
                        img.src = member.gender === 'male' ? '/images/male-avatar.svg' : '/images/female-avatar.svg';
                        img.alt = member.name;
                        photoDiv.appendChild(img);
                    }

                    // Add action buttons container
                    const actionsContainer = document.createElement('div');
                    actionsContainer.className = 'action-buttons-container';

                    // View Detail button (top left)
                    const viewButton = document.createElement('button');
                    viewButton.className = 'action-button view action-button-top-left';
                    viewButton.innerHTML =
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
                    viewButton.title = 'Lihat Detail';
                    viewButton.onclick = () => {
                        showMemberDetail(member);
                    };
                    actionsContainer.appendChild(viewButton);

                    // Add button (top right) - only show for father
                    if (member.relation === 'father') {
                        const addButton = document.createElement('button');
                        addButton.className = 'action-button add action-button-top-right';
                        addButton.innerHTML = '+';
                        addButton.title = 'Tambah Anggota';
                        addButton.onclick = () => {
                            document.getElementById('addModal').classList.remove('hidden');
                        };
                        actionsContainer.appendChild(addButton);
                    }

                    // Edit button (bottom right)
                    const editButton = document.createElement('button');
                    editButton.className = 'action-button edit action-button-bottom-right';
                    editButton.innerHTML =
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>';
                    editButton.title = 'Edit';
                    editButton.onclick = () => {
                        // Populate form with member data
                        document.getElementById('edit_member_id').value = member.id;
                        document.getElementById('edit_name').value = member.name;
                        document.getElementById('edit_nik').value = member.nik || '';
                        document.getElementById('edit_relation').value = member.relation;
                        document.getElementById('edit_gender').value = member.gender;
                        // Format birth_date to YYYY-MM-DD for input type="date"
                        const birthDate = new Date(member.birth_date);
                        const formattedDate = birthDate.toISOString().split('T')[0];
                        document.getElementById('edit_birth_date').value = formattedDate;
                        document.getElementById('edit_description').value = member.description || '';

                        // Update form action URL
                        const form = document.getElementById('editForm');
                        form.action = `/user/family/${member.family_id}/members/${member.id}`;

                        // Update preview photo if exists
                        const previewDiv = document.querySelector('.preview-avatar-edit');
                        if (member.photo) {
                            previewDiv.innerHTML = `<img src="/storage/${member.photo}" class="w-full h-full object-cover">`;
                        } else {
                            const defaultAvatar = member.gender === 'male' ? '/images/male-avatar.svg' :
                                '/images/female-avatar.svg';
                            previewDiv.innerHTML = `<img src="${defaultAvatar}" class="w-full h-full object-cover">`;
                        }

                        // Show modal
                        document.getElementById('editModal').classList.remove('hidden');
                    };
                    actionsContainer.appendChild(editButton);

                    // Delete button (bottom left)
                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'action-button delete action-button-bottom-left';
                    deleteButton.innerHTML =
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                    deleteButton.title = 'Hapus';
                    deleteButton.onclick = () => {
                        if (confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/user/family/${member.family_id}/members/${member.id}`;
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
                    actionsContainer.appendChild(deleteButton);

                    node.appendChild(actionsContainer);

                    // Add badge if member is marked as "widor"
                    if (member.nickname === 'widor') {
                        const badge = document.createElement('div');
                        badge.className = 'family-member-badge';
                        badge.textContent = 'widor';
                        node.appendChild(badge);
                    }

                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'family-member-name';
                    nameDiv.textContent = member.name;

                    const relationDiv = document.createElement('div');
                    relationDiv.className = 'family-member-relation';
                    relationDiv.textContent = member.relation === 'father' ? 'Ayah' :
                        member.relation === 'mother' ? 'Ibu' : 'Anak';

                    node.appendChild(photoDiv);
                    node.appendChild(nameDiv);
                    node.appendChild(relationDiv);

                    return node;
                }

                function previewImage(input, previewClass = '.preview-avatar') {
                    const preview = document.querySelector(previewClass);
                    const file = input.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                            const genderInput = input.closest('form').querySelector('select[name="gender"]');
                            if (genderInput) {
                                preview.classList.remove('male', 'female');
                                preview.classList.add(genderInput.value);
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                }

                // Update avatar border when gender is changed in any form
                function updateAvatarBorder(select) {
                    const form = select.closest('form');
                    const preview = form.querySelector('.preview-avatar, .preview-avatar-add');
                    if (preview) {
                        preview.classList.remove('male', 'female');
                        preview.classList.add(select.value);
                    }
                }

                // Add event listeners for gender selects
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('select[name="gender"]').forEach(select => {
                        select.addEventListener('change', function() {
                            updateAvatarBorder(this);
                        });
                    });

                    // Add photo preview for add form
                    const addPhotoInput = document.getElementById('add_photo');
                    if (addPhotoInput) {
                        addPhotoInput.addEventListener('change', function() {
                            previewImage(this, '.preview-avatar-add');
                        });
                    }
                });
            </script>

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
                                <input type="text" name="name" id="add_name" required
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500">
                            </div>

                            <div>
                                <label for="add_nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK (Nomor
                                    Induk Kependudukan)</label>
                                <input type="text" name="nik" id="add_nik" maxlength="16" pattern="[0-9]{16}"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Masukkan 16 digit NIK">
                                <p class="mt-1 text-sm text-gray-500">NIK digunakan untuk menghubungkan anggota keluarga
                                    secara otomatis</p>
                            </div>

                            <div>
                                <label for="add_relation" class="block text-sm font-semibold text-gray-700 mb-2">Hubungan
                                    Keluarga</label>
                                <select name="relation" id="add_relation" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="father">Ayah</option>
                                    <option value="mother">Ibu</option>
                                    <option value="child">Anak</option>
                                </select>
                            </div>

                            <div>
                                <label for="add_gender" class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                    Kelamin</label>
                                <select name="gender" id="add_gender" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="add_birth_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal
                                    Lahir</label>
                                <input type="date" name="birth_date" id="add_birth_date" required
                                    class="form-input w-full px-4 py-3 text-gray-900">
                            </div>

                            <div>
                                <label for="add_description"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="add_description" rows="3"
                                    class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                                    placeholder="Ceritakan tentang anggota keluarga ini..."></textarea>
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
                }

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

                // Initialize when document is ready
                document.addEventListener('DOMContentLoaded', function() {
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
                });
            </script>
        @endsection
