<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
/**
 * @method bool update(array $attributes = [], array $options = [])
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the teacher record associated with this user.
     */
    public function teacher()
    {
        return $this->hasOne(\App\Models\Teacher::class);
    }

    /**
     * Get the student record associated with this user.
     */
    public function student()
    {
        return $this->hasOne(\App\Models\Student::class);
    }
}
