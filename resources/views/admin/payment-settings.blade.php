@extends('layouts.admin', ['title' => 'Pengaturan Pembayaran - Admin Dashboard'])

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-3xl font-bold gradient-text">Pengaturan Pembayaran</h1>
                <p class="mt-2 text-sm text-gray-600">Atur biaya pendaftaran untuk user baru</p>
            </div>
        </div>

        <!-- Payment Settings Form -->
        <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up" x-data="{
            isEditing: false,
            originalValue: {{ $setting ? $setting->registration_fee : 50000 }},
            currentValue: {{ $setting ? $setting->registration_fee : 50000 }},
            hasChanges() {
                return parseFloat(this.currentValue) !== parseFloat(this.originalValue);
            },
            enableEdit() {
                this.isEditing = true;
            },
            cancelEdit() {
                this.currentValue = this.originalValue;
                this.isEditing = false;
            }
        }">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Biaya Pendaftaran</h3>
                    <button type="button" @click="enableEdit()" x-show="!isEditing"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-lg text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                </div>

                <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-gradient-to-r from-red-50/90 to-rose-50/90 border border-red-200/60 rounded-2xl p-4 shadow-lg">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-700">
                                        Terjadi kesalahan:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-600">
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
                                    x-model.number="currentValue"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="50000"
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
                        <div class="bg-gradient-to-br from-purple-50/80 to-blue-50/80 rounded-2xl p-6 border border-purple-100/60 shadow-lg">
                            <h4 class="text-base font-bold text-gray-900 mb-3">Pengaturan Saat Ini</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Biaya Pendaftaran: <span class="font-medium">Rp
                                        {{ number_format($setting->registration_fee, 0, ',', '.') }}</span></p>
                                <p>Status: <span
                                        class="font-medium {{ $setting->is_active ? 'text-green-500' : 'text-red-500' }}">{{ $setting->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                </p>
                                <p>Terakhir Diperbarui: <span
                                        class="font-medium">{{ $setting->updated_at->format('d F Y H:i') }}</span></p>
                            </div>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="bg-gradient-to-br from-blue-50/80 to-indigo-50/80 border border-blue-200/60 rounded-2xl p-6 shadow-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-700">
                                    Informasi Penting
                                </h3>
                                <div class="mt-2 text-sm text-blue-600">
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

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3" x-show="isEditing">
                        <button type="button" @click="cancelEdit()"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-lg text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            Batal
                        </button>
                        <button type="submit" :disabled="!hasChanges()"
                            :class="hasChanges() ? 'bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 cursor-pointer shadow-lg transform hover:scale-105' : 'bg-gray-400 cursor-not-allowed'"
                            class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 transition-all">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
