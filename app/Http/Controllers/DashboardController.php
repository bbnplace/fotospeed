<?php

namespace App\Http\Controllers;

use App\Models\HourlyReport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: Needs to be able to determine what records to fetch [Hourly, Daily, Monthly or Yearly]
        // TODO: Need to be able to decide whether to display Dashboard to all roles or only specific roles.
        // TODO: If user is not permitted to view report, the user's assigned task appears
        $hourlyReports = HourlyReport::orderBy('id', 'desc')->take(24)->get();
        return Inertia::render('Dashboard', [

        ]);
    }
}
