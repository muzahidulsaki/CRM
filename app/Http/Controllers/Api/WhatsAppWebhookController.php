<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AutomationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    protected AutomationService $automationService;

    public function __construct(AutomationService $automationService)
    {
        $this->automationService = $automationService;
    }

    /**
     * Webhook Handshake Verification (Meta WhatsApp Cloud API Requirement)
     */
    public function verify(Request $request): Response|JsonResponse
    {
        $mode = $request->query('hub_mode') ?: $request->query('hub.mode');
        $token = $request->query('hub_verify_token') ?: $request->query('hub.verify_token');
        $challenge = $request->query('hub_challenge') ?: $request->query('hub.challenge');

        $configuredToken = config('services.whatsapp.verify_token', 'projukti_sheba_crm_verify_token');

        if ($mode === 'subscribe' && $token === $configuredToken) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['error' => 'Verification token mismatch'], 403);
    }

    /**
     * Ingest Incoming WhatsApp Event / Message (SRS Section 40)
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Process message and connect to Lead / Customer
        $result = $this->automationService->processIncomingWhatsApp($payload);

        $status = $result['code'] ?? ($result['success'] ? 200 : 400);

        return response()->json($result, $status);
    }
}
