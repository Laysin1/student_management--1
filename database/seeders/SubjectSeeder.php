<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Math', 'code' => 'MATH'],
            ['name' => 'Algebra', 'code' => 'ALG'],
            ['name' => 'Geometry', 'code' => 'GEO'],
            ['name' => 'English', 'code' => 'ENG'],
            ['name' => 'Literature', 'code' => 'LIT'],
            ['name' => 'Art', 'code' => 'ART'],
            ['name' => 'Biology', 'code' => 'BIO'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Physics', 'code' => 'PHYS'],
            ['name' => 'Science', 'code' => 'SCI'],
            ['name' => 'History', 'code' => 'HIS'],
            ['name' => 'Geography', 'code' => 'GEO'],
            ['name' => 'Foreign Language', 'code' => 'LANG'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Physical Education', 'code' => 'PE'],
        ] as $s) {
            Subject::firstOrCreate(['name'=>$s['name']], $s);
        }
    }
}
