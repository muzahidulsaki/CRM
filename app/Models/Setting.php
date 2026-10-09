<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (!$setting) {
                return $default;
            }

            if ($setting->type === 'json') {
                return json_decode($setting->value, true);
            } elseif ($setting->type === 'boolean') {
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($setting->type === 'integer') {
                return (int) $setting->value;
            } elseif ($setting->type === 'decimal') {
                return (float) $setting->value;
            }

            return $setting->value ?? $default;
        });
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'string', ?string $description = null): self
    {
        $storedValue = $value;
        if ($type === 'json' && is_array($value)) {
            $storedValue = json_encode($value);
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => (string) $storedValue,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }
}
