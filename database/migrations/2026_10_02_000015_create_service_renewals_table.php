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
        Schema::create('service_renewals', function (Blueprint $table) {
            $table->id();
            $table->string('renewal_no', 50)->unique()->index();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            
            $table->string('service_name');
            $table->enum('category', [
                'Domain',
                'Hosting',
                'VPS/Server',
                'Software/ERP',
                'Maintenance',
                'Subscription',
                'SSL Certificate',
                'Other'
            ])->default('Domain');
            
            $table->enum('billing_cycle', [
                'Monthly',
                'Quarterly',
                'Half-Yearly',
                'Yearly',
                'Biennial',
                'Custom'
            ])->default('Yearly');
            
            $table->decimal('renewal_fee', 12, 2);
            $table->date('last_payment_date')->nullable();
            $table->date('next_due_date')->index();
            $table->integer('reminder_days')->default(7);
            
            $table->enum('status', [
                'Active',
                'Upcoming Due',
                'Due Today',
                'Overdue',
                'Suspended',
                'Cancelled'
            ])->default('Active');
            
            $table->boolean('auto_renew')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_renewals');
    }
};
