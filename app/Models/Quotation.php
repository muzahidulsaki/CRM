<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_code',
        'lead_id',
        'customer_id',
        'created_by',
        'quotation_date',
        'valid_until',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_terms',
        'delivery_terms',
        'notes',
        'converted_to_order_at',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'converted_to_order_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->total_amount = max(0, $this->subtotal - $this->discount_amount + $this->tax_amount);
        $this->save();
    }
}
