<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePayout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'voucher_no',
        'employee_id',
        'payout_type',
        'salary_month',
        'base_amount',
        'bonus_amount',
        'deduction_amount',
        'net_amount',
        'paid_amount',
        'payment_method',
        'account_details',
        'transaction_reference',
        'payment_date',
        'status',
        'linked_video_billing_ids',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'base_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'deduction_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'payment_date' => 'date',
            'linked_video_billing_ids' => 'array',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
