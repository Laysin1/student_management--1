<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('teachers', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('teachers', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('teachers', 'phone_number')) {
                $table->string('phone_number', 30)->nullable();
            }
            if (!Schema::hasColumn('teachers', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
            if (!Schema::hasColumn('teachers', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            }
            if (!Schema::hasColumn('teachers', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            try { $table->dropConstrainedForeignId('user_id'); } catch (\Throwable $e) {}
            try { $table->dropConstrainedForeignId('class_id'); } catch (\Throwable $e) {}
            $table->dropColumn(['first_name','last_name','phone_number','gender','profile_photo_path']);
        });
    }
};
