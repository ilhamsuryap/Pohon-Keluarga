@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 text-purple-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Keluarga Saya Section -->
            <div class="mb-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Keluarga Saya</h1>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @forelse ($families as $family)
                            <div class="border-b border-gray-200 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">{{ $family->family_name }}</h2>

                                        @if ($family->description)
                                            <p class="text-gray-600 mt-2">{{ $family->description }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-1">{{ $family->members_count }} anggota</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('user.family.show', $family) }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Lihat
                                        </a>
                                        <a href="{{ route('user.family.edit', $family) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Edit
                                        </a>
                                        <form action="{{ route('user.family.destroy', $family) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus keluarga ini?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 transition ease-in-out duration-150">
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
                    <h1 class="text-2xl font-semibold text-gray-900">Perusahaan Saya</h1>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @forelse ($companies as $company)
                            <div class="border-b border-gray-200 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">{{ $company->company_name }}</h2>

                                        @if ($company->description)
                                            <p class="text-gray-600 mt-2">{{ $company->description }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-1">{{ $company->members_count }} anggota</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('user.company.show', $company) }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Lihat
                                        </a>
                                        <a href="{{ route('user.company.edit', $company) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Edit
                                        </a>
                                        <form action="{{ route('user.company.destroy', $company) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-teal-600 hover:to-blue-600 transition ease-in-out duration-150">
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
