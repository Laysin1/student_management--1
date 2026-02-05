<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'subject')) {
                $table->string('subject')->nullable()->change();
            }
        });
    }

    public function down(): void {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'subject')) {
                $table->string('subject')->nullable(false)->change();
            }
        });
    }
};
