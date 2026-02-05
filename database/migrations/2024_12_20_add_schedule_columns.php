<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('schedules', 'day_of_week')) {
                $table->string('day_of_week')->nullable()->after('class_id');
            }
            if (!Schema::hasColumn('schedules', 'start_time')) {
                $table->time('start_time')->nullable()->after('day_of_week');
            }
            if (!Schema::hasColumn('schedules', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('schedules', 'room')) {
                $table->string('room')->nullable()->after('end_time');
            }
            if (!Schema::hasColumn('schedules', 'subject')) {
                $table->string('subject')->nullable()->after('room');
            }
            if (!Schema::hasColumn('schedules', 'photo')) {
                $table->string('photo')->nullable()->after('subject');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $columns = ['day_of_week', 'start_time', 'end_time', 'room', 'subject', 'photo'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('schedules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
