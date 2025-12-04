<?php

namespace App\Report;

use App\Models\DailyReport;

class Day
{
    public static function initialize()
    {
        DailyReport::create([
            'date' => date('Y-m-d'),
        ]);
    }

    public static function build(String $field, int $ordersCount = 1)
    {
        $dailyReport = DailyReport::firstOrCreate([
            'date' => date("Y-m-d")
        ]);
        
        $dailyReport->$field += $ordersCount;
        $dailyReport->save();
    }
}