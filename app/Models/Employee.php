<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'department',
        'designation',
        'employment_type',
        'joining_date',
        'payment_type',
        'salary_or_rate',
        'payment_schedule',
        'emergency_contact',
        'address',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'salary_or_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videoBillings()
    {
        return $this->hasMany(VideoBilling::class);
    }

    public function worklogs()
    {
        return $this->hasMany(Worklog::class);
    }

    public function payouts()
    {
        return $this->hasMany(EmployeePayout::class);
    }

    /**
     * Total video billing amount earned
     */
    public function getTotalVideoBillingsEarnedAttribute(): float
    {
        return floatval($this->videoBillings()->where('status', 'Approved')->sum('total_payable'));
    }

    /**
     * Total payouts received
     */
    public function getTotalPayoutsAttribute(): float
    {
        return floatval($this->payouts()->where('status', 'Paid')->sum('paid_amount'));
    }

    /**
     * Current outstanding due to employee (for contractual/video editor)
     */
    public function getCurrentDueAttribute(): float
    {
        if ($this->payment_type === 'per_second_video') {
            return floatval($this->videoBillings()->where('status', 'Approved')->sum('due_amount'));
        }
        return 0.00;
    }
}
