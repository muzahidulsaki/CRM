<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsCampaignReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_code',
        'lead_id',
        'customer_id',
        'order_id',
        'order_source',
        'activity',
        'total_amount',
        'total_dollar',
        'ads_post_link',
        'business_category',
        'business_name',
        'whatsapp',
        'order_month',
        'order_date',
        'end_date',
        'order_details',
        'starting_date',
        'total_days',
        'daily_cost',
        'previous_dollar',
        'running_dollar',
        'total_dollar_cost',
        'dollar_due',
        'today_check',
        'today_checked_at',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'end_date' => 'date',
        'starting_date' => 'date',
        'today_checked_at' => 'date',
        'total_amount' => 'decimal:2',
        'total_dollar' => 'decimal:2',
        'daily_cost' => 'decimal:2',
        'previous_dollar' => 'decimal:2',
        'running_dollar' => 'decimal:2',
        'total_dollar_cost' => 'decimal:2',
        'dollar_due' => 'decimal:2',
        'total_days' => 'integer',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Auto-calculate total days and dollar calculations before saving if not explicitly set
     */
    protected static function booted()
    {
        static::saving(function ($report) {
            if ($report->starting_date && $report->end_date && !$report->total_days) {
                $report->total_days = max(1, $report->starting_date->diffInDays($report->end_date) + 1);
            }

            if ($report->daily_cost > 0 && $report->total_days > 0 && !$report->total_dollar_cost) {
                $report->total_dollar_cost = $report->daily_cost * $report->total_days;
            }

            if ($report->total_dollar > 0 && $report->running_dollar >= 0) {
                $report->dollar_due = max(0, $report->total_dollar - $report->running_dollar);
            }
        });
    }
}
