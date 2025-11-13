<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Bukti Pembayaran') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div
                            class="mb-4 bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 text-purple-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Informasi Pembayaran -->
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
                                <p class="font-semibold text-lg text-purple-600">Rp
                                    {{ number_format($user->payment_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kode Unik</p>
                                <p class="font-semibold text-lg text-blue-600">{{ $user->payment_code }}</p>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">
                                <strong>Penting:</strong> Transfer sesuai nominal di atas (termasuk kode unik),
                                lalu upload bukti transfer di bawah ini.
                            </p>
                        </div>
                    </div>

                    <!-- Informasi Rekening -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Rekening Pembayaran</h3>
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

                    @if ($user->hasUploadedPaymentProof())
                        <!-- Bukti Pembayaran Saat Ini -->
                        <div
                            class="mb-8 bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-purple-800 mb-4">Bukti Pembayaran Saat Ini</h3>
                            <div class="mb-4">
                                <img src="{{ $user->getPaymentProofUrl() }}" alt="Bukti Pembayaran"
                                    class="max-w-md rounded-lg shadow">
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Diupload pada:
                                {{ optional($user->payment_proof_uploaded_at)->format('d/m/Y H:i') }}</p>

                            @if ($user->payment_status === 'pending')
                                <div
                                    class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-4">
                                    Menunggu verifikasi admin.
                                </div>
                            @elseif ($user->payment_status === 'rejected')
                                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                                    Ditolak. Silakan upload ulang bukti pembayaran yang valid.
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('payment-proof.view') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Lihat
                                    Detail</a>
                                @if ($user->payment_status !== 'approved')
                                    <form method="POST" action="{{ route('payment-proof.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded"
                                            onclick="return confirm('Hapus bukti pembayaran dan upload ulang?')">Hapus &
                                            Upload Ulang</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Form Upload Bukti Transfer -->
                        <div class="mb-10">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Bukti Pembayaran</h3>

                            <form method="POST" action="{{ route('payment-proof.store') }}"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="payment_proof"
                                        class="block text-sm font-medium text-gray-700 mb-2">Pilih / Seret & Lepas Bukti
                                        Pembayaran</label>

                                    <!-- Dropzone -->
                                    <div id="dropzone"
                                        class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition cursor-pointer border-blue-300">
                                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Seret dan lepas gambar di sini, atau <span
                                                class="text-blue-600 underline">klik untuk pilih</span></p>
                                        <p class="text-xs text-gray-500">JPEG, PNG, JPG (maks. 5MB)</p>
                                    </div>

                                    <input type="file" id="payment_proof" name="payment_proof" accept="image/*"
                                        class="hidden" required>
                                    @error('payment_proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div id="image-preview" class="mt-4 hidden">
                                        <div class="flex items-start gap-4">
                                            <img id="image-preview-img" src="" alt="Preview"
                                                class="max-w-xs rounded-lg shadow">
                                            <div class="space-y-2">
                                                <button type="button" id="remove-file"
                                                    class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                                                    Hapus File
                                                </button>
                                                <p class="text-xs text-gray-500">Pastikan informasi pada bukti transfer
                                                    terlihat jelas.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg">Upload</button>
                                    <a href="{{ route('pending-approval') }}"
                                        class="text-gray-600 hover:text-gray-800 underline">Kembali</a>
                                </div>
                            </form>
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
                    dropzone.classList.add('bg-blue-50', 'border-blue-500');
                });
            });

            // Drag leave
            ['dragleave', 'dragend', 'drop'].forEach(evt => {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('bg-blue-50', 'border-blue-500');
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
