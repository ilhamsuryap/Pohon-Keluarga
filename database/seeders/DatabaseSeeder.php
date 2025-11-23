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

        // Create admin user (update if exists)
        User::updateOrCreate(
            ['email' => 'admin@pohonkeluarga.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_approved' => true,
                'payment_status' => 'paid',
                'phone' => '081234567890',
            ]
        );

        // Create default payment setting (update if exists)
        PaymentSetting::updateOrCreate(
            ['id' => 1],
            [
                'registration_fee' => 50000,
                'is_active' => true,
            ]
        );

        // Seed default configs
        $this->call(ConfigSeeder::class);
    }
}
