<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_PAID = 2;
    const STATUS_FAILED = 3;
    const STATUS_CANCELLED = 4;

    public static function getInvoiceStatusesArray()
    {
        $invoiceStatuses = [];
        $invoiceStatusesCollection = self::get('name');
        if(!empty($invoiceStatusesCollection))
        {
            foreach($invoiceStatusesCollection as $invoiceStatus){
                array_push($invoiceStatuses, $invoiceStatus->name);
            }
        }

        return $invoiceStatuses;
    }
}
