<?php

namespace App\Models;

use App\Services\PhoneHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_code',
        'token_id',
        'customer_id',
        'name',
        'company_name',
        'business_type',
        'email',
        'email_status',
        'phone',
        'normalized_phone',
        'whatsapp',
        'normalized_whatsapp',
        'whatsapp_status',
        'imo_number',
        'messenger_link',
        'messenger_status',
        'address',
        'city',
        'country',
        'current_country',
        'business_country',
        'google_maps_link',
        'website',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'twitter',
        'snapchat',
        'linkedin',
        'other_socials',
        'followup_comment',
        'followup_first_date',
        'followup_last_date',
        'followup_next_month',
        'followup_next_date',
        'meeting_platform',
        'next_meeting_datetime',
        'srs_status',
        'srs_number',
        'interested_products',
        'why_not_order',
        'potential_order',
        'retentions_marketing',
        'repeat_marketing',
        'lead_source',
        'interested_service',
        'budget',
        'discussed_amount',
        'lead_temperature',
        'lead_status',
        'assigned_to',
        'expected_work_date',
        'last_contact_date',
        'next_followup_date',
        'lost_reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'discussed_amount' => 'decimal:2',
        'expected_work_date' => 'date',
        'last_contact_date' => 'datetime',
        'next_followup_date' => 'datetime',
        'followup_first_date' => 'date',
        'followup_last_date' => 'date',
        'followup_next_date' => 'date',
        'next_meeting_datetime' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function ($lead) {
            $lead->normalized_phone = PhoneHelper::normalize($lead->phone) ?? '';
            if (!empty($lead->whatsapp)) {
                $lead->normalized_whatsapp = PhoneHelper::normalize($lead->whatsapp);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class)->orderBy('scheduled_at', 'desc');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class)->orderBy('id', 'desc');
    }

    public function getFormattedPhoneAttribute(): string
    {
        return PhoneHelper::formatDisplay($this->phone);
    }

    public function getTemperatureBadgeClassAttribute(): string
    {
        return match ($this->lead_temperature) {
            'Hot' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400',
            'Warm' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400',
            'Cold' => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-900/30 dark:text-sky-400',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->lead_status) {
            'New' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'Contacted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'Interested' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
            'Meeting/Demo' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'Proposal Sent' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            'Negotiation' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
            'Won' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            'Lost' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    /**
     * Compute real-time count of prior orders for this lead/customer
     */
    public function getExistingOrderCountsAttribute(): array
    {
        $customerIds = [];
        if ($this->customer_id) {
            $customerIds[] = $this->customer_id;
        }

        $phones = array_filter([$this->phone, $this->normalized_phone, $this->whatsapp, $this->normalized_whatsapp]);
        if (!empty($phones)) {
            $matchedCustomerIds = Customer::where(function ($q) use ($phones) {
                $q->whereIn('phone', $phones)
                  ->orWhereIn('normalized_phone', $phones)
                  ->orWhereIn('whatsapp', $phones)
                  ->orWhereIn('normalized_whatsapp', $phones);
            })->pluck('id')->toArray();
            $customerIds = array_unique(array_merge($customerIds, $matchedCustomerIds));
        }

        $orders = Order::query()->where(function ($q) use ($customerIds) {
            $q->where('lead_id', $this->id);
            if (!empty($customerIds)) {
                $q->orWhereIn('customer_id', $customerIds);
            }
        })->get(['id', 'service_category']);

        $counts = [
            'erp' => 0,
            'website' => 0,
            'video' => 0,
            'graphics' => 0,
            'smm' => 0,
            'ads' => 0,
            'others' => 0,
            'total' => $orders->count(),
        ];

        foreach ($orders as $order) {
            $cat = strtolower($order->service_category ?? '');
            if (str_contains($cat, 'erp') || str_contains($cat, 'software')) {
                $counts['erp']++;
            } elseif (str_contains($cat, 'web')) {
                $counts['website']++;
            } elseif (str_contains($cat, 'video')) {
                $counts['video']++;
            } elseif (str_contains($cat, 'graphic')) {
                $counts['graphics']++;
            } elseif (str_contains($cat, 'smm') || str_contains($cat, 'social')) {
                $counts['smm']++;
            } elseif (str_contains($cat, 'ad')) {
                $counts['ads']++;
            } else {
                $counts['others']++;
            }
        }

        return $counts;
    }

    /**
     * Get user-facing display name when client name is empty.
     */
    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->name) && $this->name !== '-' && $this->name !== 'N/A') {
            return $this->name;
        }

        return $this->company_name ?: ($this->phone ? 'Client (' . $this->phone . ')' : 'Client (' . $this->lead_code . ')');
    }
}
