<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'report_date',
        'work_mode',
        'hours_worked',
        'tasks_completed',
        'in_progress_tasks',
        'challenges',
        'tomorrow_plan',
        'leads_contacted',
        'meetings_done',
        'orders_closed',
        'status',
        'manager_feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'reviewed_at' => 'datetime',
        'hours_worked' => 'decimal:1',
        'leads_contacted' => 'integer',
        'meetings_done' => 'integer',
        'orders_closed' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

