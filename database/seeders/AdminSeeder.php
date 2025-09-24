<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // // Create admin user
        // User::create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@pohonkeluarga.com',
        //     'password' => Hash::make('admin123'),
        //     'role' => 'admin',
        //     'is_approved' => true,
        //     'payment_status' => 'paid',
        //     'phone' => '081234567890',
        // ]);

        // // Create default payment setting
        // PaymentSetting::create([
        //     'registration_fee' => 50000,
        //     'is_active' => true,
        // ]);
    }
}