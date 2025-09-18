<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Bukti Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Payment Information -->
                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-semibold">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jumlah Pembayaran</p>
                                <p class="font-semibold text-lg text-green-600">Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kode Unik</p>
                                <p class="font-semibold text-lg text-blue-600">{{ $user->payment_code }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">
                                <strong>Penting:</strong> Pastikan jumlah pembayaran sesuai dengan yang tertera di atas, termasuk kode unik.
                                Transfer ke rekening yang telah ditentukan dan upload bukti pembayaran di bawah ini.
                            </p>
                        </div>
                    </div>

                    @if ($user->hasUploadedPaymentProof())
                        <!-- Show current payment proof -->
                        <div class="mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Bukti Pembayaran Saat Ini</h3>
                            <div class="mb-4">
                                <img src="{{ $user->getPaymentProofUrl() }}" alt="Bukti Pembayaran" class="max-w-md rounded-lg shadow-md">
                            </div>
                            <p class="text-sm text-gray-600 mb-4">
                                Diupload pada: {{ $user->payment_proof_uploaded_at->format('d/m/Y H:i') }}
                            </p>
                            
                            @if ($user->payment_status === 'pending')
                                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                                    <p class="font-semibold">Status: Menunggu Verifikasi</p>
                                    <p class="text-sm">Bukti pembayaran Anda sedang diverifikasi oleh admin. Anda akan mendapat notifikasi WhatsApp setelah disetujui.</p>
                                </div>
                            @elseif ($user->payment_status === 'rejected')
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                    <p class="font-semibold">Status: Ditolak</p>
                                    <p class="text-sm">Bukti pembayaran Anda ditolak. Silakan upload ulang bukti pembayaran yang valid.</p>
                                </div>
                            @endif

                            <div class="flex space-x-4">
                                <a href="{{ route('payment-proof.view') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Lihat Detail
                                </a>
                                @if ($user->payment_status !== 'approved')
                                    <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus bukti pembayaran ini?')">
                                            Hapus & Upload Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Upload form -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Bukti Pembayaran</h3>
                            
                            <form method="POST" action="{{ route('payment-proof.store') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih File Bukti Pembayaran
                                    </label>
                                    <input type="file" 
                                           id="payment_proof" 
                                           name="payment_proof" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           required>
                                    @error('payment_proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format yang didukung: JPEG, PNG, JPG. Maksimal 5MB.
                                    </p>
                                </div>

                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">Tips Upload Bukti Pembayaran:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>• Pastikan foto jelas dan tidak buram</li>
                                        <li>• Semua informasi pembayaran terlihat dengan jelas</li>
                                        <li>• Jumlah transfer sesuai dengan yang tertera di atas</li>
                                        <li>• Tanggal dan waktu transfer terlihat</li>
                                        <li>• Nama pengirim sesuai dengan nama akun Anda</li>
                                    </ul>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                                        Upload Bukti Pembayaran
                                    </button>
                                    
                                    <a href="{{ route('pending-approval') }}" 
                                       class="text-gray-600 hover:text-gray-800 underline">
                                        Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Bank Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Rekening</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded border">
                                <p class="font-semibold text-blue-600">Bank BCA</p>
                                <p class="text-lg font-mono">1234567890</p>
                                <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <p class="font-semibold text-green-600">Bank Mandiri</p>
                                <p class="text-lg font-mono">0987654321</p>
                                <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview image before upload
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if doesn't exist
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview';
                        preview.className = 'mt-4';
                        e.target.parentNode.appendChild(preview);
                    }
                    
                    preview.innerHTML = `
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img src="${e.target.result}" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>