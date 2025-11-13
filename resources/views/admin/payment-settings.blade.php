@extends('layouts.admin', ['title' => 'Pengaturan Pembayaran - Admin Dashboard'])

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Pembayaran</h1>
                <p class="mt-1 text-sm text-gray-600">Atur biaya pendaftaran untuk user baru</p>
            </div>
        </div>

        <!-- Payment Settings Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Biaya Pendaftaran</h3>

                <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Terjadi kesalahan:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="registration_fee" class="block text-sm font-medium text-gray-700">
                                Biaya Pendaftaran (Rupiah)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="registration_fee" id="registration_fee"
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="50000" value="{{ $setting ? $setting->registration_fee : 50000 }}"
                                    min="0" step="1000" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">.00</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Biaya yang harus dibayar user saat mendaftar. Kode unik akan ditambahkan secara otomatis.
                            </p>
                        </div>
                    </div>

                    <!-- Current Settings Info -->
                    @if ($setting)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Pengaturan Saat Ini</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Biaya Pendaftaran: <span class="font-medium">Rp
                                        {{ number_format($setting->registration_fee, 0, ',', '.') }}</span></p>
                                <p>Status: <span
                                        class="font-medium {{ $setting->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $setting->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                </p>
                                <p>Terakhir Diperbarui: <span
                                        class="font-medium">{{ $setting->updated_at->format('d F Y H:i') }}</span></p>
                            </div>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Informasi Penting
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Setiap user akan mendapat kode unik 3 digit yang ditambahkan ke biaya dasar</li>
                                        <li>Contoh: Jika biaya Rp 50.000, user akan bayar Rp 50.123 (dengan kode unik 123)
                                        </li>
                                        <li>Perubahan biaya hanya berlaku untuk pendaftaran baru</li>
                                        <li>User yang sudah terdaftar tidak terpengaruh perubahan ini</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
