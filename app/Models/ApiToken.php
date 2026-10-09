<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'secret_key',
        'allowed_domain',
        'is_active',
        'last_used_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public static function generate(string $name, ?string $domain = null, ?int $userId = null): self
    {
        return self::create([
            'name' => $name,
            'token' => 'ps_live_' . Str::random(40),
            'secret_key' => Str::random(32),
            'allowed_domain' => $domain,
            'is_active' => true,
            'created_by' => $userId,
        ]);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
