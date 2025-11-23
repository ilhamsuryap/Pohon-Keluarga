@extends('layouts.admin', ['title' => 'Profil - Admin Dashboard'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-3xl font-bold gradient-text">Profil Saya</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection

