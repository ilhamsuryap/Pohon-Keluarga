<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500"><a href="{{ route('payment-proof.upload') }}" class="hover:underline">Bukti Pembayaran</a> / Detail</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Bukti Pembayaran') }}</h2>
            </div>
            @if ($user->payment_status)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    {{ $user->payment_status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $user->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $user->payment_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                    {{ !in_array($user->payment_status, ['approved','pending','rejected']) ? 'bg-gray-100 text-gray-800' : '' }}
                ">
                    {{ ucfirst($user->payment_status) }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Proof Image -->
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Bukti Pembayaran</h3>
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <img src="{{ $user->getPaymentProofUrl() }}"
                                         alt="Bukti Pembayaran"
                                         class="w-full h-auto rounded-lg shadow-md cursor-pointer"
                                         onclick="openImageModal(this.src)">
                                    <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                        <p>Klik gambar untuk memperbesar</p>
                                        @php
                                            $filePath = $user->payment_proof;
                                        @endphp
                                        @if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath))
                                            @php
                                                $size = \Illuminate\Support\Facades\Storage::disk('public')->size($filePath);
                                                $name = basename($filePath);
                                            @endphp
                                            <p class="font-mono">{{ $name }} · {{ number_format($size / 1024, 1) }} KB</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($user->payment_status === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-6">
                                    <div class="flex">
                                        <svg class="h-5 w-5 mt-0.5 mr-2 text-yellow-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-medium">Menunggu Verifikasi</h4>
                                            <p class="text-sm mt-1">Bukti pembayaran Anda sedang diverifikasi oleh admin. Anda akan mendapat notifikasi WhatsApp setelah disetujui.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($user->payment_status === 'approved')
                                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">
                                    <div class="flex">
                                        <svg class="h-5 w-5 mt-0.5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-medium">Pembayaran Disetujui</h4>
                                            <p class="text-sm mt-1">Pembayaran Anda telah diverifikasi. Akun Anda sekarang aktif.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($user->payment_status === 'rejected')
                                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
                                    <div class="flex">
                                        <svg class="h-5 w-5 mt-0.5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-medium">Pembayaran Ditolak</h4>
                                            <p class="text-sm mt-1">Bukti pembayaran Anda tidak dapat diverifikasi. Silakan upload ulang bukti pembayaran yang valid.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3">
                                @if ($user->payment_status !== 'approved')
                                    <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded" onclick="return confirm('Hapus bukti pembayaran dan upload ulang?')">
                                            Hapus & Upload Ulang
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('payment-proof.upload') }}" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded">Kembali</a>
                            </div>
                        </div>

                        <!-- Right: Payment Details -->
                        <div class="lg:col-span-1">
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 class="text-base font-semibold text-blue-800 mb-4">Informasi Pembayaran</h3>
                                <dl class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-600">Nama</dt>
                                        <dd class="font-medium text-gray-900">{{ $user->name }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-600">Email</dt>
                                        <dd class="font-medium text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-600">Jumlah</dt>
                                        <dd class="font-semibold text-green-600">Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-600">Kode Unik</dt>
                                        <dd class="font-semibold text-blue-600">{{ $user->payment_code }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-600">Tanggal Upload</dt>
                                        <dd class="font-medium text-gray-900">{{ optional($user->payment_proof_uploaded_at)->format('d/m/Y H:i') ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <h3 class="text-base font-semibold text-gray-800 mb-4">Informasi Rekening</h3>
                                <ul class="space-y-3 text-sm">
                                    <li class="bg-white p-3 rounded border">
                                        <p class="font-semibold text-blue-600">Bank BCA</p>
                                        <p class="font-mono">1234567890</p>
                                        <p class="text-gray-600">a.n. Admin Pohon Keluarga</p>
                                    </li>
                                    <li class="bg-white p-3 rounded border">
                                        <p class="font-semibold text-green-600">Bank Mandiri</p>
                                        <p class="font-mono">0987654321</p>
                                        <p class="text-gray-600">a.n. Admin Pohon Keluarga</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
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