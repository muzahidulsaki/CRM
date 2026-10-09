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
        Schema::create('video_billings', function (Blueprint $table) {
            $table->id();
            $table->string('billing_code', 30)->unique(); // e.g., VB-2026-00001
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            
            $table->string('video_title');
            $table->string('video_url')->nullable()->comment('YouTube / Drive / Deliverable link');
            
            // Duration calculation
            $table->integer('duration_minutes')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->integer('total_seconds')->default(0)->comment('Calculated: (minutes * 60) + seconds');
            
            $table->decimal('rate_per_second', 10, 2)->default(8.00)->comment('Rate in BDT per second');
            $table->integer('number_of_videos')->default(1)->comment('Video quantity count');
            
            // Financial amounts
            $table->decimal('total_payable', 14, 2)->default(0.00)->comment('total_seconds * rate_per_second * number_of_videos');
            $table->decimal('paid_amount', 14, 2)->default(0.00);
            $table->decimal('due_amount', 14, 2)->default(0.00);
            
            $table->date('work_start_date')->nullable();
            $table->date('work_end_date')->nullable();
            
            // Work Status: Draft, In Progress, Submitted, Approved, Rejected
            $table->enum('status', ['Draft', 'In Progress', 'Submitted', 'Approved', 'Rejected'])->default('Submitted')->index();
            
            // Payment Status: Unpaid, Partially Paid, Paid
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Paid'])->default('Unpaid')->index();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
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
        Schema::dropIfExists('video_billings');
    }
};
