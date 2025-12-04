<?php

namespace App\Report;

use App\Models\YearlyReport;
use Illuminate\Support\Facades\Log;

class Year
{
    public static function initialize()
    {
        YearlyReport::create([
            'year' => date("Y")
        ]);
    }

    public static function build(String $field, int $ordersCount = 1)
    {
        $yearlyReport = YearlyReport::firstOrCreate([
            'year' => date("Y")
        ]);
        
        $yearlyReport->$field += $ordersCount;
        $yearlyReport->save();
    }
}