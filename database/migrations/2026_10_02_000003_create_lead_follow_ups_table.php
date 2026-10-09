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
        Schema::create('lead_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // staff who made or scheduled this
            $table->enum('type', ['phone_call', 'whatsapp', 'email', 'meeting', 'internal_note'])->default('phone_call')->index();
            $table->dateTime('scheduled_at')->nullable()->index();
            $table->dateTime('conducted_at')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'missed', 'cancelled'])->default('scheduled')->index();
            $table->string('subject')->nullable();
            $table->text('notes');
            $table->string('next_action')->nullable();
            $table->dateTime('next_followup_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_follow_ups');
    }
};
