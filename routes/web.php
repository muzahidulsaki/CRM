<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        // Roles & Permissions
        Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');

        // Users
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // Phase 8: Automation, APIs & Webhooks (SRS Section 39, 40)
        Route::get('/automation', [\App\Http\Controllers\AutomationController::class, 'index'])->name('automation.index');
        Route::post('/automation/tokens', [\App\Http\Controllers\AutomationController::class, 'storeToken'])->name('automation.tokens.store');
        Route::patch('/automation/tokens/{token}/toggle', [\App\Http\Controllers\AutomationController::class, 'toggleToken'])->name('automation.tokens.toggle');
        Route::delete('/automation/tokens/{token}', [\App\Http\Controllers\AutomationController::class, 'destroyToken'])->name('automation.tokens.destroy');
        Route::post('/automation/run-reminders', [\App\Http\Controllers\AutomationController::class, 'runReminders'])->name('automation.reminders.run');
        Route::post('/automation/test-whatsapp', [\App\Http\Controllers\AutomationController::class, 'testWhatsApp'])->name('automation.whatsapp.test');

        // Phase 9: Database & File Backups (SRS Section 53)
        Route::get('/backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backups.destroy');
    });



    // ==========================================
    // Phase 2: CRM, Leads, Quotations & Customers
    // ==========================================
    // Leads & Pipeline
    Route::get('/leads/pipeline', [\App\Http\Controllers\LeadController::class, 'pipeline'])->name('leads.pipeline');
    Route::post('/leads/check-duplicate', [\App\Http\Controllers\LeadController::class, 'checkDuplicate'])->name('leads.check-duplicate');
    Route::post('/leads/{lead}/convert', [\App\Http\Controllers\LeadController::class, 'convert'])->name('leads.convert');
    Route::patch('/leads/{lead}/status', [\App\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::resource('leads', \App\Http\Controllers\LeadController::class);

    // Lead Follow-ups & Timeline
    Route::post('/leads/{lead}/follow-ups', [\App\Http\Controllers\LeadFollowUpController::class, 'store'])->name('leads.follow-ups.store');
    Route::patch('/follow-ups/{followUp}/complete', [\App\Http\Controllers\LeadFollowUpController::class, 'complete'])->name('follow-ups.complete');
    Route::delete('/follow-ups/{followUp}', [\App\Http\Controllers\LeadFollowUpController::class, 'destroy'])->name('follow-ups.destroy');

    // Quotations
    Route::patch('/quotations/{quotation}/status', [\App\Http\Controllers\QuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::resource('quotations', \App\Http\Controllers\QuotationController::class);

    // Customers (Customer 360°)
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);

    // ==========================================
    // Phase 3: Orders, Projects & Task Management
    // ==========================================
    // Orders
    Route::post('/orders/{order}/initialize-project', [\App\Http\Controllers\OrderController::class, 'initializeProject'])->name('orders.initialize-project');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', \App\Http\Controllers\OrderController::class);

    // Projects
    Route::patch('/projects/{project}/status', [\App\Http\Controllers\ProjectController::class, 'updateStatus'])->name('projects.update-status');
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);

    // Tasks & QA Workflow
    Route::get('/tasks/kanban', [\App\Http\Controllers\TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::post('/tasks/{task}/submit-work', [\App\Http\Controllers\TaskController::class, 'submitWork'])->name('tasks.submit-work');
    Route::post('/tasks/{task}/qa-review', [\App\Http\Controllers\TaskController::class, 'qaReview'])->name('tasks.qa-review');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);

    // ==========================================
    // Phase 4: Team, Video Editor Billing & Payouts
    // ==========================================
    // Video Editor Billing
    Route::post('/video-billings/calculate-ajax', [\App\Http\Controllers\VideoBillingController::class, 'calculateAjax'])->name('video-billings.calculate-ajax');
    Route::resource('video-billings', \App\Http\Controllers\VideoBillingController::class);

    // Employees (Team Management & Financial Ledger)
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    // Worklogs
    Route::resource('worklogs', \App\Http\Controllers\WorklogController::class)->only(['index', 'store', 'destroy']);

    // Admin & Project Manager Only: Approvals & Payouts (SRS Section 43)
    Route::middleware('role:admin,project_manager')->group(function () {
        Route::patch('/video-billings/{videoBilling}/approve', [\App\Http\Controllers\VideoBillingController::class, 'approve'])->name('video-billings.approve');
        Route::patch('/worklogs/{worklog}/approve', [\App\Http\Controllers\WorklogController::class, 'approve'])->name('worklogs.approve');
        Route::get('/payouts/pending-ajax/{employee}', [\App\Http\Controllers\EmployeePayoutController::class, 'getEmployeePendingAjax'])->name('payouts.pending-ajax');
        Route::resource('payouts', \App\Http\Controllers\EmployeePayoutController::class);
    });

    // ==========================================
    // Phase 5: Support & Ticketing System (Internal)
    // ==========================================
    Route::patch('/support-tickets/{supportTicket}/assign', [\App\Http\Controllers\SupportTicketController::class, 'assign'])->name('support-tickets.assign');
    Route::patch('/support-tickets/{supportTicket}/status', [\App\Http\Controllers\SupportTicketController::class, 'updateStatus'])->name('support-tickets.status');
    Route::post('/support-tickets/{supportTicket}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::resource('support-tickets', \App\Http\Controllers\SupportTicketController::class);

    // ==========================================
    // Phase 6: Accounts, Finance, Renewal & Expenses
    // ==========================================
    // Invoices
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);

    // Client Collections & Money Receipts
    Route::resource('client-payments', \App\Http\Controllers\ClientPaymentController::class)->except(['edit', 'update']);

    // Service Renewals & Recurring Alerts
    Route::post('/renewals/{renewal}/renew', [\App\Http\Controllers\ServiceRenewalController::class, 'renew'])->name('renewals.renew');
    Route::resource('renewals', \App\Http\Controllers\ServiceRenewalController::class);

    // Expenses
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);

    // Financial Dashboard & Cashflow (SRS Section 43: Admin, PM & Sales Manager)
    Route::middleware('role:admin,project_manager,sales_manager')->group(function () {
        Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/reports/finance', [\App\Http\Controllers\ReportController::class, 'finance'])->name('reports.finance');
    });

    // ==========================================
    // Phase 7: Business Intelligence & Reports
    // ==========================================
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/production', [\App\Http\Controllers\ReportController::class, 'production'])->name('reports.production');
    Route::get('/reports/support', [\App\Http\Controllers\ReportController::class, 'support'])->name('reports.support');
    Route::get('/reports/export/{type}', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');


    // ==========================================
    // Phase 8: Notifications
    // ==========================================
    Route::get('/notifications', [\App\Http\Controllers\AutomationController::class, 'notifications'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\AutomationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\AutomationController::class, 'markAllAsRead'])->name('notifications.read-all');

});

// ==========================================
// Phase 5: Public Client Support Portal
// ==========================================
Route::get('/support', [\App\Http\Controllers\PublicSupportController::class, 'create'])->name('public-support.create');
Route::post('/support', [\App\Http\Controllers\PublicSupportController::class, 'store'])->name('public-support.store');
Route::get('/support/track/{ticket_no?}', [\App\Http\Controllers\PublicSupportController::class, 'track'])->name('public-support.track');
Route::post('/support/track/{ticket_no}/reply', [\App\Http\Controllers\PublicSupportController::class, 'reply'])->name('public-support.reply');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

