<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('schedules')) {
      Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->enum('type', ['class','teacher'])->nullable();
        $table->unsignedBigInteger('class_id')->nullable();
        $table->unsignedBigInteger('teacher_id')->nullable();
        $table->string('photo_path')->nullable();
        $table->timestamps();
      });
    } else {
      Schema::table('schedules', function (Blueprint $table) {
        if (!Schema::hasColumn('schedules', 'title')) $table->string('title')->nullable();
        if (!Schema::hasColumn('schedules', 'type')) $table->enum('type', ['class','teacher'])->nullable();
        if (!Schema::hasColumn('schedules', 'class_id')) $table->unsignedBigInteger('class_id')->nullable();
        if (!Schema::hasColumn('schedules', 'teacher_id')) $table->unsignedBigInteger('teacher_id')->nullable();
        if (!Schema::hasColumn('schedules', 'photo_path')) $table->string('photo_path')->nullable();
        if (!Schema::hasColumn('schedules', 'created_at')) $table->timestamps();
      });
    }
  }

  public function down(): void {
    Schema::dropIfExists('schedules');
  }
};
