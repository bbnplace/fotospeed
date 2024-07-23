<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\HourlyReport;
use App\Models\Setting;
use App\Report\ReportPicker;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, $ref='24hrs')
    {
        $settings = Setting::first();
        if (empty($settings->reports_permission)) {
            $settings->reportables = json_encode(["Received"]);
            $settings->reports_permission = json_encode(["Administrator", "System Admin"]);
            $settings->save();
        }
        
        $reportViewers = json_decode($settings->reports_permission);
        if (in_array(auth()->user()->role->name, $reportViewers)) {
            // TODO: Needs to be able to determine what records to fetch [Hourly, Daily, Monthly or Yearly]
            // TODO: Need to be able to decide whether to display Dashboard to all roles or only specific roles.
            $hourlyReports = HourlyReport::orderBy('id', 'desc')->take(24)->get();
            return Inertia::render('Dashboard', [
                $this->getReports($request, $ref)
            ]);
        } else {
            // TODO: If user is not permitted to view report, the user's assigned task appears
        }
        
        
    }

    public function home(Request $request, $ref='24hrs')
    {
        return $this->report($request, $ref);
    }

    public function report(Request $request, $ref='24hrs')
    {
        // This data is used to form the dropwn on the Dashboard View

        return Inertia::render('Dashboard', $this->getReports($request, $ref))->withViewData([
            'layout' => 'app.auth.layout'
        ]);
    }

    /**
     * Method used by customer to fetch their report
     */
    public function getReports(Request $request, $ref='24hrs')
    {
        return ReportPicker::reports($request, auth()->user(), 'report', $ref);
    }


    /**
     * Export Report
     */
    public function export(Request $request)
    {
        $period = $request->period ?? "24hrs";

        switch($period){
            case '7days':
                $stopDate = Carbon::now();
                $startDate = Carbon::now()->subDays(7);
                break;
            case '30days':
                $stopDate = Carbon::now();
                $startDate = Carbon::now()->subDays(30);
                break;
            case '90days':
                $stopDate = Carbon::now();
                $startDate = Carbon::now()->subDays(90);
                break;
            case 'this-year':
                $year = date("Y");
                $startDate = $year . "-01-01 00:00";
                $stopDate = Carbon::now();
                break;
            case 'last-year':
                $year = date("Y") - 1;
                $startDate = Carbon::parse($year . "-01-01");
                $stopDate = Carbon::parse($year . "-12-31 23:59:59");
                break;
            case 'all-time':
                $stopDate = Carbon::now();
                $dailyReportStart = DailyReport::orderBy('id', 'asc')->limit(1)->get('date');
                $startDate = $dailyReportStart->date;
                break;
            case 'custom':
                $startDate = $request->start;
                $stopDate = $request->stop;
                break;
            default: // Default and 24 Hours
                $stopDate = Carbon::now();
                $startDate = Carbon::now()->subDays(1);
                break;
        }

        // return response()->json([
        //     'message' => sprintf('Period: %s, Start Date: %s, Stop Date: %s', $period, $startDate, $stopDate)
        // ]);

        // Queue the task
        // dispatch(new GenerateSMSReportFile($startDate, $stopDate, auth()->user()))
        //     ->onQueue('reports');

        return response()->json([
            'message' => sprintf("Processing Request. Report File will be sent to %s.", auth()->user()->email)
        ]);
    }
}
