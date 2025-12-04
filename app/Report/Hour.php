<?php

namespace App\Report;

use App\Models\HourlyReport;
use Illuminate\Support\Facades\Log;

class Hour
{
    public static function initialize()
    {
        HourlyReport::create([
            'hour' => date("Y-m-d H"),
        ]);
    }

    public static function build(string $field, int $ordersCount = 1)
    {
        // Log::info(date("Y-m-d H"));
        $hourlyReport = HourlyReport::firstOrCreate([
            'hour' => date("Y-m-d H")
        ]);
        
        $hourlyReport->$field += $ordersCount;
        $hourlyReport->save();
    }
}