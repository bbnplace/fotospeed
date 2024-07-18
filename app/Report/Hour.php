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

    public static function build(String $field, int $ordersCount = 1)
    {
        $hourlyReport = HourlyReport::where('hour', date("Y-m-d H"))->first();
        $hourlyReport->$field += $ordersCount;
        $hourlyReport->save();
    }
}