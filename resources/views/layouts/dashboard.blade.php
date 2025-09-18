<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard - Pohon Keluarga' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                                class="text-2xl font-bold text-green-600">
                                🌳 Pohon Keluarga
                            </a>
                        </div>
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'border-green-500 text-gray-900' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.users') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.users') ? 'border-green-500 text-gray-900' : '' }}">
                                    Kelola User
                                </a>
                                <a href="{{ route('admin.payment-settings') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.payment-settings') ? 'border-green-500 text-gray-900' : '' }}">
                                    Pengaturan Pembayaran
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'border-green-500 text-gray-900' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('family.tree') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('family.tree') ? 'border-green-500 text-gray-900' : '' }}">
                                    Pohon Keluarga
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        @if (auth()->user()->isAdmin())
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Admin</span>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">
                                    {{ session('info') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>

</html>
