@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Keluarga Saya</h1>
                @if (!$families->count())
                    <a href="{{ route('user.family.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Buat Keluarga
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($families as $family)
                        <div class="border-b border-gray-200 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $family->family_name }}</h2>
                                    <p class="text-gray-600 mt-1">{{ $family->members_count }} anggota</p>
                                    @if ($family->description)
                                        <p class="text-gray-600 mt-2">{{ $family->description }}</p>
                                    @endif
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
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
