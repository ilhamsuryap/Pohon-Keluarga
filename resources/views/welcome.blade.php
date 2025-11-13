@extends('layouts.app', ['title' => $metaData['title'], 'metaData' => $metaData])

@section('content')
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

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-pulse-slow {
            animation: pulse 3s infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #c084fc, #818cf8, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(96, 165, 250, 0.2);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: float 8s ease-in-out infinite;
        }

        .hero-bg {
            background: linear-gradient(135deg, #065f46 0%, #047857 25%, #059669 50%, #10b981 75%, #34d399 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="30" cy="30" r="2"/></g></svg>');
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation: float 10s ease-in-out infinite;
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .step-number {
            position: relative;
            overflow: hidden;
        }

        .step-number::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, #a855f7, #3b82f6);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .step-number span {
            position: relative;
            z-index: 1;
        }
    </style>

    <!-- Hero Section -->
    <section
        class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-blue-500 to-purple-800">
        <!-- Floating Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1 bg-purple-400/20"></div>
            <div class="shape shape-2 bg-blue-400/20"></div>
            <div class="shape shape-3 bg-indigo-400/20"></div>
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-transparent to-black/30 backdrop-blur-sm"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                    Bangun <span class="gradient-text">Pohon Keluarga</span> Digital Anda
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 mb-12 max-w-4xl mx-auto leading-relaxed">
                    Platform terpercaya untuk menyimpan, mengelola, dan berbagi silsilah keluarga.
                    Lestarikan warisan leluhur untuk generasi mendatang dengan teknologi modern.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <div
                        class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 justify-center w-full sm:w-auto">
                        <a href="{{ auth()->check() ? route('home') : route('register') }}"
                            class="group bg-white text-purple-600 hover:bg-purple-50 px-8 sm:px-10 py-4 rounded-2xl text-lg sm:text-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-2xl w-full sm:w-auto">
                            <span class="flex items-center justify-center">
                                {{ auth()->check() ? 'Mulai Sekarang' : 'Mulai Sekarang' }}
                                <svg class="w-6 h-6 ml-2 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="#features"
                            class="glass-effect text-white hover:bg-white/20 px-8 sm:px-10 py-4 rounded-2xl text-lg sm:text-xl font-bold transition-all duration-300 transform hover:scale-105 border-2 border-white/30 text-center w-full sm:w-auto">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-purple-50 to-blue-50 relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="100" height="100"
                viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23000000" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(%23grid)" /></svg>')">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20 animate-fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Fitur <span class="gradient-text">Unggulan</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Kelola pohon keluarga dengan mudah dan aman menggunakan
                    teknologi terdepan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 px-4 sm:px-0">
                <div
                    class="feature-card card-hover bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-lg border border-purple-100 animate-slide-in-left hover:border-purple-200 transition-colors">
                    <div
                        class="feature-icon w-14 sm:w-16 h-14 sm:h-16 bg-gradient-to-br from-purple-400 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pohon Keluarga Visual</h3>
                    <p class="text-gray-600 leading-relaxed">Visualisasi pohon keluarga yang interaktif dan mudah dipahami
                        dengan tampilan yang menarik dan modern.</p>
                </div>

                <div
                    class="feature-card card-hover bg-white p-8 rounded-3xl shadow-lg border border-gray-100 animate-fade-in-up">
                    <div
                        class="feature-icon w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keamanan Data</h3>
                    <p class="text-gray-600 leading-relaxed">Data keluarga Anda tersimpan dengan aman menggunakan enkripsi
                        tingkat militer dan hanya dapat diakses oleh anggota keluarga yang berwenang.</p>
                </div>

                <div
                    class="feature-card card-hover bg-white p-8 rounded-3xl shadow-lg border border-gray-100 animate-slide-in-right">
                    <div
                        class="feature-icon w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Notifikasi WhatsApp</h3>
                    <p class="text-gray-600 leading-relaxed">Dapatkan notifikasi penting melalui WhatsApp untuk update
                        terbaru tentang keluarga Anda secara real-time.</p>
                </div>

                <div
                    class="feature-card card-hover bg-white p-8 rounded-3xl shadow-lg border border-gray-100 animate-slide-in-left">
                    <div
                        class="feature-icon w-16 h-16 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Galeri Foto</h3>
                    <p class="text-gray-600 leading-relaxed">Simpan foto-foto kenangan keluarga dengan kualitas tinggi dan
                        dokumentasikan sejarah keluarga dengan lengkap.</p>
                </div>

                <div
                    class="feature-card card-hover bg-white p-8 rounded-3xl shadow-lg border border-gray-100 animate-fade-in-up">
                    <div
                        class="feature-icon w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Akses Cepat</h3>
                    <p class="text-gray-600 leading-relaxed">Interface yang responsif dan mudah digunakan di berbagai
                        perangkat dengan performa lightning-fast, kapan saja dan dimana saja.</p>
                </div>

                <div
                    class="feature-card card-hover bg-white p-8 rounded-3xl shadow-lg border border-gray-100 animate-slide-in-right">
                    <div
                        class="feature-icon w-16 h-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran Mudah</h3>
                    <p class="text-gray-600 leading-relaxed">Sistem pembayaran yang aman dan mudah dengan multiple payment
                        gateway dan kode unik untuk memudahkan verifikasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gradient-to-br from-indigo-50 to-blue-50 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="blob absolute top-10 left-10 w-32 h-32 bg-indigo-300"></div>
            <div class="blob absolute bottom-10 right-10 w-40 h-40 bg-blue-300"></div>
            <div
                class="blob absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 bg-purple-300">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20 animate-fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Cara <span class="gradient-text">Kerja</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Mulai membangun pohon keluarga dalam 3 langkah mudah dan
                    cepat</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 px-4 sm:px-0">
                <div class="text-center animate-slide-in-left">
                    <div
                        class="step-number w-20 sm:w-24 h-20 sm:h-24 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <span class="text-2xl sm:text-3xl font-bold text-white">1</span>
                    </div>
                    <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Daftar & Bayar</h3>
                        <p class="text-gray-600 leading-relaxed">Daftar akun baru dengan mudah dan lakukan pembayaran
                            dengan sistem yang aman menggunakan kode unik yang diberikan.</p>
                    </div>
                </div>

                <div class="text-center animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div
                        class="step-number w-20 sm:w-24 h-20 sm:h-24 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <span class="text-2xl sm:text-3xl font-bold text-white">2</span>
                    </div>
                    <div
                        class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-lg border border-purple-100 hover:border-purple-200 transition-colors">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Verifikasi Admin</h3>
                        <p class="text-gray-600 leading-relaxed">Tim admin profesional kami akan memverifikasi pembayaran
                            Anda dengan cepat dan memberikan akses penuh ke platform.</p>
                    </div>
                </div>

                <div class="text-center animate-slide-in-right" style="animation-delay: 0.4s;">
                    <div
                        class="step-number w-20 sm:w-24 h-20 sm:h-24 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <span class="text-2xl sm:text-3xl font-bold text-white">3</span>
                    </div>
                    <div
                        class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-lg border border-purple-100 hover:border-purple-200 transition-colors">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Buat Pohon Keluarga</h3>
                        <p class="text-gray-600 leading-relaxed">Mulai membangun pohon keluarga digital dengan menambahkan
                            anggota keluarga, foto, dan cerita mereka.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-purple-600 via-blue-600 to-purple-800 relative overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full opacity-20">
                <div class="animate-float absolute top-20 left-20 w-20 h-20 bg-purple-300/20 rounded-full blur-xl"></div>
                <div class="animate-float absolute bottom-20 right-20 w-16 h-16 bg-blue-300/20 rounded-full blur-xl"
                    style="animation-delay: 1s;"></div>
                <div class="animate-float absolute top-1/2 left-1/4 w-12 h-12 bg-indigo-300/20 rounded-full blur-xl"
                    style="animation-delay: 2s;"></div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <div class="animate-fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    Mulai Membangun <span class="text-indigo-200">Warisan Keluarga</span> Anda
                </h2>
                <p class="text-xl text-indigo-100 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Bergabunglah dengan ribuan keluarga yang telah mempercayakan silsilah mereka kepada kami.
                    Wujudkan impian memiliki dokumentasi keluarga yang lengkap dan terorganisir.
                </p>
                <div class="animate-pulse-slow">
                    <a href="{{ auth()->check() ? route('home') : route('register') }}"
                        class="group bg-white text-indigo-600 hover:bg-indigo-50 px-12 py-5 rounded-2xl text-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-2xl inline-flex items-center">
                        {{ auth()->check() ? 'Daftar Sekarang' : 'Daftar Sekarang' }}
                        <svg class="w-6 h-6 ml-3 transition-transform group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-3xl font-bold mb-6 gradient-text">🌳 Pohon Keluarga</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed text-lg">
                        Platform digital terpercaya untuk menyimpan dan mengelola silsilah keluarga.
                        Lestarikan warisan leluhur untuk generasi mendatang dengan teknologi terdepan.
                    </p>
                    <div class="flex space-x-4">
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center hover:bg-indigo-500 transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79" />
                            </svg>
                        </div>
                        <div
                            class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center hover:bg-indigo-400 transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="animate-slide-in-left">
                    <h4 class="text-2xl font-bold mb-6 text-indigo-400">Tautan Cepat</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('login') }}"
                                class="text-gray-400 hover:text-white transition-colors duration-300 text-lg flex items-center group">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                                Masuk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}"
                                class="text-gray-400 hover:text-white transition-colors duration-300 text-lg flex items-center group">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                                Daftar
                            </a>
                        </li>
                        <li>
                            <a href="#features"
                                class="text-gray-400 hover:text-white transition-colors duration-300 text-lg flex items-center group">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                                Fitur
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="animate-slide-in-right">
                    <h4 class="text-2xl font-bold mb-6 text-indigo-400">Kontak Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-center text-gray-400 text-lg">
                            <svg class="w-6 h-6 mr-3 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>info@pohonkeluarga.com</span>
                        </li>
                        <li class="flex items-center text-gray-400 text-lg">
                            <svg class="w-6 h-6 mr-3 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                            </svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center text-gray-400 text-lg">
                            <svg class="w-6 h-6 mr-3 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Surakarta, Jawa Tengah</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-lg mb-4 md:mb-0">
                        &copy; {{ date('Y') }} Pohon Keluarga. Semua hak dilindungi undang-undang.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Kebijakan
                            Privasi</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Syarat &
                            Ketentuan</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection
