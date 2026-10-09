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
        Schema::create('worklogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            
            $table->date('log_date');
            $table->decimal('hours_worked', 5, 2)->default(0.00);
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->decimal('billable_amount', 14, 2)->default(0.00);
            
            $table->text('work_summary');
            $table->enum('status', ['Logged', 'Approved', 'Rejected'])->default('Logged')->index();
            $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid')->index();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worklogs');
    }
};
