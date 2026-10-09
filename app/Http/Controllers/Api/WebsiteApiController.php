<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AutomationService;
use App\Services\OrderService;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteApiController extends Controller
{
    protected AutomationService $automationService;

    public function __construct(AutomationService $automationService)
    {
        $this->automationService = $automationService;
    }

    /**
     * Ingest lead from website contact / inquiry form (SRS Section 39)
     */
    public function storeLead(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'interested_service' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:100',
            'message' => 'nullable|string',
            'notes' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'source' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN') ?: $request->query('api_token');
        $result = $this->automationService->processIncomingLead($request->all(), $token);

        $status = $result['code'] ?? ($result['success'] ? 201 : 400);

        return response()->json($result, $status);
    }

    /**
     * Ingest direct order inquiry from website checkout/order form (SRS Section 39)
     */
    public function storeOrder(Request $request, OrderService $orderService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'service_category' => 'required|string|max:100',
            'title' => 'required|string|max:200',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN') ?: $request->query('api_token');
        // Capture lead first
        $leadResult = $this->automationService->processIncomingLead([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'interested_service' => $request->service_category,
            'budget' => $request->total_amount,
            'source' => 'website_order',
            'message' => 'Direct website order: ' . $request->title . "\n" . ($request->description ?? ''),
        ], $token);

        if (!empty($leadResult['error'])) {
            return response()->json($leadResult, $leadResult['code'] ?? 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Website order received and lead captured for sales confirmation.',
            'lead_code' => $leadResult['lead_code'] ?? null,
            'lead_id' => $leadResult['lead_id'] ?? null,
        ], 201);
    }

    /**
     * Ingest support request from website public form (SRS Section 17 & 39)
     */
    public function storeSupport(Request $request, TicketService $ticketService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact_name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'subject' => 'required|string|max:255',
            'issue_type' => 'required|string|max:100',
            'description' => 'required|string',
            'priority' => 'nullable|in:Low,Medium,High,Critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket = $ticketService->createTicket($request->all());

        return response()->json([
            'success' => true,
            'ticket_no' => $ticket->ticket_no,
            'tracking_url' => route('public-support.track', $ticket->ticket_no),
            'message' => 'Support ticket created successfully.',
        ], 201);
    }
}
