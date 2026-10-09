<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'order_id',
        'project_id',
        'issue_date',
        'due_date',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'notes',
        'terms',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public const STATUSES = [
        'Draft',
        'Sent',
        'Partially Paid',
        'Paid',
        'Overdue',
        'Cancelled'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class)->orderBy('payment_date', 'desc');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'Paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Partially Paid' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Sent' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Overdue' => 'bg-rose-100 text-rose-800 border-rose-300',
            'Draft' => 'bg-slate-100 text-slate-800 border-slate-300',
            'Cancelled' => 'bg-gray-100 text-gray-500 border-gray-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
