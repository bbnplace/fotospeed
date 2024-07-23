<?php

namespace App\Report;

use App\Models\DailyReport;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPicker
{
    /**
     * Method that actually feches the Admin Reports
     */
    public static function reports(Request $request, $clientRoute, $ref='24hrs')
    {
        $startDate = DailyReport::orderBy('id', 'asc')->limit(1)->get('date');

        $knownRefs = [
            '24hrs' => 'Last 24 Hours',
            '7days' => 'Last 7 Days',
            '30days' => 'Last 30 Days',
            '90days' => 'Last 90 Days',
            'custom' => 'Custom Period',
            'this-year' => 'This Year',
            'last-year' => 'Last Year',
            'all-time' => 'All Time',
        ];

        $targetRef = isset($knownRefs[$ref]) ? $ref : '24hrs';
        $data = self::getRecords($targetRef, $request->start ?? '', $request->stop ?? '');

        return [
            'startDate' => !isset($startDate[0]) ? date('Y-m-d') : $startDate[0]->date,
            'reports' => [
                $ref => $data['chart'],
            ],
            'records' => $data['records'],
            'reportables' => $data['reportables'],
            'key' => $targetRef,
            'periods' => $knownRefs,
            'endpoint' => route('report.json', $ref),
            'exportEndpoint' => route('report.export'),
            'clientRoute' => $clientRoute,
        ];
    }

    private static function getRecords($ref, $from='', $to='')
    {
        $settings = Setting::first();
        $reportables = json_decode($settings->reportables);
        switch ($ref)
        {
            case '7days':
                return Report::get7DaysReport($reportables);
            case '30days':
                return Report::get30DaysReport($reportables);
            case '90days':
                return Report::get90DaysReport($reportables);
            case 'this-year':
                return Report::getThisYearReport($reportables);
            case 'last-year':
                return Report::getLastYearReport($reportables);
            case 'all-time':
                return Report::getAllTimeReport($reportables);
            case 'custom':
                return Report::getCustomReport($reportables, $from, $to);
            default:
                return Report::get24HoursReport($reportables);
        }
    }
}
