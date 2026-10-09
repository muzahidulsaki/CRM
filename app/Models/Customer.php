<?php

namespace App\Models;

use App\Services\PhoneHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'name',
        'company_name',
        'business_type',
        'email',
        'phone',
        'normalized_phone',
        'whatsapp',
        'normalized_whatsapp',
        'address',
        'city',
        'country',
        'website',
        'facebook',
        'instagram',
        'status',
        'created_by',
        'notes',
    ];

    protected static function booted(): void
    {
        static::saving(function ($customer) {
            $customer->normalized_phone = PhoneHelper::normalize($customer->phone) ?? '';
            if (!empty($customer->whatsapp)) {
                $customer->normalized_whatsapp = PhoneHelper::normalize($customer->whatsapp);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function clientPayments(): HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(ServiceRenewal::class);
    }

    public function getFormattedPhoneAttribute(): string
    {
        return PhoneHelper::formatDisplay($this->phone);
    }
}
