<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->after('id');   // link to school_classes
            $table->unsignedBigInteger('teacher_id')->after('class_id'); // link to users

            // Optional: add foreign key constraints
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['class_id', 'teacher_id']);
        });
    }
};
