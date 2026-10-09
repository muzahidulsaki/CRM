<?php

namespace App\Models;

use App\Services\OrderService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_code',
        'project_id',
        'assigned_to',
        'assigned_by',
        'title',
        'description',
        'task_type',
        'priority',
        'status',
        'estimated_hours',
        'actual_hours',
        'start_date',
        'deadline',
        'submission_notes',
        'submission_url',
        'submitted_at',
        'qa_by',
        'qa_notes',
        'qa_status',
        'checked_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'datetime',
        'submitted_at' => 'datetime',
        'checked_at' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        // Whenever a task status is changed or saved, sync progress on its project
        static::saved(function ($task) {
            if ($task->project) {
                OrderService::syncProjectProgress($task->project);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function qaUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qa_by');
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'Urgent' => 'bg-rose-100 text-rose-800 border-rose-200',
            'High' => 'bg-orange-100 text-orange-800 border-orange-200',
            'Medium' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Low' => 'bg-slate-100 text-slate-800 border-slate-200',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'bg-gray-100 text-gray-700',
            'Working' => 'bg-indigo-100 text-indigo-700',
            'Reworking' => 'bg-rose-100 text-rose-700 font-bold',
            'Completed' => 'bg-amber-100 text-amber-800 font-bold',
            'Checked' => 'bg-emerald-100 text-emerald-800 font-bold',
            'Delivered' => 'bg-teal-100 text-teal-800 font-bold',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
