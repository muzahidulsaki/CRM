<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'customer_id',
        'contact_name',
        'company_name',
        'email',
        'phone',
        'whatsapp',
        'service_url',
        'order_id',
        'project_id',
        'issue_type',
        'priority',
        'status',
        'subject',
        'description',
        'attachment_path',
        'assigned_manager_id',
        'assigned_developer_id',
        'estimated_hours',
        'actual_hours',
        'internal_notes',
        'resolution_notes',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public const ISSUE_TYPES = [
        'Bug/Error',
        'Technical Support',
        'Website Issue',
        'Software Issue',
        'Server/Hosting',
        'Data Issue',
        'Customization',
        'New Feature',
        'Training/Help',
        'Other'
    ];

    public const PRIORITIES = [
        'Low',
        'Medium',
        'High',
        'Critical'
    ];

    public const STATUSES = [
        'New',
        'Assigned',
        'Working',
        'Reworking',
        'Testing',
        'Resolved',
        'Client Confirmed',
        'Closed',
        'Reopened'
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

    public function assignedManager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public function publicReplies()
    {
        return $this->hasMany(TicketReply::class)->where('is_internal', false)->orderBy('created_at', 'asc');
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'Critical' => 'bg-red-100 text-red-800 border-red-300',
            'High' => 'bg-orange-100 text-orange-800 border-orange-300',
            'Medium' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Low' => 'bg-blue-100 text-blue-800 border-blue-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'New' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'Assigned' => 'bg-purple-100 text-purple-800 border-purple-300',
            'Working' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Reworking' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Testing' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'Resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Client Confirmed' => 'bg-teal-100 text-teal-800 border-teal-300',
            'Closed' => 'bg-slate-100 text-slate-800 border-slate-300',
            'Reopened' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
