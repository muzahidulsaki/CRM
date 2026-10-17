<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoBilling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'billing_code',
        'employee_id',
        'project_id',
        'task_id',
        'customer_id',
        'video_title',
        'video_url',
        'duration_minutes',
        'duration_seconds',
        'total_seconds',
        'rate_per_second',
        'number_of_videos',
        'total_payable',
        'paid_amount',
        'due_amount',
        'work_start_date',
        'work_end_date',
        'status',
        'payment_status',
        'approved_by',
        'approved_at',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'duration_seconds' => 'integer',
            'total_seconds' => 'integer',
            'rate_per_second' => 'decimal:2',
            'number_of_videos' => 'integer',
            'total_payable' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
            'work_start_date' => 'date',
            'work_end_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Formatted duration as MM:SS or HH:MM:SS
     */
    public function getFormattedDurationAttribute(): string
    {
        $mins = floor($this->total_seconds / 60);
        $secs = $this->total_seconds % 60;
        return sprintf('%02dm %02ds (%s sec)', $mins, $secs, number_format($this->total_seconds));
    }
}
