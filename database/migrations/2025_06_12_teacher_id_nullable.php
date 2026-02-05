<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('schedules', function (Blueprint $table) {
      if (Schema::hasColumn('schedules', 'class_id')) {
        $table->unsignedBigInteger('class_id')->nullable()->change();
      }
      if (Schema::hasColumn('schedules', 'teacher_id')) {
        $table->unsignedBigInteger('teacher_id')->nullable()->change();
      }
    });
  }

  public function down(): void {
    Schema::table('schedules', function (Blueprint $table) {
      if (Schema::hasColumn('schedules', 'class_id')) {
        $table->unsignedBigInteger('class_id')->nullable(false)->change();
      }
      if (Schema::hasColumn('schedules', 'teacher_id')) {
        $table->unsignedBigInteger('teacher_id')->nullable(false)->change();
      }
    });
  }
};
