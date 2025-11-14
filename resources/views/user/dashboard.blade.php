<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Selamat datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Kelola pohon keluarga Anda di sini.</p>
                    </div>

                    <!-- Blok Keluarga -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-lg p-6 mb-8">
                        <!-- Statistics Cards - Keluarga -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Keluarga</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-blue-100">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Total Keluarga</p>
                                            <p class="text-2xl font-semibold text-gray-900">{{ $families->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-6 rounded-lg shadow-sm border border-blue-100">
                                    <div class="flex items-center">
                                        <div
                                            class="p-3 rounded-full bg-gradient-to-br from-purple-100 to-blue-100 text-purple-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Total Anggota Keluarga</p>
                                            <p class="text-2xl font-semibold text-gray-900">{{ $totalMembers }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family List -->
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Daftar Keluarga</h4>
                            <a href="{{ route('user.family.create') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Tambah Keluarga
                            </a>
                        </div>

                        @if ($families->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($families as $family)
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h5 class="font-medium text-gray-900 mb-2">{{ $family->family_name }}</h5>
                                        <p class="text-sm text-gray-600 mb-3">{{ $family->members->count() }} anggota
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('user.family.show', $family) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('user.family.edit', $family) }}"
                                                class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada keluarga</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan keluarga pertama Anda.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('user.family.create') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Tambah Keluarga
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Divider -->
                    <div class="my-8 border-t border-gray-300"></div>

                    <!-- Blok Perusahaan -->
                    <div class="bg-green-50/50 border border-green-100 rounded-lg p-6">
                        <!-- Statistics Cards - Perusahaan -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perusahaan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-green-100">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Total Perusahaan</p>
                                            <p class="text-2xl font-semibold text-gray-900">{{ $companies->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-6 rounded-lg shadow-sm border border-green-100">
                                    <div class="flex items-center">
                                        <div
                                            class="p-3 rounded-full bg-gradient-to-br from-emerald-100 to-green-100 text-emerald-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Total Anggota Perusahaan</p>
                                            <p class="text-2xl font-semibold text-gray-900">{{ $totalCompanyMembers }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company List -->
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Daftar Perusahaan</h4>
                            <a href="{{ route('user.family.create', ['type' => 'company']) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Tambah Perusahaan
                            </a>
                        </div>

                        @if ($companies->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($companies as $company)
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h5 class="font-medium text-gray-900 mb-2">{{ $company->family_name }}</h5>
                                        <p class="text-sm text-gray-600 mb-3">{{ $company->members->count() }} anggota
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('user.family.show', $company) }}"
                                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('user.family.edit', $company) }}"
                                                class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada perusahaan</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan perusahaan pertama Anda.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('user.family.create', ['type' => 'company']) }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Tambah Perusahaan
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
