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
        Schema::create('training_hubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_category_id')->constrained('training_categories')->onDelete('cascade');
            $table->string('type'); // 'pdf' or 'video'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_hubs');
    }
};
