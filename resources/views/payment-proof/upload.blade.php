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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-effect overflow-hidden shadow-2xl rounded-3xl animate-fade-in-up">
                <div class="p-8 md:p-10">

                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                            <span class="gradient-text">Upload Bukti Pembayaran</span>
                        </h1>
                        <p class="text-gray-600 text-lg">Lengkapi pembayaran Anda untuk mengaktifkan akun</p>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 bg-gradient-to-r from-green-400 to-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg border border-white/20 backdrop-blur-sm">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="font-semibold">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-gradient-to-r from-red-400 to-pink-500 text-white px-6 py-4 rounded-2xl shadow-lg border border-white/20 backdrop-blur-sm">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="font-semibold">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Pembayaran -->
                    <div class="mb-8 glass-effect rounded-2xl p-6 md:p-8 shadow-xl border border-white/30">
                        <h3 class="text-2xl font-bold mb-6 gradient-text">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-4 rounded-xl border border-purple-200/50">
                                <p class="text-sm text-gray-600 mb-1">Nama</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $user->name }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-4 rounded-xl border border-purple-200/50">
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $user->email }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-xl border border-indigo-200/50">
                                <p class="text-sm text-gray-600 mb-1">Jumlah Pembayaran</p>
                                <p class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                    Rp {{ number_format($user->payment_amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-xl border border-indigo-200/50">
                                <p class="text-sm text-gray-600 mb-1">Kode Unik</p>
                                <p class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $user->payment_code }}</p>
                            </div>
                        </div>
                        <div class="mt-6 p-5 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-xl shadow-lg border border-white/20">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="font-semibold">
                                    <strong>Penting:</strong> Transfer sesuai nominal di atas (termasuk kode unik),
                                    lalu upload bukti transfer di bawah ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Rekening -->
                    <div class="mb-8 glass-effect rounded-2xl p-6 md:p-8 shadow-xl border border-white/30">
                        <h3 class="text-2xl font-bold mb-6 gradient-text">Informasi Rekening Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200/50 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Bank BCA</p>
                                </div>
                                <p class="text-2xl font-mono font-bold text-gray-800 mb-2">1234567890</p>
                                <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200/50 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-xl bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Bank Mandiri</p>
                                </div>
                                <p class="text-2xl font-mono font-bold text-gray-800 mb-2">0987654321</p>
                                <p class="text-sm text-gray-600">a.n. Admin Pohon Keluarga</p>
                            </div>
                        </div>
                    </div>

                    @if ($user->hasUploadedPaymentProof())
                        <!-- Bukti Pembayaran Saat Ini -->
                        <div class="mb-8 glass-effect rounded-2xl p-6 md:p-8 shadow-xl border border-white/30">
                            <h3 class="text-2xl font-bold mb-6 gradient-text">Bukti Pembayaran Saat Ini</h3>
                            <div class="mb-6">
                                <img src="{{ $user->getPaymentProofUrl() }}" alt="Bukti Pembayaran"
                                    class="max-w-full md:max-w-md rounded-2xl shadow-2xl border-4 border-white/50">
                            </div>
                            <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Diupload pada:</span>
                                    {{ optional($user->payment_proof_uploaded_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            @if ($user->payment_status === 'pending')
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-6 py-4 rounded-xl mb-6 shadow-lg border border-white/20">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="font-semibold">Menunggu verifikasi admin.</p>
                                    </div>
                                </div>
                            @elseif ($user->payment_status === 'rejected')
                                <div class="bg-gradient-to-r from-red-400 to-pink-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg border border-white/20">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="font-semibold">Ditolak. Silakan upload ulang bukti pembayaran yang valid.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('payment-proof.view') }}"
                                    class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                                @if ($user->payment_status !== 'approved')
                                    <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                                            onclick="return confirm('Hapus bukti pembayaran dan upload ulang?')">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus & Upload Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Form Upload Bukti Transfer -->
                        <div class="mb-10">
                            <div class="glass-effect rounded-2xl p-6 md:p-8 shadow-xl border border-white/30">
                                <h3 class="text-2xl font-bold mb-6 gradient-text">Upload Bukti Pembayaran</h3>

                                <form method="POST" action="{{ route('payment-proof.store') }}"
                                    enctype="multipart/form-data" class="space-y-6">
                                    @csrf

                                    <div>
                                        <label for="payment_proof"
                                            class="block text-sm font-semibold text-gray-700 mb-3">Pilih / Seret & Lepas Bukti
                                            Pembayaran</label>

                                        <!-- Dropzone -->
                                        <div id="dropzone"
                                            class="flex flex-col items-center justify-center w-full p-12 border-3 border-dashed rounded-2xl bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 hover:from-purple-100 hover:via-blue-100 hover:to-indigo-100 transition-all duration-300 cursor-pointer border-purple-300 hover:border-purple-500 shadow-lg hover:shadow-xl">
                                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="mt-2 text-base font-semibold text-gray-700">Seret dan lepas gambar di sini, atau <span
                                                    class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent underline">klik untuk pilih</span></p>
                                            <p class="text-sm text-gray-500 mt-2">JPEG, PNG, JPG (maks. 5MB)</p>
                                        </div>

                                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*"
                                            class="hidden" required>
                                        @error('payment_proof')
                                            <p class="mt-3 text-sm text-red-600 bg-red-50 px-4 py-2 rounded-lg border border-red-200">{{ $message }}</p>
                                        @enderror

                                        <div id="image-preview" class="mt-6 hidden">
                                            <div class="glass-effect rounded-2xl p-6 border border-white/30 shadow-xl">
                                                <div class="flex flex-col md:flex-row items-start gap-6">
                                                    <img id="image-preview-img" src="" alt="Preview"
                                                        class="max-w-xs rounded-xl shadow-2xl border-4 border-white/50">
                                                    <div class="space-y-4 flex-1">
                                                        <button type="button" id="remove-file"
                                                            class="inline-flex items-center justify-center bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus File
                                                        </button>
                                                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                                            <p class="text-sm text-gray-700 font-semibold">
                                                                <span class="text-blue-600">✓</span> Pastikan informasi pada bukti transfer
                                                                terlihat jelas dan dapat dibaca dengan baik.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 hover:from-purple-700 hover:via-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Bukti Pembayaran
                                        </button>
                                        <a href="{{ route('pending-approval') }}"
                                            class="text-gray-600 hover:text-gray-800 font-semibold underline hover:no-underline transition-all">
                                            ← Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif



                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const input = document.getElementById('payment_proof');
            const dropzone = document.getElementById('dropzone');
            const wrapper = document.getElementById('image-preview');
            const img = document.getElementById('image-preview-img');
            const removeBtn = document.getElementById('remove-file');

            if (!input || !dropzone) return;

            function handleFiles(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    if (img && wrapper) {
                        img.src = ev.target.result;
                        wrapper.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }

            // Click to open file dialog
            dropzone.addEventListener('click', function() {
                input.click();
            });

            // Drag over
            ['dragenter', 'dragover'].forEach(evt => {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('from-purple-50', 'via-blue-50', 'to-indigo-50', 'border-purple-300');
                    dropzone.classList.add('from-purple-200', 'via-blue-200', 'to-indigo-200', 'border-purple-500', 'scale-105');
                });
            });

            // Drag leave
            ['dragleave', 'dragend', 'drop'].forEach(evt => {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('from-purple-200', 'via-blue-200', 'to-indigo-200', 'border-purple-500', 'scale-105');
                    dropzone.classList.add('from-purple-50', 'via-blue-50', 'to-indigo-50', 'border-purple-300');
                });
            });

            // Drop
            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                if (!dt || !dt.files || !dt.files.length) return;
                const file = dt.files[0];
                input.files = dt.files;
                handleFiles(file);
            });

            // Change via input
            input.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                handleFiles(file);
            });

            // Remove selected file
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    input.value = '';
                    if (img) img.src = '';
                    if (wrapper) wrapper.classList.add('hidden');
                });
            }
        })();
    </script>
</x-app-layout>
