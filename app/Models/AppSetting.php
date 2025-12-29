<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getString(string $key, ?string $default = null): ?string
    {
        $row = static::query()->where('key', $key)->first();
        if (!$row) {
            return $default;
        }

        return $row->value ?? $default;
    }

    public static function getDecimal(string $key, float $default = 0): float
    {
        $raw = static::getString($key, null);
        if ($raw === null || trim($raw) === '') {
            return $default;
        }

        return (float) $raw;
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

