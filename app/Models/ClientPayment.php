<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_no',
        'invoice_id',
        'order_id',
        'customer_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'received_by_id',
        'notes',
        'attachment_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public const METHODS = [
        'Cash',
        'Bank Transfer',
        'bKash',
        'Nagad',
        'Rocket',
        'Card',
        'Cheque',
        'Other'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }
}
