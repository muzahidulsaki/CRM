<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'avatar',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function assignedLeads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function createdLeads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function followUps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadFollowUp::class);
    }

    public function quotations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quotation::class, 'created_by');
    }

    public function managedOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'project_manager_id');
    }

    public function managedProjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function assignedTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($this->role->slug, $roles);
        }

        return $this->role->slug === $roles;
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->hasRole('admin')) {
            return true; // Admin has full access
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->permissions->contains('slug', $permissionSlug);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
