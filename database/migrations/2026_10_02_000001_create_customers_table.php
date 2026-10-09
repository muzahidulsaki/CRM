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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 30)->unique(); // e.g., CUST-2026-00001
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->index();
            $table->string('normalized_phone', 20)->index(); // digits only for reliable duplicate detection
            $table->string('whatsapp', 30)->nullable();
            $table->string('normalized_whatsapp', 20)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('customers');
    }
};
