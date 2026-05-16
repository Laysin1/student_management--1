<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentUser extends Model
{
    protected $table = 'parent_users';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'occupation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_user_id', 'student_id');
    }
}
