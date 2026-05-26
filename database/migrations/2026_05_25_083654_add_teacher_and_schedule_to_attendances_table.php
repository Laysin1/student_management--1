<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->unsignedBigInteger('teacher_id')->nullable()->after('student_id');
        $table->unsignedBigInteger('schedule_id')->nullable()->after('class_id');
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropColumn(['teacher_id', 'schedule_id']);
    });
}
};
