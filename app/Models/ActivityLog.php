<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'record_type',
        'record_id',
        'record_title',
        'previous_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'previous_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        ?string $module = 'crm',
        ?string $description = null,
        ?Model $record = null,
        ?Model $subject = null,
        ?array $previousValues = null,
        ?array $newValues = null,
        ?int $userId = null,
        ?array $properties = null
    ): self {
        $effectiveRecord = $record ?? $subject;
        $effectiveModule = $module ?? 'crm';

        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'module' => $effectiveModule,
            'record_type' => $effectiveRecord ? get_class($effectiveRecord) : null,
            'record_id' => $effectiveRecord ? $effectiveRecord->getKey() : null,
            'record_title' => $effectiveRecord && isset($effectiveRecord->name)
                ? $effectiveRecord->name
                : ($effectiveRecord->lead_code ?? $effectiveRecord->customer_code ?? $effectiveRecord->quotation_code ?? $effectiveRecord->title ?? null),
            'previous_values' => $previousValues,
            'new_values' => $newValues ?? $properties,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
