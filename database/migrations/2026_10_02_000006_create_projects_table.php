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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code', 30)->unique(); // e.g., PRJ-2026-00001
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('name');
            $table->string('category')->index();
            $table->text('description')->nullable();
            
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable()->index();
            
            $table->enum('status', [
                'Pending',
                'Planning',
                'In Development',
                'In QA',
                'Client Review',
                'Completed',
                'Delivered',
                'Delayed',
                'Cancelled',
            ])->default('Pending')->index();
            
            $table->unsignedTinyInteger('progress_percent')->default(0); // 0 - 100
            
            $table->string('git_repository')->nullable();
            $table->string('live_url')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
