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
        Schema::create('ads_campaign_reports', function (Blueprint $table) {
            $table->id();

            // 1. Lead ID (Excel: Lead ID)
            $table->string('lead_code')->nullable()->index();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            // 2. Order Source (Excel: Order Source)
            $table->string('order_source')->nullable();

            // 3. Activity (Excel: Activity - Active, Paused, Completed)
            $table->string('activity')->default('Active')->index();

            // 4. Total Amount (Excel: Total Amount in BDT)
            $table->decimal('total_amount', 12, 2)->default(0);

            // 5. Total Dollar (Excel: Total Dollar $)
            $table->decimal('total_dollar', 10, 2)->default(0);

            // 6. Ads Post Link (Excel: Ads Post Link)
            $table->text('ads_post_link')->nullable();

            // 7. Business Category (Excel: Business Category)
            $table->string('business_category')->nullable()->index();

            // 8. Business Name (Excel: Business Name)
            $table->string('business_name')->nullable()->index();

            // 9. Whatsapp (Excel: Whatsapp)
            $table->string('whatsapp')->nullable()->index();

            // 10. Order Month (Excel: Order Month)
            $table->string('order_month')->nullable()->index();

            // 11. Order Date (Excel: Order Date)
            $table->date('order_date')->nullable();

            // 12. End Date (Excel: End Date)
            $table->date('end_date')->nullable();

            // 13. Order Details (Excel: Order Details)
            $table->text('order_details')->nullable();

            // 14. Starting Date (Excel: Starting Date)
            $table->date('starting_date')->nullable();

            // 15. Total Days (Excel: Total Days)
            $table->integer('total_days')->default(0);

            // 16. Daily Cost (Excel: Daily Cost $)
            $table->decimal('daily_cost', 10, 2)->default(0);

            // 17. Previous Dollar (Excel: Previous Dollar $)
            $table->decimal('previous_dollar', 10, 2)->default(0);

            // 18. Running Dollar (Excel: Runnng Dollar $)
            $table->decimal('running_dollar', 10, 2)->default(0);

            // 19. Total $ Cost (Excel: Total $ Cost)
            $table->decimal('total_dollar_cost', 10, 2)->default(0);

            // 20. $ Due (Excel: $ Due)
            $table->decimal('dollar_due', 10, 2)->default(0);

            // 21. Today Check (Excel: Today Check - Checked, Attention, Pending)
            $table->string('today_check')->nullable()->index();
            $table->date('today_checked_at')->nullable();

            // Meta & Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_campaign_reports');
    }
};
