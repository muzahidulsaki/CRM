<?php

use App\Models\Order;
use App\Services\FinanceService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $financeService = app(FinanceService::class);

        Order::where('order_type', 'ads')->chunkById(100, function ($orders) use ($financeService) {
            foreach ($orders as $order) {
                $total = floatval($order->total_amount);
                $receiptSum = floatval($order->clientPayments()->sum('amount'));

                if ($total <= 0) {
                    continue;
                }

                // First align paid_amount with recorded receipts to prevent double counting
                if (abs(floatval($order->paid_amount) - $receiptSum) > 0.01) {
                    $order->update(['paid_amount' => $receiptSum]);
                }

                $diff = round($total - $receiptSum, 2);
                if ($diff > 0) {
                    $financeService->recordClientPayment([
                        'customer_id' => $order->customer_id,
                        'order_id' => $order->id,
                        'amount' => $diff,
                        'payment_date' => $order->order_date?->format('Y-m-d') ?? now()->toDateString(),
                        'payment_method' => 'Cash',
                        'received_by_id' => $order->sales_person_id,
                        'notes' => "Auto Ads Campaign full settlement for Order {$order->order_code}",
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        // Data settlement migration is intentionally non-destructive on rollback.
    }
};

