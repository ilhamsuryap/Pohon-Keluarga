@extends('layouts.admin', ['title' => 'Admin Dashboard - Pohon Keluarga'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-3xl font-bold gradient-text">Dashboard Admin</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola sistem pohon keluarga dengan mudah</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-hover bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl rounded-3xl border border-purple-100/60 animate-fade-in-up">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-300 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total User</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl rounded-3xl border border-yellow-100/60 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-300 to-orange-400 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Menunggu Persetujuan</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $pendingApprovals }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl rounded-3xl border border-green-100/60 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-300 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Keluarga</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalFamilies }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl rounded-3xl border border-red-100/60 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-300 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pembayaran Pending</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $pendingPayments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-2xl font-bold gradient-text mb-6">Aksi Cepat</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.users') }}" class="relative group card-hover bg-gradient-to-br from-blue-50/80 to-indigo-50/80 p-8 rounded-3xl border border-blue-100/60 shadow-lg hover:border-blue-200/60">
                    <div>
                        <span class="rounded-2xl inline-flex p-4 bg-gradient-to-br from-blue-300 to-blue-500 text-white shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Kelola User
                        </h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Setujui pendaftaran dan kelola user
                        </p>
                    </div>
                    @if($pendingApprovals > 0)
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-400 to-pink-400 text-white shadow-lg">
                            {{ $pendingApprovals }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.payment-settings') }}" class="relative group card-hover bg-gradient-to-br from-green-50/80 to-emerald-50/80 p-8 rounded-3xl border border-green-100/60 shadow-lg hover:border-green-200/60">
                    <div>
                        <span class="rounded-2xl inline-flex p-4 bg-gradient-to-br from-green-300 to-emerald-500 text-white shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Pengaturan Pembayaran
                        </h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Atur biaya pendaftaran
                        </p>
                    </div>
                </a>

                <a href="{{ route('admin.config-settings') }}" class="relative group card-hover bg-gradient-to-br from-purple-50/80 to-indigo-50/80 p-8 rounded-3xl border border-purple-100/60 shadow-lg hover:border-purple-200/60">
                    <div>
                        <span class="rounded-2xl inline-flex p-4 bg-gradient-to-br from-purple-300 to-indigo-500 text-white shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Konfigurasi Sistem
                        </h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Atur WhatsApp API & nomor admin
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @if($pendingApprovals > 0)
    <div class="bg-gradient-to-r from-yellow-50/80 to-orange-50/80 shadow-2xl rounded-3xl border border-yellow-200/60 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Perlu Perhatian</h3>
            
            <div class="bg-gradient-to-r from-yellow-100/80 to-orange-100/80 border-l-4 border-yellow-400 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-300 to-orange-400 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-medium text-gray-800">
                            Ada <strong class="text-yellow-600">{{ $pendingApprovals }}</strong> user yang menunggu persetujuan. 
                            <a href="{{ route('admin.users') }}" class="font-bold underline text-purple-500 hover:text-purple-600 transition-colors">
                                Lihat sekarang →
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection