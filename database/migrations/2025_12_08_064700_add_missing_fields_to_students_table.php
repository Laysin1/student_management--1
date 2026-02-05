<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'student_id')) {
                $table->string('student_id', 50)->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('students', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('students', 'phone_number')) {
                $table->string('phone_number', 30)->nullable()->after('age');
            }
            if (!Schema::hasColumn('students', 'parent_number')) {
                $table->string('parent_number', 30)->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('students', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('class_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['student_id','age','phone_number','parent_number','profile_photo_path']);
        });
    }
};
