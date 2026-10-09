<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'renewal_no',
        'customer_id',
        'project_id',
        'service_name',
        'category',
        'billing_cycle',
        'renewal_fee',
        'last_payment_date',
        'next_due_date',
        'reminder_days',
        'status',
        'auto_renew',
        'notes',
    ];

    protected $casts = [
        'renewal_fee' => 'decimal:2',
        'last_payment_date' => 'date',
        'next_due_date' => 'date',
        'auto_renew' => 'boolean',
    ];

    public const CATEGORIES = [
        'Domain',
        'Hosting',
        'VPS/Server',
        'Software/ERP',
        'Maintenance',
        'Subscription',
        'SSL Certificate',
        'Other'
    ];

    public const CYCLES = [
        'Monthly',
        'Quarterly',
        'Half-Yearly',
        'Yearly',
        'Biennial',
        'Custom'
    ];

    public const STATUSES = [
        'Active',
        'Upcoming Due',
        'Due Today',
        'Overdue',
        'Suspended',
        'Cancelled'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Compute current alert status dynamically from next_due_date
     */
    public function getComputedStatusAttribute(): string
    {
        if (in_array($this->status, ['Suspended', 'Cancelled'])) {
            return $this->status;
        }

        $today = Carbon::today();
        $due = Carbon::parse($this->next_due_date)->startOfDay();

        if ($due->lt($today)) {
            return 'Overdue';
        }

        if ($due->equalTo($today)) {
            return 'Due Today';
        }

        $reminderThreshold = $today->copy()->addDays($this->reminder_days ?? 7);
        if ($due->lte($reminderThreshold)) {
            return 'Upcoming Due';
        }

        return 'Active';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        $status = $this->computed_status;

        return match($status) {
            'Overdue' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
            'Due Today' => 'bg-red-100 text-red-800 border-red-300 font-bold',
            'Upcoming Due' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Active' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Suspended' => 'bg-gray-100 text-gray-800 border-gray-300',
            'Cancelled' => 'bg-slate-100 text-slate-500 border-slate-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
