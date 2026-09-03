<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['admins', 'dealers'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->decimal('price', 10, 2)->default(1000);
                $table->decimal('offer_price', 10, 2)->default(800);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number', 30)->nullable();
            $table->text('address')->nullable();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['whatsapp_number', 'address']));
        foreach (['admins', 'dealers'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn(['price', 'offer_price']));
        }
    }
};
