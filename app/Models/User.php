<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }

    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            RolePermission::class,  // Final model (permissions)
            RolePermissionUserRole::class,  // Intermediate pivot model
            'role_id',  // Foreign key on pivot table (role_id)
            'id',  // Foreign key on permissions table (permission_id)
            'role_id',  // Local key on users table (role_id)
            'permission_id'  // Local key on pivot table (permission_id)
        );
    }

    public function getAbilities(): array
    {
        return $this->role->permissions->pluck('ability')->toArray();
    }

    public function hasAbility(string $ability): bool
    {
        return in_array($ability, $this->getAbilities(), true);
    }

    public function hasAnyAbility(array $abilities): bool
    {
        $userAbilities = $this->getAbilities();
        $hasAbility = false;

        foreach ($abilities as $ability) {
            if (in_array($ability, $userAbilities, true)) {
                $hasAbility = true;
                break;
            }
        }

        return $hasAbility;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
}
