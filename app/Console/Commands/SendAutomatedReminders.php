<?php

namespace App\Console\Commands;

use App\Services\AutomationService;
use Illuminate\Console\Command;

class SendAutomatedReminders extends Command
{
    protected $signature = 'crm:send-reminders';

    protected $description = 'Run scheduled checks for service renewals, overdue invoices, lead follow-ups and recurring expenses (SRS Section 20, 22, 25, 32)';

    public function handle(AutomationService $automationService): int
    {
        $this->info("Running automated CRM reminder engine...");

        $stats = $automationService->runAutomatedReminders();

        $this->table(
            ['Check Type', 'Alerts Generated'],
            [
                ['Service Renewals', $stats['renewals_alerted']],
                ['Overdue Invoices', $stats['invoices_alerted']],
                ['Lead Follow-ups', $stats['followups_alerted']],
                ['Recurring Expenses', $stats['expenses_alerted']],
                ['Total Notifications', $stats['total_notifications']],
            ]
        );

        $this->info("Automated reminder checks completed successfully.");

        return Command::SUCCESS;
    }
}
