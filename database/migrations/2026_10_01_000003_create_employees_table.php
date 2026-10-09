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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('employee_code')->unique();
            $table->string('department');
            $table->string('designation');
            $table->enum('employment_type', ['permanent', 'contractual', 'freelance'])->default('permanent');
            $table->date('joining_date')->nullable();
            $table->enum('payment_type', ['monthly_salary', 'per_second_video', 'per_task', 'hourly'])->default('monthly_salary');
            $table->decimal('salary_or_rate', 12, 2)->default(0.00)->comment('Monthly salary amount or per-second video rate / per-task rate');
            $table->string('payment_schedule')->default('monthly')->comment('monthly, weekly, per_work');
            $table->string('emergency_contact')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
