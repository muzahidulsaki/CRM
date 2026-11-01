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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_type', 50)->default('standard')->index()->after('service_category'); // creative, ads, web_software, standard
            $table->string('order_source', 100)->nullable()->after('order_type'); // Repeated, Local, Referral, etc.
            $table->string('activity_status', 50)->default('Active')->index()->after('order_source'); // Open, Active, Closed
            $table->string('payment_status', 50)->default('Due')->index()->after('due_amount'); // Paid, Due, Partially Paid
            $table->json('meta')->nullable()->after('attachments'); // Extensible storage for Creative, Ads, Soft&Web specific columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'order_source',
                'activity_status',
                'payment_status',
                'meta',
            ]);
        });
    }
};
