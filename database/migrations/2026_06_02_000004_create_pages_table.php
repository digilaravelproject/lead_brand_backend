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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->unique();
            $table->string('page_type')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
            $table->timestamps();
        });

        // insert default static pages
        DB::table('pages')->insert([
            [
                'page_name' => 'privacy_policy',
                'page_type' => 'privacy_policy',
                'description' => '<h2>Privacy Policy</h2><p>Your privacy policy content here.</p>',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_name' => 'terms_condition',
                'page_type' => 'terms_condition',
                'description' => '<h2>Terms & Conditions</h2><p>Your terms and conditions content here.</p>',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_name' => 'about_us',
                'page_type' => 'about_us',
                'description' => '<h2>About Us</h2><p>About us content here.</p>',
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
        Schema::dropIfExists('pages');
    }
};
