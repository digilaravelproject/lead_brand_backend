<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create tools table
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // e.g. combo_plans, combo_posters, etc.
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
        });

        // 2. Create subtools table
        Schema::create('subtools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
        });

        // 3. Create tool_media table
        Schema::create('tool_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->foreignId('subtool_id')->nullable()->constrained('subtools')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('file_path');
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
        });

        // Seed default tools from the user screenshots
        DB::table('tools')->insert([
            [
                'title' => 'Combo Plans',
                'description' => 'Explore customized insurance combo plans',
                'icon' => 'combo_plans',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Combo Posters',
                'description' => 'Browse designs and templates',
                'icon' => 'combo_posters',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Concept Brochures',
                'description' => 'Interactive insurance concepts',
                'icon' => 'concept_brochures',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'LIC Plans',
                'description' => 'All LIC insurance products and details',
                'icon' => 'lic_plans',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Agent Recruitment',
                'description' => 'Agent recruitment materials',
                'icon' => 'agent_recruitment',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promotional Videos',
                'description' => 'Watch and share promotional videos',
                'icon' => 'promotional_videos',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Create Video Ads',
                'description' => 'Create premium short promotional videos',
                'icon' => 'create_video_ads',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Create PDF Calendar',
                'description' => 'Generate custom PDF business calendars',
                'icon' => 'create_pdf_calendar',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_media');
        Schema::dropIfExists('subtools');
        Schema::dropIfExists('tools');
    }
};
