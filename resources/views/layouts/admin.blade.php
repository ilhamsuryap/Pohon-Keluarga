<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard - SilsilahKita' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/pohonLogo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/pohonLogo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            background: linear-gradient(135deg, #a78bfa, #818cf8, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(96, 165, 250, 0.15);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-poppins antialiased bg-gradient-to-br from-purple-50/80 via-blue-50/80 to-indigo-50/80 overflow-hidden" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex overflow-hidden">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-br from-purple-500 via-blue-500 to-indigo-600 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" @click.stop style="height: 100vh; max-height: 100vh; overflow: hidden !important;">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/20 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }" class="flex items-center space-x-3 relative z-10 group">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm p-1.5 border border-white/30 group-hover:bg-white/30 transition-all duration-200">
                        <img src="{{ asset('storage/pohonLogo.png') }}" alt="SilsilahKita Logo" class="w-full h-full object-contain rounded-lg" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-white leading-tight">SilsilahKita</span>
                        <span class="text-xs text-white/80 leading-tight">Admin Panel</span>
                    </div>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto overflow-x-hidden px-6 py-6" style="max-height: calc(100vh - 200px);">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 relative z-10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-lg border border-white/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Kelola User -->
                    <a href="{{ route('admin.users') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 relative z-10 {{ request()->routeIs('admin.users') ? 'bg-white/20 text-white shadow-lg border border-white/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span class="flex-1">Kelola User</span>
                        @if(isset($hasPendingNotifications) && $hasPendingNotifications)
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        @endif
                    </a>

                    <!-- Pengaturan Pembayaran -->
                    <a href="{{ route('admin.payment-settings') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 relative z-10 {{ request()->routeIs('admin.payment-settings') ? 'bg-white/20 text-white shadow-lg border border-white/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Pengaturan Pembayaran
                    </a>

                    <!-- Konfigurasi Sistem -->
                    <a href="{{ route('admin.config-settings') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 relative z-10 {{ request()->routeIs('admin.config-settings') ? 'bg-white/20 text-white shadow-lg border border-white/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Konfigurasi Sistem
                    </a>
                </div>
            </nav>

            <!-- User Info & Logout -->
            <div class="pt-6 pb-6 px-6 border-t border-white/20 flex-shrink-0">
                    <div class="flex items-center px-4 py-2">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/30">
                                <span
                                    class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/70">Administrator</p>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <a href="{{ route('admin.profile') }}" @click.stop @click="if (window.innerWidth < 1024) { sidebarOpen = false; }"
                            class="flex items-center px-4 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-200 relative z-10 {{ request()->routeIs('admin.profile') ? 'bg-white/20 text-white shadow-lg border border-white/30' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden" style="height: 100vh; max-height: 100vh;">
            <!-- Top Header -->
            <header class="bg-white/80 backdrop-blur-sm shadow-lg border-b border-white/20 lg:hidden">
                <div class="flex items-center justify-between h-16 px-4">
                    <button @click="sidebarOpen = true" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-white p-1.5 shadow-md border border-gray-100 group-hover:shadow-lg transition-all duration-200">
                            <img src="{{ asset('storage/pohonLogo.png') }}" alt="SilsilahKita Logo" class="w-full h-full object-contain rounded-lg" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold gradient-text leading-tight">SilsilahKita</span>
                            <span class="text-xs text-gray-500 leading-tight">Admin Panel</span>
                        </div>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50/90 to-emerald-50/90 border border-green-200/60 rounded-2xl p-4 shadow-lg animate-fade-in-up">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-gradient-to-r from-red-50/90 to-rose-50/90 border border-red-200/60 rounded-2xl p-4 shadow-lg animate-fade-in-up">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-700">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-6 bg-gradient-to-r from-blue-50/90 to-indigo-50/90 border border-blue-200/60 rounded-2xl p-4 shadow-lg animate-fade-in-up">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-700">
                                    {{ session('info') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
        @click="sidebarOpen = false" x-cloak></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>

</html>
