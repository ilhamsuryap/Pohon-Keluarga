<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menunggu Persetujuan') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Akun Menunggu Persetujuan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Terima kasih telah mendaftar! Akun Anda sedang dalam proses review oleh admin.
                        </p>

                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Informasi Penting
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Admin akan meninjau akun Anda dalam 1-2 hari kerja</li>
                                            <li>Pastikan informasi yang Anda berikan sudah benar</li>
                                            <li>Anda akan mendapat notifikasi WhatsApp setelah akun disetujui</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status Section -->
                        @if (!Auth::user()->hasUploadedPaymentProof())
                            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Bukti Pembayaran Diperlukan
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Jumlah pembayaran: <strong>Rp {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong></p>
                                            <p>Kode unik: <strong>{{ Auth::user()->payment_code }}</strong></p>
                                            <p class="mt-2">Anda perlu mengupload bukti pembayaran untuk melanjutkan proses aktivasi akun.</p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('payment-proof.upload') }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                Upload Bukti Pembayaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'pending')
                            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Menunggu Verifikasi Pembayaran
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Bukti pembayaran Anda telah diupload pada: <strong>{{ Auth::user()->payment_proof_uploaded_at->format('d/m/Y H:i') }}</strong></p>
                                            <p>Jumlah: <strong>Rp {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong></p>
                                            <p class="mt-2">Admin sedang memverifikasi pembayaran Anda. Anda akan mendapat notifikasi WhatsApp setelah disetujui.</p>
                                        </div>
                                        <div class="mt-4 space-x-2">
                                            <a href="{{ route('payment-proof.view') }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200">
                                                Lihat Bukti Pembayaran
                                            </a>
                                            <a href="{{ route('payment-proof.upload') }}" 
                                               class="inline-flex items-center px-3 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50">
                                                Edit/Upload Ulang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'rejected')
                            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Pembayaran Ditolak
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Bukti pembayaran Anda tidak dapat diverifikasi.</p>
                                            <p>Jumlah yang harus dibayar: <strong>Rp {{ number_format(Auth::user()->payment_amount, 0, ',', '.') }}</strong></p>
                                            <p>Kode unik: <strong>{{ Auth::user()->payment_code }}</strong></p>
                                            <p class="mt-2">Silakan upload ulang bukti pembayaran yang valid.</p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('payment-proof.upload') }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                Upload Ulang Bukti Pembayaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (Auth::user()->payment_status === 'approved')
                            <div class="mt-6 bg-green-50 border border-green-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">
                                            Pembayaran Disetujui
                                        </h3>
                                        <div class="mt-2 text-sm text-green-700">
                                            <p>Pembayaran Anda telah diverifikasi dan disetujui!</p>
                                            <p>Akun Anda akan segera diaktifkan setelah persetujuan final dari admin.</p>
                                        </div>
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
