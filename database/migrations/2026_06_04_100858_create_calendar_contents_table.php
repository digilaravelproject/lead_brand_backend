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
        Schema::create('calendar_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_year_id')->constrained('calendar_years')->onDelete('cascade');
            $table->string('language');
            $table->string('pdf_path');
            $table->timestamps();

            $table->unique(['calendar_year_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_contents');
    }
};
