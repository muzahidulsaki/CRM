<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worklog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'task_id',
        'project_id',
        'log_date',
        'hours_worked',
        'hourly_rate',
        'billable_amount',
        'work_summary',
        'status',
        'payment_status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'hours_worked' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'billable_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
