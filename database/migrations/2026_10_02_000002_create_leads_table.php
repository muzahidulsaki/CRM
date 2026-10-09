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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code', 30)->unique(); // e.g. LD-2026-00001
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->index();
            $table->string('normalized_phone', 20)->index();
            $table->string('whatsapp', 30)->nullable();
            $table->string('normalized_whatsapp', 20)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->text('other_socials')->nullable();
            
            // CRM classification
            $table->string('lead_source')->default('Manual Entry')->index();
            $table->string('interested_service')->nullable()->index();
            $table->decimal('budget', 14, 2)->nullable();
            $table->decimal('discussed_amount', 14, 2)->nullable();
            
            // Pipeline & Temperature
            $table->enum('lead_temperature', ['Hot', 'Warm', 'Cold'])->default('Warm')->index();
            $table->enum('lead_status', [
                'New',
                'Contacted',
                'Interested',
                'Meeting/Demo',
                'Proposal Sent',
                'Negotiation',
                'Won',
                'Lost'
            ])->default('New')->index();
            
            // Assignment & Follow-up
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('expected_work_date')->nullable();
            $table->dateTime('last_contact_date')->nullable();
            $table->dateTime('next_followup_date')->nullable()->index();
            $table->text('lost_reason')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
