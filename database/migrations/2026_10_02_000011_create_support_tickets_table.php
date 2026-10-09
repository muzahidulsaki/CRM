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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no', 50)->unique()->index();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('contact_name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('whatsapp')->nullable();
            $table->string('service_url')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            
            $table->string('issue_type')->default('Technical Support');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', [
                'New',
                'Assigned',
                'Working',
                'Reworking',
                'Testing',
                'Resolved',
                'Client Confirmed',
                'Closed',
                'Reopened'
            ])->default('New');
            
            $table->string('subject');
            $table->text('description');
            $table->string('attachment_path')->nullable();
            
            $table->foreignId('assigned_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_developer_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('resolution_notes')->nullable();
            
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
