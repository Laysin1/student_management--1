<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('teachers', function (Blueprint $table) {
      if (!Schema::hasColumn('teachers', 'subject_id')) {
        $table->unsignedBigInteger('subject_id')->nullable()->after('class_id');
      }
      if (Schema::hasColumn('teachers', 'subject')) {
        $table->dropColumn('subject'); // drop old string column
      }
    });
  }
  public function down(): void {
    Schema::table('teachers', function (Blueprint $table) {
      if (Schema::hasColumn('teachers', 'subject_id')) {
        $table->dropColumn('subject_id');
      }
      if (!Schema::hasColumn('teachers', 'subject')) {
        $table->string('subject')->nullable();
      }
    });
  }
};
