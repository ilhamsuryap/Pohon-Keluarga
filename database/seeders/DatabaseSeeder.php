<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pohonkeluarga.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_approved' => true,
            'payment_status' => 'paid',
            'phone' => '081234567890',
        ]);

        // Create default payment setting
        PaymentSetting::create([
            'registration_fee' => 50000,
            'is_active' => true,
        ]);
    }
}
