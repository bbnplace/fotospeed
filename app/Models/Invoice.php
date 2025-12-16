<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'invoice_status_id',
        'description',
        'track_id',
        'customer_payment_proof',
        'refunded',
        'refund_amount',
        'refund_points',
        'refunded_points',
        'refund_account_name',
        'refund_account_number',
        'refund_bank_name',
        'refund_transaction_reference',
        'refunded_by',
        'refunded_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceStatus()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }
}
