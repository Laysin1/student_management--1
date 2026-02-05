<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing scores table if it exists
        Schema::dropIfExists('scores');

        // Create new scores table with proper structure
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->integer('first_semester')->nullable();
            $table->integer('second_semester')->nullable();
            $table->integer('final_score')->nullable();
            $table->enum('grade', ['A', 'B', 'C', 'D', 'F'])->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->default(date('Y'));
            $table->timestamps();

            $table->unique(['student_id', 'class_id', 'month']);
            $table->index(['class_id', 'month']);
            $table->index(['student_id']);
        });
    }    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
