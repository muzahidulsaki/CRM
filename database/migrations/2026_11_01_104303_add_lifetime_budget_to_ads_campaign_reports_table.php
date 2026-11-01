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
        Schema::table('ads_campaign_reports', function (Blueprint $table) {
            $table->decimal('lifetime_budget', 10, 2)->default(0)->after('total_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_campaign_reports', function (Blueprint $table) {
            $table->dropColumn('lifetime_budget');
        });
    }
};
