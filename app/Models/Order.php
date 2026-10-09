<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_code',
        'customer_id',
        'quotation_id',
        'lead_id',
        'sales_person_id',
        'project_manager_id',
        'service_category',
        'title',
        'description',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_terms',
        'order_date',
        'start_date',
        'expected_delivery_date',
        'status',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'order_date' => 'date',
        'start_date' => 'date',
        'expected_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'attachments' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function ($order) {
            $order->due_amount = max(0, floatval($order->total_amount) - floatval($order->paid_amount));
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function clientPayments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Confirmed' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'Project Started' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'Working' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
            'Under Review' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
            'Completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'Delivered' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'Cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        };
    }
}
