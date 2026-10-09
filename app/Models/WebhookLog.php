<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'event_type',
        'external_id',
        'sender_phone',
        'normalized_phone',
        'sender_name',
        'message_body',
        'payload',
        'status',
        'response_message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
