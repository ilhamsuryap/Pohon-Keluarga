@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gradient-to-br from-purple-50/80 via-blue-50/80 to-indigo-50/80 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-50/90 to-emerald-50/90 border border-green-200/60 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-lg"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-gradient-to-r from-red-50/90 to-rose-50/90 border border-red-200/60 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-lg" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Keluarga Saya Section -->
            <div class="mb-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-500 via-blue-500 to-indigo-500 bg-clip-text text-transparent">Keluarga Saya</h1>
                </div>

                <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20">
                    <div class="p-6 sm:p-8 text-gray-900">
                        @forelse ($families as $family)
                            <div class="border-b border-gray-200/60 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">{{ $family->family_name }}</h2>

                                        @if ($family->description)
                                            <p class="text-gray-600 mt-2">{{ $family->description }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-1">{{ $family->members_count }} anggota</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('user.family.show', $family) }}"
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-400 to-indigo-500 hover:from-blue-500 hover:to-indigo-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                            Lihat
                                        </a>
                                        <a href="{{ route('user.family.edit', $family) }}"
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                            Edit
                                        </a>
                                        <form action="{{ route('user.family.destroy', $family) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus keluarga ini?')"
                                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada keluarga</h3>
                                <p class="text-gray-600 mb-4">Mulai buat silsilah keluarga Anda sekarang.</p>
                                <a href="{{ route('user.family.create') }}?type=family"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-400 to-purple-500 hover:from-blue-500 hover:to-purple-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    Buat Keluarga
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Perusahaan Saya Section -->
            <div>
                <div class="mb-6">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 bg-clip-text text-transparent">Perusahaan Saya</h1>
                </div>

                <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20">
                    <div class="p-6 sm:p-8 text-gray-900">
                        @forelse ($companies as $company)
                            <div class="border-b border-gray-200/60 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">{{ $company->company_name }}</h2>

                                        @if ($company->description)
                                            <p class="text-gray-600 mt-2">{{ $company->description }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-1">{{ $company->members_count }} anggota</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('user.company.show', $company) }}"
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                            Lihat
                                        </a>
                                        <a href="{{ route('user.company.edit', $company) }}"
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                            Edit
                                        </a>
                                        <form action="{{ route('user.company.destroy', $company) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')"
                                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada perusahaan</h3>
                                <p class="text-gray-600 mb-4">Mulai buat struktur perusahaan Anda sekarang.</p>
                                <a href="{{ route('user.company.create') }}"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    Buat Perusahaan
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
