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

    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 py-12 flex items-center justify-center">
        <div class="max-w-4xl w-full mx-auto sm:px-6 lg:px-8">
            <div class="glass-effect overflow-hidden shadow-2xl rounded-3xl animate-fade-in-up">
                <div class="p-8 md:p-10 text-gray-900">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 shadow-xl mb-6">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                            <span class="gradient-text">Akun Menunggu Persetujuan</span>
                        </h1>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Terima kasih telah mendaftar! Akun Anda sedang dalam proses review oleh admin.
                        </p>

                        <div class="mt-8 bg-gradient-to-r from-purple-400/80 to-blue-500/80 text-white rounded-2xl p-6 shadow-xl border border-white/20">
                            <div class="flex flex-col items-center text-center">
                                <div class="mb-4">
                                    <svg class="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-4">
                                    Informasi Penting
                                </h3>
                                <div class="text-sm space-y-2">
                                    <ul class="list-none space-y-2">
                                        <li>Admin akan meninjau akun Anda dalam 1-2 hari kerja</li>
                                        <li>Pastikan informasi yang Anda berikan sudah benar</li>
                                        <li>Anda akan mendapat notifikasi WhatsApp setelah akun disetujui</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status Section -->
                        @if (!Auth::user()->hasUploadedPaymentProof())
                            <div class="mt-8 bg-gradient-to-r from-red-300/90 to-pink-400/90 text-white rounded-2xl p-6 shadow-xl border border-white/20">
                                <div class="flex flex-col items-center text-center">
                                    <div class="mb-4">
                                        <svg class="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-4">
                                        Bukti Pembayaran Diperlukan
                                    </h3>
                                    <div class="text-sm space-y-2 mb-6">
                                        <p>Jumlah pembayaran: <strong class="text-lg">Rp
                                                {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong>
                                        </p>
                                        <p>Kode unik: <strong class="text-lg">{{ Auth::user()->payment_code }}</strong></p>
                                        <p class="mt-3">Anda perlu mengupload bukti pembayaran untuk melanjutkan
                                            proses aktivasi akun.</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('payment-proof.upload') }}"
                                            class="inline-flex items-center justify-center bg-white/90 text-red-500 hover:bg-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Bukti Pembayaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'pending')
                            <div class="mt-8 bg-gradient-to-r from-purple-400/80 to-blue-500/80 text-white rounded-2xl p-6 shadow-xl border border-white/20">
                                <div class="flex flex-col items-center text-center">
                                    <div class="mb-4">
                                        <svg class="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-4">
                                        Menunggu Verifikasi Pembayaran
                                    </h3>
                                    <div class="text-sm space-y-2 mb-6">
                                        <p>Bukti pembayaran Anda telah diupload pada:
                                            <strong class="text-lg">{{ Auth::user()->payment_proof_uploaded_at->format('d/m/Y H:i') }}</strong>
                                        </p>
                                        <p>Jumlah: <strong class="text-lg">Rp
                                                {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong>
                                        </p>
                                        <p class="mt-3">Admin sedang memverifikasi pembayaran Anda. Anda akan
                                            mendapat notifikasi WhatsApp setelah disetujui.</p>
                                    </div>
                                    <div class="flex flex-wrap gap-3 justify-center">
                                        <a href="{{ route('payment-proof.view') }}"
                                            class="inline-flex items-center justify-center bg-white/90 text-purple-600 hover:bg-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat Bukti Pembayaran
                                        </a>
                                        <a href="{{ route('payment-proof.upload') }}"
                                            class="inline-flex items-center justify-center bg-white/20 text-white hover:bg-white/30 font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 border border-white/30">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit/Upload Ulang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'rejected')
                            <div class="mt-8 bg-gradient-to-r from-red-300/90 to-pink-400/90 text-white rounded-2xl p-6 shadow-xl border border-white/20">
                                <div class="flex flex-col items-center text-center">
                                    <div class="mb-4">
                                        <svg class="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-4">
                                        Pembayaran Ditolak
                                    </h3>
                                    <div class="text-sm space-y-2 mb-6">
                                        <p>Bukti pembayaran Anda tidak dapat diverifikasi.</p>
                                        <p>Jumlah yang harus dibayar: <strong class="text-lg">Rp
                                                {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong>
                                        </p>
                                        <p>Kode unik: <strong class="text-lg">{{ Auth::user()->payment_code }}</strong></p>
                                        <p class="mt-3">Silakan upload ulang bukti pembayaran yang valid.</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('payment-proof.upload') }}"
                                            class="inline-flex items-center justify-center bg-white/90 text-red-500 hover:bg-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Ulang Bukti Pembayaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'approved')
                            <div class="mt-8 bg-gradient-to-r from-green-300/90 to-emerald-400/90 text-white rounded-2xl p-6 shadow-xl border border-white/20">
                                <div class="flex flex-col items-center text-center">
                                    <div class="mb-4">
                                        <svg class="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-4">
                                        Pembayaran Disetujui
                                    </h3>
                                    <div class="text-sm space-y-2">
                                        <p>Pembayaran Anda telah diverifikasi dan disetujui!</p>
                                        <p>Akun Anda akan segera diaktifkan setelah persetujuan final dari admin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
