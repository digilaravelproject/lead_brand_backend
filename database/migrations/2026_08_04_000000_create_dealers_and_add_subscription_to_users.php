<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number', 30);
            $table->string('alternative_phone_number', 30)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('login_password');
            $table->unsignedInteger('user_limit')->default(0);
            $table->string('referral_code', 8)->unique();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('dealer_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->timestamp('subscription_started_at')->nullable()->after('language');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');
            $table->string('approval_status', 20)->default('pending')->after('subscription_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dealer_id');
            $table->dropColumn(['subscription_started_at', 'subscription_ends_at', 'approval_status']);
        });

        Schema::dropIfExists('dealers');
    }
};
