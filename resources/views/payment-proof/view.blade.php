<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #c084fc, #818cf8, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">
                            <a href="{{ route('payment-proof.upload') }}" class="hover:text-purple-600 font-semibold transition-colors">Bukti Pembayaran</a> 
                            <span class="text-gray-400">/</span> 
                            <span class="text-gray-800 font-semibold">Detail</span>
                        </p>
                        <h1 class="text-4xl md:text-5xl font-extrabold">
                            <span class="gradient-text">Detail Bukti Pembayaran</span>
                        </h1>
                    </div>
                    @if ($user->payment_status)
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-lg
                            {{ $user->payment_status === 'approved' ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white' : '' }}
                            {{ $user->payment_status === 'pending' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white' : '' }}
                            {{ $user->payment_status === 'rejected' ? 'bg-gradient-to-r from-red-400 to-pink-500 text-white' : '' }}
                            {{ !in_array($user->payment_status, ['approved','pending','rejected']) ? 'bg-gradient-to-r from-gray-400 to-gray-500 text-white' : '' }}
                        ">
                            {{ ucfirst($user->payment_status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="glass-effect overflow-hidden shadow-2xl rounded-3xl animate-fade-in-up">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left: Proof Image -->
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold mb-6 gradient-text">Bukti Pembayaran</h3>
                                <div class="glass-effect p-6 rounded-2xl border border-white/30 shadow-xl">
                                    <img src="{{ $user->getPaymentProofUrl() }}"
                                         alt="Bukti Pembayaran"
                                         class="w-full h-auto rounded-2xl shadow-2xl border-4 border-white/50 cursor-pointer hover:scale-105 transition-transform duration-300"
                                         onclick="openImageModal(this.src)">
                                    <div class="mt-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 flex items-center justify-between text-sm">
                                        <p class="text-gray-700 font-semibold flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                            Klik gambar untuk memperbesar
                                        </p>
                                        @php
                                            $filePath = $user->payment_proof;
                                        @endphp
                                        @if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath))
                                            @php
                                                $size = \Illuminate\Support\Facades\Storage::disk('public')->size($filePath);
                                                $name = basename($filePath);
                                            @endphp
                                            <p class="font-mono text-gray-600 bg-white px-3 py-1 rounded-lg border border-gray-200">{{ $name }} · {{ number_format($size / 1024, 1) }} KB</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($user->payment_status === 'pending')
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-6 py-4 rounded-xl mb-6 shadow-lg border border-white/20">
                                    <div class="flex items-start">
                                        <svg class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-bold text-lg mb-1">Menunggu Verifikasi</h4>
                                            <p class="text-sm">Bukti pembayaran Anda sedang diverifikasi oleh admin. Anda akan mendapat notifikasi WhatsApp setelah disetujui.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($user->payment_status === 'approved')
                                <div class="bg-gradient-to-r from-green-400 to-emerald-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg border border-white/20">
                                    <div class="flex items-start">
                                        <svg class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-bold text-lg mb-1">Pembayaran Disetujui</h4>
                                            <p class="text-sm">Pembayaran Anda telah diverifikasi. Akun Anda sekarang aktif.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($user->payment_status === 'rejected')
                                <div class="bg-gradient-to-r from-red-400 to-pink-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg border border-white/20">
                                    <div class="flex items-start">
                                        <svg class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                        <div>
                                            <h4 class="font-bold text-lg mb-1">Pembayaran Ditolak</h4>
                                            <p class="text-sm">Bukti pembayaran Anda tidak dapat diverifikasi. Silakan upload ulang bukti pembayaran yang valid.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-4">
                                @if ($user->payment_status !== 'approved')
                                    <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200" onclick="return confirm('Hapus bukti pembayaran dan upload ulang?')">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus & Upload Ulang
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('payment-proof.upload') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>

                        <!-- Right: Payment Details -->
                        <div class="lg:col-span-1">
                            <div class="mb-6 glass-effect rounded-2xl p-6 shadow-xl border border-white/30">
                                <h3 class="text-xl font-bold mb-6 gradient-text">Informasi Pembayaran</h3>
                                <dl class="space-y-4">
                                    <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-4 rounded-xl border border-purple-200/50">
                                        <dt class="text-sm text-gray-600 mb-1">Nama</dt>
                                        <dd class="font-bold text-gray-900 text-lg">{{ $user->name }}</dd>
                                    </div>
                                    <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-4 rounded-xl border border-purple-200/50">
                                        <dt class="text-sm text-gray-600 mb-1">Email</dt>
                                        <dd class="font-bold text-gray-900 text-lg">{{ $user->email }}</dd>
                                    </div>
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-xl border border-indigo-200/50">
                                        <dt class="text-sm text-gray-600 mb-1">Jumlah</dt>
                                        <dd class="font-bold text-2xl bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-xl border border-indigo-200/50">
                                        <dt class="text-sm text-gray-600 mb-1">Kode Unik</dt>
                                        <dd class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $user->payment_code }}</dd>
                                    </div>
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200">
                                        <dt class="text-sm text-gray-600 mb-1">Tanggal Upload</dt>
                                        <dd class="font-semibold text-gray-900">{{ optional($user->payment_proof_uploaded_at)->format('d/m/Y H:i') ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="glass-effect rounded-2xl p-6 shadow-xl border border-white/30">
                                <h3 class="text-xl font-bold mb-6 gradient-text">Informasi Rekening</h3>
                                <ul class="space-y-4">
                                    <li class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-2xl border border-blue-200/50 shadow-lg hover:shadow-xl transition-shadow">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-lg bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Bank BCA</p>
                                        </div>
                                        <p class="text-xl font-mono font-bold text-gray-800 mb-1">1234567890</p>
                                        <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
                                    </li>
                                    <li class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-2xl border border-green-200/50 shadow-lg hover:shadow-xl transition-shadow">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-lg bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Bank Mandiri</p>
                                        </div>
                                        <p class="text-xl font-mono font-bold text-gray-800 mb-1">0987654321</p>
                                        <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
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
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full glass-effect rounded-3xl shadow-2xl border border-white/30 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold gradient-text">Bukti Pembayaran</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 hover:bg-white/20 rounded-full p-2 transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-2xl shadow-2xl border-4 border-white/50">
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