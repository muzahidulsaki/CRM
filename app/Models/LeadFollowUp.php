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
            'phone_call' => 'ফোন কল (Phone Call)',
            'whatsapp' => 'হোয়াটসঅ্যাপ (WhatsApp)',
            'email' => 'ইমেইল (Email)',
            'meeting' => 'মিটিং/ভিজিট (Meeting)',
            'internal_note' => 'অভ্যন্তরীণ নোট (Internal Note)',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
