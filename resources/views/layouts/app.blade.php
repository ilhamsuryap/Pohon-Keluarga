<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- SEO Meta Tags -->
    @if (isset($metaData))
        <meta name="description" content="{{ $metaData['description'] }}">
        <meta name="keywords" content="{{ $metaData['keywords'] }}">
        <meta name="author" content="{{ $metaData['author'] }}">
        <meta name="robots" content="{{ $metaData['robots'] }}">
        <link rel="canonical" href="{{ $metaData['canonical'] }}">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $metaData['og_title'] }}">
        <meta property="og:description" content="{{ $metaData['og_description'] }}">
        <meta property="og:image" content="{{ $metaData['og_image'] }}">
        <meta property="og:url" content="{{ $metaData['og_url'] }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaData['og_title'] }}">
        <meta name="twitter:description" content="{{ $metaData['og_description'] }}">
        <meta name="twitter:image" content="{{ $metaData['og_image'] }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- D3.js -->
    <script src="https://d3js.org/d3.v7.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white">

        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @else
            @hasSection('header')
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif
        @endif

        <!-- Page Content -->
        <main>
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>
</body>

</html>
