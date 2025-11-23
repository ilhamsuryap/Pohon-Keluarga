@extends('layouts.admin', ['title' => 'Pengaturan Konfigurasi - Admin Dashboard'])

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-3xl font-bold gradient-text">Pengaturan Konfigurasi</h1>
                <p class="mt-2 text-sm text-gray-600">Atur konfigurasi WhatsApp API dan nomor admin</p>
            </div>
        </div>

        <!-- Config Settings Form -->
        <script>
            window.configSettingsData = function() {
                return {
                    isEditing: false,
                    originalValues: {!! json_encode([
                        'whatsapp_api_url' => isset($configs['whatsapp_api_url']) ? $configs['whatsapp_api_url']->value : 'https://api.quods.id/api',
                        'whatsapp_api_key' => isset($configs['whatsapp_api_key']) ? $configs['whatsapp_api_key']->value : '',
                        'whatsapp_device_key' => isset($configs['whatsapp_device_key']) ? $configs['whatsapp_device_key']->value : '',
                        'whatsapp_admin_phones' => isset($configs['whatsapp_admin_phones']) ? $configs['whatsapp_admin_phones']->value : ''
                    ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
                    currentValues: {!! json_encode([
                        'whatsapp_api_url' => isset($configs['whatsapp_api_url']) ? $configs['whatsapp_api_url']->value : 'https://api.quods.id/api',
                        'whatsapp_api_key' => isset($configs['whatsapp_api_key']) ? $configs['whatsapp_api_key']->value : '',
                        'whatsapp_device_key' => isset($configs['whatsapp_device_key']) ? $configs['whatsapp_device_key']->value : '',
                        'whatsapp_admin_phones' => isset($configs['whatsapp_admin_phones']) ? $configs['whatsapp_admin_phones']->value : ''
                    ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
                    hasChanges() {
                        return this.currentValues.whatsapp_api_url !== this.originalValues.whatsapp_api_url ||
                               this.currentValues.whatsapp_api_key !== this.originalValues.whatsapp_api_key ||
                               this.currentValues.whatsapp_device_key !== this.originalValues.whatsapp_device_key ||
                               this.currentValues.whatsapp_admin_phones !== this.originalValues.whatsapp_admin_phones;
                    },
                    enableEdit() {
                        this.isEditing = true;
                    },
                    cancelEdit() {
                        this.currentValues = JSON.parse(JSON.stringify(this.originalValues));
                        this.isEditing = false;
                    }
                }
            }
        </script>
        <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up" x-data="configSettingsData()">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Konfigurasi WhatsApp API</h3>
                    <div x-show="!isEditing" style="display: block;">
                        <button type="button" @click="enableEdit()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-lg text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.config-settings.update') }}" method="POST" class="space-y-6">
                    @csrf

                    @if (session('success'))
                        <div class="bg-gradient-to-r from-green-50/90 to-emerald-50/90 border border-green-200/60 rounded-2xl p-4 shadow-lg">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-700">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

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
                        <!-- WhatsApp API URL -->
                        <div>
                            <label for="whatsapp_api_url" class="block text-sm font-medium text-gray-700">
                                WhatsApp API URL
                            </label>
                            <div class="mt-1">
                                <input type="url" name="whatsapp_api_url" id="whatsapp_api_url"
                                    x-model="currentValues.whatsapp_api_url"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="https://api.quods.id/api" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                URL endpoint untuk API WhatsApp Quods
                            </p>
                        </div>

                        <!-- WhatsApp API Key (Bearer Token) -->
                        <div>
                            <label for="whatsapp_api_key" class="block text-sm font-medium text-gray-700">
                                WhatsApp API Key (Bearer Token)
                            </label>
                            <div class="mt-1">
                                <input type="text" name="whatsapp_api_key" id="whatsapp_api_key"
                                    x-model="currentValues.whatsapp_api_key"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono"
                                    placeholder="Masukkan Bearer Token" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Bearer token untuk autentikasi API WhatsApp Quods
                            </p>
                        </div>

                        <!-- WhatsApp Device Key -->
                        <div>
                            <label for="whatsapp_device_key" class="block text-sm font-medium text-gray-700">
                                WhatsApp Device Key
                            </label>
                            <div class="mt-1">
                                <input type="text" name="whatsapp_device_key" id="whatsapp_device_key"
                                    x-model="currentValues.whatsapp_device_key"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono"
                                    placeholder="Masukkan Device Key" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Device key untuk identifikasi perangkat WhatsApp
                            </p>
                        </div>

                        <!-- WhatsApp Admin Phones -->
                        <div>
                            <label for="whatsapp_admin_phones" class="block text-sm font-medium text-gray-700">
                                Nomor WhatsApp Admin
                            </label>
                            <div class="mt-1">
                                <input type="text" name="whatsapp_admin_phones" id="whatsapp_admin_phones"
                                    x-model="currentValues.whatsapp_admin_phones"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="6285941051469 atau 6285941051469,628123456789" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Nomor WhatsApp yang akan menerima notifikasi admin. Pisahkan dengan koma jika lebih dari satu.
                                Format: 62xxxxxxxxxxx (tanpa + dan spasi)
                            </p>
                        </div>
                    </div>

                    <!-- Current Settings Info -->
                    @if ($configs->isNotEmpty())
                        <div class="bg-gradient-to-br from-purple-50/80 to-blue-50/80 rounded-2xl p-6 border border-purple-100/60 shadow-lg">
                            <h4 class="text-base font-bold text-gray-900 mb-3">Informasi Konfigurasi Saat Ini</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                @foreach ($configs as $key => $config)
                                    <p>
                                        <span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                        @if ($key === 'whatsapp_api_key' || $key === 'whatsapp_device_key')
                                            <span class="font-mono text-xs bg-gray-200 px-2 py-1 rounded">
                                                {{ Str::limit($config->value ?? '', 20) }}...
                                            </span>
                                        @else
                                            <span class="font-medium">{{ $config->value ?? '-' }}</span>
                                        @endif
                                    </p>
                                @endforeach
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
                                        <li>Pastikan API Key dan Device Key valid sebelum menyimpan</li>
                                        <li>Nomor admin akan menerima notifikasi untuk pembayaran baru</li>
                                        <li>Untuk multiple admin, pisahkan nomor dengan koma (contoh: 628123456789,628987654321)</li>
                                        <li>Format nomor harus dimulai dengan 62 (kode negara Indonesia)</li>
                                        <li>Perubahan akan langsung diterapkan setelah disimpan</li>
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
                            :class="hasChanges() ? 'bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 cursor-pointer shadow-lg transform hover:scale-105' : 'bg-gray-400 cursor-not-allowed'"
                            class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

