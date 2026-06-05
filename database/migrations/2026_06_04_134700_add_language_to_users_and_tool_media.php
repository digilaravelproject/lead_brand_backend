<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('language')->default('en')->after('logo');
        });

        Schema::table('tool_media', function (Blueprint $table) {
            $table->string('language')->default('en')->after('media_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('language');
        });

        Schema::table('tool_media', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
