<?php

namespace App\Report;

use App\Models\DailyReport;
use Illuminate\Support\Facades\Log;

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
        $dailyReport = DailyReport::where('date', date("Y-m-d"))->first();
        if(!empty($dailyReport)) {
            $dailyReport->$field += $ordersCount;
            $dailyReport->save();
        }
    }
}