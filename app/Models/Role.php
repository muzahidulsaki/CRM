<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function givePermission(string $permissionSlug): void
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        if ($permission && !$this->permissions()->where('permission_id', $permission->id)->exists()) {
            $this->permissions()->attach($permission->id);
        }
    }
}
