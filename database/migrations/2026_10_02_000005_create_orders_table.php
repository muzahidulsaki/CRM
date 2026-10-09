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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique(); // e.g., ORD-2026-00001
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('service_category')->index();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->default(0);
            $table->text('payment_terms')->nullable();
            
            $table->date('order_date')->index();
            $table->date('start_date')->nullable();
            $table->date('expected_delivery_date')->nullable()->index();
            
            $table->enum('status', [
                'Pending',
                'Confirmed',
                'Project Started',
                'Working',
                'Under Review',
                'Completed',
                'Delivered',
                'Cancelled',
            ])->default('Confirmed')->index();
            
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
