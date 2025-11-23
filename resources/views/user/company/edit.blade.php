@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gradient-to-br from-purple-50/80 via-blue-50/80 to-indigo-50/80 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20">
                <div class="p-6 sm:p-8 text-gray-900">
                    <h2 class="text-3xl font-bold mb-6 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 bg-clip-text text-transparent">Edit Perusahaan</h2>

                    <form action="{{ route('user.company.update', $company) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                <input type="text" name="company_name" id="company_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required value="{{ old('company_name', $company->company_name) }}">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                    Perusahaan</label>
                                <textarea name="description" id="description" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $company->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="privacy" class="block text-sm font-medium text-gray-700">
                                    Privacy Label
                                </label>
                                <select name="privacy" id="privacy"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Privacy</option>
                                    <option value="privat" {{ old('privacy', $company->privacy) == 'privat' ? 'selected' : '' }}>Privat</option>
                                    <option value="publik" {{ old('privacy', $company->privacy) == 'publik' ? 'selected' : '' }}>Publik</option>
                                    <option value="friend_only" {{ old('privacy', $company->privacy) == 'friend_only' ? 'selected' : '' }}>Friend Only</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Pilih tingkat privasi untuk perusahaan ini</p>
                                @error('privacy')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('user.company.show', $company) }}"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



