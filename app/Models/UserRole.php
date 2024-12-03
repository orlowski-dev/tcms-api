<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoleFactory> */
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(RolePermission::class, 'role_permission_user_role', 'role_id', 'permission_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
