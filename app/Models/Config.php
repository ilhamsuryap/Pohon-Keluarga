<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
    ];

    /**
     * Get config value by key
     */
    public static function get(string $key, $default = null)
    {
        $config = self::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->type) {
            'boolean' => filter_var($config->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($config->value) ? (str_contains($config->value, '.') ? (float)$config->value : (int)$config->value) : $default,
            'json' => json_decode($config->value, true) ?? $default,
            default => $config->value ?? $default,
        };
    }

    /**
     * Set config value by key
     */
    public static function set(string $key, $value, ?string $description = null, string $type = 'text'): bool
    {
        // Convert value based on type
        $storedValue = match($type) {
            'boolean' => $value ? '1' : '0',
            'number' => (string)$value,
            'json' => json_encode($value),
            default => (string)$value,
        };

        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'description' => $description,
                'type' => $type,
            ]
        ) !== null;
    }

    /**
     * Get all configs as key-value array
     */
    public static function allAsArray(): array
    {
        return self::pluck('value', 'key')->toArray();
    }
}
