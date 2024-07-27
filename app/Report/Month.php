<?php

namespace App\Report;

use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Log;

class Month
{
    public static function initialize()
    {
        MonthlyReport::create([
            'month' => date("Y-m"),
        ]);
    }

    public static function build(String $field, int $ordersCount = 1)
    {
        $monthlyReport = MonthlyReport::where('month', date("Y-m"))->first();
        if (!empty($monthlyReport)) {
            $monthlyReport->$field += $ordersCount;
            $monthlyReport->save();
        }
    }
}