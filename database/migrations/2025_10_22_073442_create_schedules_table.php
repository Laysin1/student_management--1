<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('class_id');      // foreign key to classes table
    $table->unsignedBigInteger('teacher_id');    // foreign key to teachers table
    $table->string('subject');
    $table->enum('day', ['Mon','Tue','Wed','Thu','Fri','Sat']);
    $table->string('time_slot');                 // e.g., "7:00-8:00"
    $table->timestamps();

    $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
    $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
