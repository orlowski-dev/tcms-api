<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /** @use HasFactory<\Database\Factories\RolePermissionFactory> */
    use HasFactory;

    protected $fillable = [
        'ability'
    ];

    public $timestamps = false;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'role_permission_user_role', 'permission_id', 'role_id');
    }
}
