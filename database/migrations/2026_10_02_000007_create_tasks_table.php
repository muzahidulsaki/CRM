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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code', 30)->unique(); // e.g., TSK-2026-00001
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->enum('task_type', [
                'Development',
                'Video Editing',
                'Graphics Design',
                'Marketing Campaign',
                'QA / Testing',
                'Bug Fix',
                'Other',
            ])->default('Development')->index();
            
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium')->index();
            
            // Core workflow: Pending -> Working -> Reworking -> Completed -> Checked -> Delivered
            $table->enum('status', [
                'Pending',
                'Working',
                'Reworking',
                'Completed',
                'Checked',
                'Delivered',
            ])->default('Pending')->index();
            
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            
            $table->date('start_date')->nullable();
            $table->dateTime('deadline')->nullable()->index();
            
            // Submission by Developer/Editor
            $table->text('submission_notes')->nullable();
            $table->string('submission_url')->nullable();
            $table->dateTime('submitted_at')->nullable();
            
            // QA / Quality Review
            $table->foreignId('qa_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('qa_notes')->nullable();
            $table->enum('qa_status', ['Pending', 'Passed', 'Failed'])->default('Pending')->index();
            $table->dateTime('checked_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
