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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type'); // 'hot_lead', 'follow_up', 'training', 'promotion', 'lead_status', 'system_update'
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Seed sample notifications matching the user's screenshot exactly
        DB::table('notifications')->insert([
            [
                'title' => '🔥 New Hot Lead Added',
                'message' => 'Rahul Sharma has been marked as a Hot Lead. Take action now before the lead goes cold.',
                'type' => 'hot_lead',
                'is_read' => false,
                'created_at' => now()->subMinutes(2),
                'updated_at' => now()->subMinutes(2),
            ],
            [
                'title' => '📅 Follow-up Reminder',
                'message' => "You have a follow-up scheduled with Priya Mehta today at 4:00 PM. Don't forget to call her.",
                'type' => 'follow_up',
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15),
            ],
            [
                'title' => '🎬 New Training Video Added',
                'message' => 'A new video "Handling Sales Objections" has been added to the Training Hub.',
                'type' => 'training',
                'is_read' => false,
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
            [
                'title' => '🎉 Plan Promotion Live!',
                'message' => 'New combo plan posters are available. Share them now to attract more clients to the system.',
                'type' => 'promotion',
                'is_read' => true,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'title' => '✅ Lead Status Updated',
                'message' => "Anita Singh's status has been changed from \"Follow Up\" to \"Appointment\".",
                'type' => 'lead_status',
                'is_read' => true,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'title' => '📢 System Update',
                'message' => 'LeadBrandHub backend has been updated to version 1.2.0. Enjoy enhanced performance!',
                'type' => 'system_update',
                'is_read' => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
