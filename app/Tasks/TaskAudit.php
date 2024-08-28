<?php

namespace App\Tasks;
use App\Models\CustomerFeedback;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use Illuminate\Support\Facades\Log;

class TaskAudit
{
    public static function checkAll(array $checks, $order): array
    {
        $auditables = [];
        foreach ($checks as $check) {
            $auditables[$check] = self::matchVerifiableTask($check, $order);
        }
        return $auditables;
    }

    public static function getVerifiableTasks()
    {
        $verifiableTasks = [
            'Order Number Input',
            'Waybill Number Input',
            'Invoice Generation',
            'Invoice Payment',
            'Price Input',
            'Customer Feedback',
        ];
        sort($verifiableTasks);
        return $verifiableTasks;
    }

    public static function matchVerifiableTask(string $check, $order): bool
    {
        switch ($check) {
            case 'Order Number Input':
                return self::checkOrderNumber($order);
            case 'Waybill Number Input':
               return self::checkWaybillNumber($order);
            case 'Invoice Generation':
                return self::checkInvoiceGenerated($order);
            case 'Invoice Payment':
               return self::checkInvoicePaid($order);
            case 'Price Input':
            case 'Price Set':
                return self::checkPriceSet($order);
            case 'Customer Feedback':
                return self::checkCustomerFeedback($order);
            default:
                return false;
        }
    }

    public static function checkCustomerFeedback($order): bool
    {
        return !empty(CustomerFeedback::where('created_at', '>', $order->created_at)->first());
    }

    public static function checkOrderNumber($order): bool
    {
        return !empty($order->order_number);
    }

    public static function checkPriceSet($order): bool
    {
        return !empty($order->total_cost);
    }

    public static function checkInvoiceGenerated($order): bool
    {
        $orderInvoice = Invoice::where("order_id", $order->id)->first();
        return !empty($orderInvoice);
    }

    public static function checkInvoicePaid($order): bool
    {
        $orderInvoice = Invoice::where("order_id", $order->id)->first();
        return $orderInvoice->invoice_status_id == InvoiceStatus::STATUS_PAID;
    }

    public static function checkWaybillNumber($order): bool
    {
        return !empty($order->waybill_number);
    }
}