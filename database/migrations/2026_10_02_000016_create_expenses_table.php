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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_no', 50)->unique()->index();
            $table->string('category')->default('Other');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->enum('payment_method', [
                'Cash',
                'Bank Transfer',
                'bKash',
                'Nagad',
                'Rocket',
                'Card',
                'Other'
            ])->default('Cash');
            $table->string('paid_to')->nullable();
            $table->foreignId('paid_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['Monthly', 'Quarterly', 'Yearly'])->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['Paid', 'Pending', 'Overdue'])->default('Paid');
            $table->string('attachment_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
