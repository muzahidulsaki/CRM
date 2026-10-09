<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_code',
        'order_id',
        'customer_id',
        'project_manager_id',
        'name',
        'category',
        'description',
        'start_date',
        'deadline',
        'status',
        'progress_percent',
        'git_repository',
        'live_url',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'progress_percent' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'bg-gray-100 text-gray-800',
            'Planning' => 'bg-purple-100 text-purple-800',
            'In Development' => 'bg-indigo-100 text-indigo-800',
            'In QA' => 'bg-amber-100 text-amber-800',
            'Client Review' => 'bg-cyan-100 text-cyan-800',
            'Completed' => 'bg-emerald-100 text-emerald-800',
            'Delivered' => 'bg-teal-100 text-teal-800',
            'Delayed' => 'bg-rose-100 text-rose-800',
            'Cancelled' => 'bg-slate-100 text-slate-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
