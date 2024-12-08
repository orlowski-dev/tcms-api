<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissionUserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'role_id'
    ];

    protected $table = 'role_permission_user_role';

    /* public $incrementing = false; */

    public $timestamps = false;
}
