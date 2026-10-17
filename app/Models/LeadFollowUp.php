<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'customer_id',
        'user_id',
        'type',
        'scheduled_at',
        'conducted_at',
        'status',
        'subject',
        'notes',
        'next_action',
        'next_followup_date',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'conducted_at' => 'datetime',
        'next_followup_date' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'phone_call' => __('Phone Call'),
            'whatsapp' => __('WhatsApp'),
            'email' => __('Email'),
            'meeting' => __('Meeting'),
            'internal_note' => __('Internal Note'),
            default => __(ucfirst(str_replace('_', ' ', $this->type))),
        };
    }
}
