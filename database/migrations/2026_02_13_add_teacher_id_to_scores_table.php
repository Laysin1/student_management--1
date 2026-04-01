<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherIdToScoresTable2026 extends Migration
{
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (!Schema::hasColumn('scores', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('class_id');
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (Schema::hasColumn('scores', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
        });
    }
}
