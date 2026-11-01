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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('token_id', 100)->nullable()->after('lead_code');
            $table->string('whatsapp_status', 50)->nullable()->after('whatsapp');
            $table->string('imo_number', 50)->nullable()->after('whatsapp_status');
            $table->string('email_status', 50)->nullable()->after('email');
            $table->string('messenger_link', 255)->nullable()->after('email_status');
            $table->string('messenger_status', 50)->nullable()->after('messenger_link');
            
            // Social channels
            $table->string('youtube', 255)->nullable()->after('instagram');
            $table->string('tiktok', 255)->nullable()->after('youtube');
            $table->string('twitter', 255)->nullable()->after('tiktok');
            $table->string('snapchat', 255)->nullable()->after('twitter');
            $table->string('linkedin', 255)->nullable()->after('snapchat');
            
            // Location & Maps (replacing single city with Current & Business country)
            $table->string('current_country', 100)->nullable()->after('country');
            $table->string('business_country', 100)->nullable()->after('current_country');
            $table->string('google_maps_link', 500)->nullable()->after('business_country');
            
            // Follow-up direct tracking
            $table->text('followup_comment')->nullable()->after('google_maps_link');
            $table->date('followup_first_date')->nullable()->after('followup_comment');
            $table->date('followup_last_date')->nullable()->after('followup_first_date');
            $table->string('followup_next_month', 50)->nullable()->after('followup_last_date');
            $table->date('followup_next_date')->nullable()->after('followup_next_month');
            
            // Meeting tracking
            $table->string('meeting_platform', 100)->nullable()->after('followup_next_date');
            $table->dateTime('next_meeting_datetime')->nullable()->after('meeting_platform');
            
            // Sales, SRS & Conversion tracking
            $table->string('srs_status', 50)->nullable()->after('next_meeting_datetime');
            $table->string('srs_number', 100)->nullable()->after('srs_status');
            $table->string('interested_products', 255)->nullable()->after('srs_number');
            $table->string('why_not_order', 255)->nullable()->after('interested_products');
            $table->string('potential_order', 255)->nullable()->after('why_not_order');
            $table->string('retentions_marketing', 100)->nullable()->after('potential_order');
            $table->string('repeat_marketing', 100)->nullable()->after('retentions_marketing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'token_id',
                'whatsapp_status',
                'imo_number',
                'email_status',
                'messenger_link',
                'messenger_status',
                'youtube',
                'tiktok',
                'twitter',
                'snapchat',
                'linkedin',
                'current_country',
                'business_country',
                'google_maps_link',
                'followup_comment',
                'followup_first_date',
                'followup_last_date',
                'followup_next_month',
                'followup_next_date',
                'meeting_platform',
                'next_meeting_datetime',
                'srs_status',
                'srs_number',
                'interested_products',
                'why_not_order',
                'potential_order',
                'retentions_marketing',
                'repeat_marketing',
            ]);
        });
    }
};
