
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('subjects', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('code')->nullable()->unique();
      $table->timestamps();
    });

    // Add subject_id to teachers
    Schema::table('teachers', function (Blueprint $table) {
      if (!Schema::hasColumn('teachers', 'subject_id')) {
        $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
      }
      // Optional: drop old subject text column if you had it
      // if (Schema::hasColumn('teachers', 'subject')) { $table->dropColumn('subject'); }
    });
  }

  public function down(): void {
    Schema::table('teachers', function (Blueprint $table) {
      try { $table->dropConstrainedForeignId('subject_id'); } catch (\Throwable $e) {}
      // $table->string('subject',100)->nullable(); // restore if needed
    });
    Schema::dropIfExists('subjects');
  }
};
