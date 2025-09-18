<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Bukti Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('payment-proof.upload') }}" class="text-blue-600 hover:text-blue-800 underline">
                            ← Kembali ke Upload
                        </a>
                    </div>

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
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Upload</p>
                                <p class="font-semibold">{{ $user->payment_proof_uploaded_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                @if ($user->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif ($user->payment_status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                @elseif ($user->payment_status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($user->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof Image -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Bukti Pembayaran</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <img src="{{ $user->getPaymentProofUrl() }}" 
                                 alt="Bukti Pembayaran" 
                                 class="max-w-full h-auto rounded-lg shadow-md cursor-pointer"
                                 onclick="openImageModal(this.src)">
                            <p class="text-sm text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                        </div>
                    </div>

                    <!-- Status Information -->
                    @if ($user->payment_status === 'pending')
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Menunggu Verifikasi</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Bukti pembayaran Anda sedang diverifikasi oleh admin. Proses verifikasi biasanya memakan waktu 1-2 hari kerja. Anda akan mendapat notifikasi WhatsApp setelah pembayaran disetujui.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($user->payment_status === 'approved')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Pembayaran Disetujui</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>Selamat! Pembayaran Anda telah diverifikasi dan disetujui. Akun Anda sekarang aktif dan Anda dapat menggunakan semua fitur aplikasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($user->payment_status === 'rejected')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Pembayaran Ditolak</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Maaf, bukti pembayaran Anda tidak dapat diverifikasi. Silakan upload ulang bukti pembayaran yang valid atau hubungi admin untuk bantuan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex space-x-4">
                        @if ($user->payment_status !== 'approved')
                            <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus bukti pembayaran ini?')">
                                    Hapus & Upload Ulang
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('pending-approval') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            Kembali ke Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran</h3>
                    <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>