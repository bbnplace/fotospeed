<?php

namespace App\Report;

use App\Models\DailyReport;
use App\Models\HourlyReport;
use App\Models\MonthlyReport;
use App\Models\YearlyReport;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class Report
{
    public static function get24HoursReport(array $reportables): array
    {
        return self::getHourlyReport($reportables, 24);
    }

    public static function get7DaysReport(array $reportables): array
    {
        return self::getDailyReport($reportables, 7);
    }

    public static function get30DaysReport(array $reportables): array
    {
        return self::getDailyReport($reportables, 30);
    }

    public static function get90DaysReport(array $reportables): array
    {
        return self::getDailyReport($reportables, 90);
    }

    public static function getCustomReport(array $reportables, string $start, string $end): array
    {
        if (empty($start) || empty($end)) {
            return [
                'chart' => [
                    'labels'=> [],
                    'datasets' => [],
                ],
                'records' => [],
                'reportables' => $reportables,
                'totals' => []
            ];
        }

        // dd($start);
        $startDate = Carbon::parse($start);
        $stopDate = Carbon::parse($end);
        $currentDate = Carbon::now();

        // Calculate the difference in days
        $numberOfDays = $stopDate->diffInDays($startDate);
        $numberOfMonths = $stopDate->diffInMonths($startDate);
        $daysSinceStopDate = $currentDate->diffInDays($stopDate);


        switch(true)
        {
            case $numberOfDays <= 2 && $daysSinceStopDate <= 2: // Hourly Report
                $record = HourlyReport::where('hour', '>=', $startDate->format('Y-m-d H'))
                    ->where('hour', '<=', $stopDate->format('Y-m-d H'))
                    ->orderBy('id', 'asc')
                    ->get(['hour', ...$reportables]);
                $period = "hour";
                break;
            case $numberOfDays <= 61: // Daily Report
                $record = DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
                    ->where('date', '<=', $stopDate->format('Y-m-d'))
                    ->orderBy('id', 'asc')
                    ->get(['date', ...$reportables]);
                $period = "date";
                break;
            case $numberOfDays < (365 * 3): // Monthly Report
                $record = MonthlyReport::where('month', '>=', $startDate->format('Y-m'))
                    ->where('month', '<=', $stopDate->format('Y-m'))
                    ->orderBy('id', 'asc')
                    ->get(['month', ...$reportables]);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::where('year', '>=', $startDate->format('Y'))
                    ->where('year', '<=', $stopDate->format('Y'))
                    ->orderBy('id', 'asc')
                    ->get(['year', ...$reportables]);
                $period = "year";
        }

            return self::generateLineChartData($reportables, $record, $period);
    }



    public static function getThisYearReport(array $reportables): array
    {
        $currentDate = Carbon::now();
        $dayOfYear = $currentDate->dayOfYear;
        $monthOfYear = $currentDate->month();

        switch(true)
        {
            case $dayOfYear == 1: // Hourly
                $period = 'hour';
                $record = HourlyReport::where('hour', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get(['hour', ...$reportables]);
                $record = count($record) < $dayOfYear ? self::augmentHourlyReport($reportables, $record, $dayOfYear) : $record;
                break;
            case $dayOfYear < 31: // Daily
                $period = 'date';
                $record = DailyReport::where('date', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get(['date', ...$reportables]);
                $record = count($record) < $dayOfYear ? self::augmentDailyReport($reportables, $record, $dayOfYear) : $record;
                break;
            default: // Monthly
                $period = 'month';
                $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get(['month', ...$reportables]);
        }

        return self::generateLineChartData($reportables, $record, $period);
    }

    public static function getLastYearReport(array $reportables): array
    {
        // For this, need to check the date a person signed up for the service. If date is

        $period = 'month';
        $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y') - 1))
            ->orderBy('id', 'asc')
            ->get(['month', ...$reportables]);
        if (count($record)) {
            $record = self::augmentMonthlyReport($reportables, $record, 12 - count($record));
        } else {
            $minDate = sprintf("%d-01", date("Y"));

            $additionalRecords = [];
            for ($i=12; $i > 0; $i--) {
                array_push($additionalRecords, [
                    'month' => self::getPastMonth($minDate, $i),
                    ...self::initializeReportables($reportables),
                ]);
            }

            $collection = collect($additionalRecords);
            $record = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return self::generateLineChartData($reportables, $record, $period);
    }

    public static function getAllTimeReport(array $reportables): array
    {
        $record = DailyReport::orderBy('id', 'asc')->limit(1)
            ->get(['date']);
        $startDate = $record[0]->date;

        $referenceDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $currentDate = Carbon::now();
        $daysSince = $currentDate->diffInDays($referenceDate);

        switch(true)
        {
            case $daysSince <= 48: // Hourly Report
                $record = HourlyReport::orderBy('id', 'asc')
                    ->get(['hour', ...$reportables]);
                $period = "hour";
                break;
            case $daysSince <= 31: // Daily Report
                $record = DailyReport::orderBy('id', 'asc')
                    ->get(['date', ...$reportables]);
                $period = "date";
                break;
            case $daysSince < (365 * 3): // Monthly Report
                $record = MonthlyReport::orderBy('id', 'asc')
                    ->get(['month', ...$reportables]);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::orderBy('id', 'asc')
                    ->get(['year', ...$reportables]);
                $period = "year";
        }

        return self::generateLineChartData($reportables, $record, $period);
    }


    private static function augmentMonthlyReport(array $reportables, $record, $months)
    {
        // 1. Create a map of existing records indexed by month
        $existingRecords = [];
        foreach ($record as $item) {
            $existingRecords[$item->month] = $item;
        }

        // 2. Generate the full timeline of expected months
        $fullTimeline = [];
        $currentDate = Carbon::now('Africa/Lagos');

        for ($i = $months - 1; $i >= 0; $i--) {
            $targetDate = $currentDate->copy()->subMonths($i);
            $monthKey = $targetDate->format('Y-m');

            if (isset($existingRecords[$monthKey])) {
                $fullTimeline[] = $existingRecords[$monthKey];
            } else {
                $emptyRecord = [
                    'month' => $monthKey,
                    ...self::initializeReportables($reportables),
                ];
                $fullTimeline[] = (object)$emptyRecord;
            }
        }

        return collect($fullTimeline);
    }


    private static function augmentHourlyReport(array $reportables, $record, $hours)
    {
        // 1. Create a map of existing records indexed by hour
        $existingRecords = [];
        foreach ($record as $item) {
            $existingRecords[$item->hour] = $item;
        }

        // 2. Generate the full timeline of expected hours
        $fullTimeline = [];
        $currentDate = Carbon::now('Africa/Lagos'); // Using the timezone from getPastHour
        
        // We want the last $hours hours, ending with the current hour
        for ($i = $hours - 1; $i >= 0; $i--) {
            $targetDate = $currentDate->copy()->subHours($i);
            $hourKey = $targetDate->format('Y-m-d H');
            
            if (isset($existingRecords[$hourKey])) {
                $fullTimeline[] = $existingRecords[$hourKey];
            } else {
                // Create a zero-filled record
                $emptyRecord = [
                    'hour' => $hourKey,
                    ...self::initializeReportables($reportables),
                ];
                $fullTimeline[] = (object)$emptyRecord;
            }
        }

        return collect($fullTimeline);
    }


    private static function getHourlyReport(array $reportables, int $hours)
    {
        $record = self::getHourlyReportData($reportables, $hours);
        // Always augment to ensure gaps are filled
        return self::generateLineChartData($reportables, self::augmentHourlyReport($reportables, $record, $hours), 'hour');
    }


    private static function getPastHour($initialTime, $hourDifference)
    {

        $carbonDate = Carbon::createFromFormat('Y-m-d H', $initialTime, 'Africa/Lagos');
        $carbonDate->subHours($hourDifference);
        return $carbonDate->format('Y-m-d H');
    }


    private static function getPastDay($initialTime, $daysDifference)
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $initialTime, 'Africa/Lagos');
        $carbonDate->subDays($daysDifference);
        return $carbonDate->format('Y-m-d');
    }


    private static function getPastMonth($initialTime, $monthsDifference)
    {
        $carbonDate = Carbon::createFromFormat('Y-m', $initialTime, 'Africa/Lagos');
        $carbonDate->subMonths($monthsDifference);
        return $carbonDate->format('Y-m');
    }

    private static function initializeReportables(array $reportables)
    {
        $reportable = [];
        if(!empty($reportables))
        {
            foreach ($reportables as $value) {
                $reportable[$value] = 0;
            }
        }

        return $reportable;
    }


    private static function augmentDailyReport(array $reportables, $record, $days)
    {
        // 1. Create a map of existing records indexed by date
        $existingRecords = [];
        foreach ($record as $item) {
            $existingRecords[$item->date] = $item;
        }

        // 2. Generate the full timeline of expected days
        $fullTimeline = [];
        $currentDate = Carbon::now('Africa/Lagos');

        for ($i = $days - 1; $i >= 0; $i--) {
            $targetDate = $currentDate->copy()->subDays($i);
            $dateKey = $targetDate->format('Y-m-d');

            if (isset($existingRecords[$dateKey])) {
                $fullTimeline[] = $existingRecords[$dateKey];
            } else {
                $emptyRecord = [
                    'date' => $dateKey,
                    ...self::initializeReportables($reportables),
                ];
                $fullTimeline[] = (object)$emptyRecord;
            }
        }

        return collect($fullTimeline);
    }


    private static function getDailyReport(array $reportables, int $days): array
    {
        $record = self::getDailyReportData($reportables, $days);
        // Always augment to ensure gaps are filled
        return self::generateLineChartData($reportables, self::augmentDailyReport($reportables, $record, $days), 'date');
    }


    private static function getMonthlyReport(array $reportables, int $months): array
    {
        $record = self::getMonthlyReportData($reportables, $months);
        // Always augment to ensure gaps are filled
        return self::generateLineChartData($reportables, self::augmentMonthlyReport($reportables, $record, $months), 'month');
    }

    private static function generateLineChartData(array $reportables, $records, $xkey): array
    {
        $data = [];
        $labels = [];
        $datasets = [];
        $lineColors = ['#FF0000', '#FF7F00', '#FFFF00', '#00FF00','#0000FF', '#4B0082', '#8B00FF', '#FF69B4', '#FF4500', '#FFD700','#00FA9A', '#00CED1', '#8A2BE2', '#FF1493', '#1E90FF'];

        foreach ($reportables as $value)
        {
            $data[$value] = [];
        }

        if (count($records) > 0) {
            $finalRecord = [];
            $fieldTotals = [];
            foreach ($records->toarray() as $record) {
                $localRecord = (array) $record;
                foreach ($record as $key => $value) {
                    if ($key == $xkey) {
                        switch ($xkey) {
                            case 'hour':
                                $date = Carbon::createFromFormat('Y-m-d H', $value);
                                $localRecord[$key] = $date->format('h A');
                                array_push($labels, $date->format('h A'));
                                break;
                            case 'date':
                                $date = Carbon::createFromFormat('Y-m-d', $value);
                                $localRecord[$key] = $date->format('M d');
                                array_push($labels, $date->format('M d'));
                                break;
                            case 'month':
                                $date = Carbon::createFromFormat('Y-m', $value);
                                $localRecord[$key] = $date->format('M, Y');
                                array_push($labels, $date->format('M, Y'));
                                break;
                            case 'year':
                                $date = Carbon::createFromFormat('Y', $value);
                                $localRecord[$key] = $date->format('Y');
                                array_push($labels, $date->format('Y'));
                                break;
                        }
                    } else {
                        // $key = ucfirst(str_replace('_',' ', $key));
                        array_push($data[$key], $value);
                        isset($fieldTotals[$key]) ? $fieldTotals[$key] += $value : $fieldTotals[$key] = $value;
                    }
                }
                array_push($finalRecord, $localRecord);
            }
        }

        $colorIndex = 0;
        foreach ($reportables as $value)
        {
            array_push($datasets, [
                'label' => ucfirst(str_replace('_', ' ', $value)),
                'backgroundColor' => $lineColors[$colorIndex],
                'borderColor' => $lineColors[$colorIndex],
                'data' => $data[$value],
            ]);
            $colorIndex++;
        }

        return [
            'chart' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'records' => array_reverse($finalRecord),
            'reportables' => $reportables,
            'totals' => $fieldTotals,
        ];
    }


    /**
     * Get Effective Date
     *
     * @param integer $difference
     * @param string $intervalType
     * @return string|false
     */
    private static function getEffectiveDate(int $difference, string $intervalType='day'): string|false
    {
        if ($difference > 0) {
            $date = Carbon::now();
            switch($intervalType)
            {
                case 'year':
                    $effectiveDate = $date->subYear($difference);
                    break;
                case 'month':
                    $effectiveDate = $date->subMonth($difference);
                    break;
                default:
                    $effectiveDate = $date->subDays($difference);
            }
            if ($effectiveDate->format('H:i:s') != '00:00:00') {
                if (($difference > 1 && $intervalType == 'day') || in_array($intervalType, ['year', 'month'])) {
                    $effectiveDate = $effectiveDate->addDays(1)->format('Y-m-d 00:00:00');
                } else {
                    $effectiveDate = $effectiveDate->format("Y-m-d H:i:s");
                }
            } else {
                $effectiveDate = $effectiveDate->format("Y-m-d H:i:s");
            }
        }

        return $effectiveDate ?? false;
    }


     /**
     * Get Hourly Report Data
     *
     * @param array $reportables array of fields to fetch
     * @param integer $hours
     * @return Collection
     */
    private static function getHourlyReportData(array $reportables, int $hours): Collection
    {
        $totalRecord = HourlyReport::count();
        return HourlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $hours)
            ->limit($hours)->get(['hour', ...$reportables]);
    }

    /**
     * Get Daily Report Data
     *
     * @param array $reportables array of fields to fetch
     * @param integer $days
     * @return Collection
     */
    private static function getDailyReportData(array $reportables, int $days): Collection
    {
        $totalRecord = DailyReport::count();
        return DailyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $days)
            ->limit($days)->get(['date', ...$reportables]);
    }


    /**
     * Get Monthly Report Data
     *
     * @param array $reportables array of fields to fetch
     * @param integer $months
     * @return Collection
     */
    private static function getMonthlyReportData(array $reportables, int $months): Collection
    {
        $totalRecord = MonthlyReport::count();
        return MonthlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $months)
            ->limit($months)->get(['month', ...$reportables]);
    }


     /**
     * Get Custom Period Report Data
     *
     * @param array $reportables array of fields to fetch
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Collection
     */
    private static function getCustomPeriodReportData(array $reportables, DateTime $startDate, DateTime $endDate): Collection
    {
        return DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
            ->where('date', '<=', $endDate->format('Y-m-d'))
            ->orderBy('id', 'asc')
            ->get(['date', ...$reportables]);
    }
}
