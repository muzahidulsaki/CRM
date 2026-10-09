<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_no',
        'category',
        'title',
        'amount',
        'expense_date',
        'payment_method',
        'paid_to',
        'paid_by_id',
        'is_recurring',
        'recurring_frequency',
        'due_date',
        'status',
        'attachment_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'due_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public const CATEGORIES = [
        'Office Rent',
        'Internet',
        'Electricity',
        'VPS/Server',
        'Hosting',
        'Domain',
        'Software/Tools',
        'Advertising',
        'Marketing',
        'Office Supplies',
        'Travel',
        'Employee Salary',
        'Contractual Payment',
        'Other'
    ];

    public const METHODS = [
        'Cash',
        'Bank Transfer',
        'bKash',
        'Nagad',
        'Rocket',
        'Card',
        'Other'
    ];

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by_id');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'Paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Overdue' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
