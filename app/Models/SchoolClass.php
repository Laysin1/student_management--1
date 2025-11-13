<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'name', // e.g., "Grade 1", "Grade 2"
    ];

    // Relationship with schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
