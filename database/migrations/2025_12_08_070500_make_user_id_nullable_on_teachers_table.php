<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id_tmp')->nullable()->after('id');
        });

        DB::statement('UPDATE teachers SET user_id_tmp = user_id');

        Schema::table('teachers', function (Blueprint $table) {
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
            $table->dropColumn('user_id');
            $table->renameColumn('user_id_tmp', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
            $table->unsignedBigInteger('user_id_tmp')->nullable(false)->after('id');
        });

        DB::statement('UPDATE teachers SET user_id_tmp = user_id');

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->renameColumn('user_id_tmp', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }
};
