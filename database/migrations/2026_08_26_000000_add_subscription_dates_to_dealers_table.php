<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->timestamp('subscription_started_at')->nullable()->after('is_active');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');
        });

        DB::table('dealers')->orderBy('id')->each(function (object $dealer): void {
            $startedAt = $dealer->created_at ?? now();

            DB::table('dealers')->where('id', $dealer->id)->update([
                'subscription_started_at' => $startedAt,
                'subscription_ends_at' => Carbon::parse($startedAt)->addYear(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn(['subscription_started_at', 'subscription_ends_at']);
        });
    }
};
