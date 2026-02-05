<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('schedules', function (Blueprint $table) {
            foreach (['subject', 'day_of_week', 'start_time', 'end_time'] as $col) {
                if (Schema::hasColumn('schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'subject')) {
                $table->string('subject')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'day_of_week')) {
                $table->string('day_of_week')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'start_time')) {
                $table->time('start_time')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'end_time')) {
                $table->time('end_time')->nullable();
            }
        });
    }
};
