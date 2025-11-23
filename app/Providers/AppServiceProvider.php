<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share admin notification data to admin layout
        View::composer('layouts.admin', function ($view) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                $pendingApprovals = User::where('is_approved', false)->count();
                $pendingPayments = User::where('payment_status', 'pending')
                    ->whereNotNull('payment_proof')
                    ->count();
                
                $view->with([
                    'pendingApprovals' => $pendingApprovals,
                    'pendingPayments' => $pendingPayments,
                    'hasPendingNotifications' => $pendingApprovals > 0 || $pendingPayments > 0,
                ]);
            } else {
                // Set default values for non-admin users
                $view->with([
                    'pendingApprovals' => 0,
                    'pendingPayments' => 0,
                    'hasPendingNotifications' => false,
                ]);
            }
        });
    }
}
