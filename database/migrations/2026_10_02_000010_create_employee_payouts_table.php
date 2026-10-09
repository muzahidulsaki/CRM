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
        Schema::create('employee_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no', 30)->unique(); // e.g., PAY-2026-00001
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            
            // Payment category
            $table->enum('payout_type', [
                'monthly_salary',
                'video_billing',
                'task_contract',
                'hourly_work',
                'advance',
                'bonus',
            ])->default('monthly_salary')->index();
            
            $table->string('salary_month', 7)->nullable()->comment('Format: YYYY-MM for monthly salary');
            
            // Financial components
            $table->decimal('base_amount', 14, 2)->default(0.00);
            $table->decimal('bonus_amount', 14, 2)->default(0.00);
            $table->decimal('deduction_amount', 14, 2)->default(0.00);
            $table->decimal('net_amount', 14, 2)->default(0.00)->comment('base + bonus - deduction');
            $table->decimal('paid_amount', 14, 2)->default(0.00);
            
            // Payment Details
            $table->enum('payment_method', ['Cash', 'bKash', 'Nagad', 'Rocket', 'Bank Transfer'])->default('bKash');
            $table->string('account_details')->nullable()->comment('Mobile number or Bank Acc no');
            $table->string('transaction_reference')->nullable();
            $table->date('payment_date');
            
            $table->enum('status', ['Draft', 'Paid', 'Cancelled'])->default('Paid')->index();
            $table->json('linked_video_billing_ids')->nullable()->comment('Array of video_billing IDs cleared');
            
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payouts');
    }
};
