<?php

use App\Http\Controllers\Api\WebsiteApiController;
use App\Http\Controllers\Api\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

// Health / Ping
Route::get('/v1/ping', function () {
    return response()->json([
        'status' => 'online',
        'app' => config('app.name', 'Projukti Sheba CRM'),
        'timestamp' => now()->toIso8601String(),
    ]);
});

// ==========================================
// Phase 8: Website REST API (SRS Section 39)
// ==========================================
Route::prefix('v1')->group(function () {
    Route::post('/leads', [WebsiteApiController::class, 'storeLead'])->name('api.leads.store');
    Route::post('/orders', [WebsiteApiController::class, 'storeOrder'])->name('api.orders.store');
    Route::post('/support', [WebsiteApiController::class, 'storeSupport'])->name('api.support.store');

    // ==========================================
    // Phase 8: WhatsApp Webhook (SRS Section 40)
    // ==========================================
    Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify'])->name('api.whatsapp.verify');
    Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])->name('api.whatsapp.handle');
});
