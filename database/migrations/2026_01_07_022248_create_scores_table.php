<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('scores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained()->onDelete('cascade');
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
        $table->decimal('score', 5, 2);
        $table->string('term')->default('Term 1');
        $table->timestamps();

        // Add unique constraint so each student can only have one score per subject per term
        $table->unique(['student_id', 'subject_id', 'term']);
    });
}

public function down()
{
    Schema::dropIfExists('scores');
}
};
