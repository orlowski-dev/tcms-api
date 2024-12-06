<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'phone_number',
        'address',
        'city',
        'postal_code'
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
