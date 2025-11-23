<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'key' => 'whatsapp_api_url',
                'value' => 'https://api.quods.id/api',
                'description' => 'URL API WhatsApp Quods',
                'type' => 'text',
            ],
            [
                'key' => 'whatsapp_api_key',
                'value' => 'al018FyLBRT1bwG3Z4C8gACULNZ3o5',
                'description' => 'Bearer Token API WhatsApp Quods',
                'type' => 'text',
            ],
            [
                'key' => 'whatsapp_device_key',
                'value' => 'sEVok3IhQs4avF5',
                'description' => 'Device Key API WhatsApp Quods',
                'type' => 'text',
            ],
            [
                'key' => 'whatsapp_admin_phones',
                'value' => '6285941051469', // Format: 62 + nomor (085941051469 -> 6285941051469)
                'description' => 'Nomor WhatsApp Admin (pisahkan dengan koma jika lebih dari satu)',
                'type' => 'text',
            ],
        ];

        foreach ($configs as $config) {
            Config::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}

