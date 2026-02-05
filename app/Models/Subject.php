<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name','code'];

    public function teachers()
    {
        return $this->hasMany(\App\Models\Teacher::class);
    }
    public function scores()
{
    return $this->hasMany(\App\Models\Score::class);
}
}
