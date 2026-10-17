<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\WebhookLog;
use App\Models\CrmNotification;
use App\Services\AutomationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{
    protected AutomationService $automationService;

    public function __construct(AutomationService $automationService)
    {
        $this->automationService = $automationService;
    }

    /**
     * Automation & API Control Center
     */
    public function index()
    {
        $tokens = ApiToken::with('creator')->latest()->get();
        $webhookLogs = WebhookLog::latest()->take(20)->get();
        $recentNotifications = CrmNotification::latest()->take(15)->get();

        return view('automation.index', compact('tokens', 'webhookLogs', 'recentNotifications'));
    }

    /**
     * Generate new API Token for external website integration
     */
    public function storeToken(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'allowed_domain' => 'nullable|string|max:255',
        ]);

        $token = ApiToken::generate($request->name, $request->allowed_domain, Auth::id());

        return redirect()->route('automation.index')->with('success', "API Token '{$token->name}' created: {$token->token}");
    }

    /**
     * Toggle Token active status
     */
    public function toggleToken(ApiToken $token)
    {
        $token->update(['is_active' => !$token->is_active]);
        $status = $token->is_active ? 'Activated' : 'Deactivated';

        return redirect()->route('automation.index')->with('success', "API Token '{$token->name}' successfully {$status}.");
    }

    /**
     * Delete API Token
     */
    public function destroyToken(ApiToken $token)
    {
        $token->delete();

        return redirect()->route('automation.index')->with('success', 'API Token deleted successfully.');
    }

    /**
     * Manually trigger automated reminder engine
     */
    public function runReminders()
    {
        $stats = $this->automationService->runAutomatedReminders();

        $msg = "Reminder engine executed successfully! (Renewals: {$stats['renewals_alerted']}, Overdue Invoices: {$stats['invoices_alerted']}, Follow-ups: {$stats['followups_alerted']}, Total Alerts: {$stats['total_notifications']})";

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Simulate incoming WhatsApp message for testing and verification
     */
    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string|max:100',
            'sender_phone' => 'required|string|max:50',
            'message' => 'required|string',
        ]);

        $payload = [
            'id' => 'sim_wa_' . uniqid(),
            'from' => $request->sender_phone,
            'name' => $request->sender_name,
            'message' => $request->message,
        ];

        $result = $this->automationService->processIncomingWhatsApp($payload);

        return redirect()->route('automation.index')->with('success', "WhatsApp message simulation successful: " . ($result['message'] ?? 'OK'));
    }

    /**
     * Notifications Center
     */
    public function notifications(Request $request)
    {
        $query = CrmNotification::latest();

        if ($request->filter === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20);
        $unreadCount = CrmNotification::where('is_read', false)->count();

        return view('automation.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(CrmNotification $notification)
    {
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        CrmNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
