<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. API Tokens for external websites (SRS Section 39)
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token', 80)->unique();
            $table->string('secret_key', 64)->nullable();
            $table->string('allowed_domain')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Webhook Logs for WhatsApp & Third-party integrations (SRS Section 40)
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->default('whatsapp'); // whatsapp, website_api, custom
            $table->string('event_type', 50)->nullable();
            $table->string('external_id', 150)->nullable()->index(); // for deduplication (WhatsApp message ID)
            $table->string('sender_phone', 50)->nullable()->index();
            $table->string('normalized_phone', 50)->nullable()->index();
            $table->string('sender_name', 150)->nullable();
            $table->text('message_body')->nullable();
            $table->json('payload')->nullable();
            $table->string('status', 30)->default('processed'); // processed, duplicate, failed
            $table->text('response_message')->nullable();
            $table->timestamps();
        });

        // 3. Central System Notifications (SRS Section 32 & 20)
        Schema::create('crm_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 50); // renewal_alert, invoice_overdue, follow_up, new_lead, whatsapp_msg
            $table->string('title');
            $table->text('message');
            $table->string('link_url')->nullable();
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_notifications');
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('api_tokens');
    }
};
