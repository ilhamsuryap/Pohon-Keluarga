@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-blue-500 px-6 py-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Jelajah Diagram</h1>
                    <p class="text-blue-100">Temukan dan jelajahi diagram keluarga dan perusahaan</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button id="tab-family" 
                            class="tab-button active flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors"
                            onclick="switchTab('family')">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Pencarian Keluarga
                        </button>
                        <button id="tab-company" 
                            class="tab-button flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors"
                            onclick="switchTab('company')">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Pencarian Perusahaan
                        </button>
                    </nav>
                </div>

                <!-- Search Bar -->
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                            id="search-input" 
                            placeholder="Cari berdasarkan nama keluarga atau perusahaan..." 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            onkeyup="handleSearch()">
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div id="results-container" class="space-y-4">
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-lg">Mulai mengetik untuk mencari keluarga atau perusahaan</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-button {
            color: #6b7280;
            border-color: transparent;
        }
        .tab-button.active {
            color: #3b82f6;
            border-color: #3b82f6;
        }
        .tab-button:hover {
            color: #3b82f6;
            border-color: #e5e7eb;
        }
        .result-card {
            transition: all 0.3s ease;
        }
        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        let currentTab = 'family';
        let searchTimeout;

        function switchTab(tab) {
            currentTab = tab;
            
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById('tab-' + tab).classList.add('active');
            
            // Clear search and results
            document.getElementById('search-input').value = '';
            document.getElementById('results-container').innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-lg">Mulai mengetik untuk mencari ${tab === 'family' ? 'keluarga' : 'perusahaan'}</p>
                </div>
            `;
        }

        function handleSearch() {
            clearTimeout(searchTimeout);
            const query = document.getElementById('search-input').value.trim();
            
            if (query.length < 2) {
                document.getElementById('results-container').innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-lg">Mulai mengetik untuk mencari ${currentTab === 'family' ? 'keluarga' : 'perusahaan'}</p>
                    </div>
                `;
                return;
            }

            // Show loading
            document.getElementById('results-container').innerHTML = `
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                    <p class="mt-4 text-gray-500">Mencari...</p>
                </div>
            `;

            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        }

        function performSearch(query) {
            const url = currentTab === 'family' 
                ? '{{ route("explore.search.families") }}?q=' + encodeURIComponent(query)
                : '{{ route("explore.search.companies") }}?q=' + encodeURIComponent(query);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('results-container').innerHTML = `
                        <div class="text-center py-12 text-red-500">
                            <p class="text-lg">Terjadi kesalahan saat mencari. Silakan coba lagi.</p>
                        </div>
                    `;
                });
        }

        function displayResults(data) {
            const container = document.getElementById('results-container');
            const items = currentTab === 'family' ? data.families : data.companies;

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg">Tidak ada hasil ditemukan</p>
                    </div>
                `;
                return;
            }

            const type = currentTab === 'family' ? 'family' : 'company';
            const typeLabel = currentTab === 'family' ? 'Keluarga' : 'Perusahaan';
            const viewRoute = currentTab === 'family' ? '/explore/family/' : '/explore/company/';

            container.innerHTML = items.map(item => `
                <div class="result-card bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">${escapeHtml(item.name)}</h3>
                            ${item.description ? `<p class="text-gray-600 mb-3">${escapeHtml(item.description)}</p>` : ''}
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    ${item.members_count} anggota
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    ${escapeHtml(item.owner)}
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    ${getPrivacyLabel(item.privacy)}
                                </span>
                            </div>
                        </div>
                        <a href="${viewRoute}${item.id}" 
                            class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Diagram
                        </a>
                    </div>
                </div>
            `).join('');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getPrivacyLabel(privacy) {
            const labels = {
                'publik': 'Publik',
                'friend_only': 'Teman Saja',
                'privat': 'Privat'
            };
            return labels[privacy] || privacy;
        }
    </script>
@endsection

