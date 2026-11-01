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
        Schema::create('employee_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            
            $table->date('report_date')->index();
            $table->string('work_mode')->default('Office'); // Office, Remote, Field, Hybrid
            $table->decimal('hours_worked', 4, 1)->default(8.0);
            
            // Core report sections
            $table->text('tasks_completed');
            $table->text('in_progress_tasks')->nullable();
            $table->text('challenges')->nullable();
            $table->text('tomorrow_plan')->nullable();
            
            // Quick CRM activity counts logged today
            $table->integer('leads_contacted')->default(0);
            $table->integer('meetings_done')->default(0);
            $table->integer('orders_closed')->default(0);
            
            // Status and Review
            $table->enum('status', ['Submitted', 'Reviewed', 'Needs Improvement'])->default('Submitted')->index();
            $table->text('manager_feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();

            $table->unique(['user_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_daily_reports');
    }
};
