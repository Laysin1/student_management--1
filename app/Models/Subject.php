<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'subject_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'subject_id', 'id');
    }
}
