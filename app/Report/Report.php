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
    public static function get24HoursReport(Array $reportables): array
    {
        return self::getHourlyReport($reportables, 24);
    }

    public static function get7DaysReport(Array $reportables): array
    {
        return self::getDailyReport($reportables, 7);
    }

    public static function get30DaysReport(Array $reportables): array
    {
        return self::getDailyReport($reportables, 30);
    }

    public static function get90DaysReport(Array $reportables): array
    {
        return self::getDailyReport($reportables, 90);
    }

    public static function getCustomReport(Array $reportables, string $start, string $end): array
    {
        if (empty($start) || empty($end)) {
            // return [
            //     'traffic' => [
            //         'data' => [],
            //         'xkey' => [],
            //         'ykeys' => $reportables,
            //         'labels' => $reportables,
            //         'lineColors' => ['#ff2500', '#28a745', '#ff5000', '#ff9000'],
            //     ]
            // ];
            return [
                'labels'=> [],
                'datasets' => []
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
                    ->get([...$reportables, 'hour']);
                $period = "hour";
                break;
            case $numberOfDays <= 61: // Daily Report
                $record = DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
                    ->where('date', '<=', $stopDate->format('Y-m-d'))
                    ->orderBy('id', 'asc')
                    ->get([...$reportables, 'date']);
                $period = "date";
                break;
            case $numberOfDays < (365 * 3): // Monthly Report
                $record = MonthlyReport::where('month', '>=', $startDate->format('Y-m'))
                    ->where('month', '<=', $stopDate->format('Y-m'))
                    ->orderBy('id', 'asc')
                    ->get([...$reportables, 'month']);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::where('year', '>=', $startDate->format('Y'))
                    ->where('year', '<=', $stopDate->format('Y'))
                    ->orderBy('id', 'asc')
                    ->get([...$reportables, 'year']);
                $period = "year";
        }

            return self::generateLineChartData($reportables, $record, $period);
    }



    public static function getThisYearReport(Array $reportables): array
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
                    ->get([...$reportables, 'hour']);
                $record = count($record) < $dayOfYear ? self::augmentHourlyReport($reportables, $record, $dayOfYear) : $record;
                break;
            case $dayOfYear < 31: // Daily
                $period = 'date';
                $record = DailyReport::where('date', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get([...$reportables, 'date']);
                $record = count($record) < $dayOfYear ? self::augmentDailyReport($reportables, $record, $dayOfYear) : $record;
                break;
            default: // Monthly
                $period = 'month';
                $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get([...$reportables, 'month']);
        }

        return self::generateLineChartData($reportables, $record, $period);
    }

    public static function getLastYearReport(Array $reportables): array
    {
        // For this, need to check the date a person signed up for the service. If date is

        $period = 'month';
        $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y') - 1))
            ->orderBy('id', 'asc')
            ->get([...$reportables, 'month']);
        if (count($record)) {
            $record = self::augmentMonthlyReport($reportables, $record, 12 - count($record));
        } else {
            $minDate = sprintf("%d-01", date("Y"));

            $additionalRecords = [];
            for ($i=12; $i > 0; $i--) {
                array_push($additionalRecords, [
                    ...self::initializeReportables($reportables),
                    'month' => self::getPastMonth($minDate, $i),
                ]);
            }

            $collection = collect($additionalRecords);
            $record = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return self::generateLineChartData($reportables, $record, $period);
    }

    public static function getAllTimeReport(Array $reportables): array
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
                    ->get([...$reportables, 'hour']);
                $period = "hour";
                break;
            case $daysSince <= 31: // Daily Report
                $record = DailyReport::orderBy('id', 'asc')
                    ->get([...$reportables, 'date']);
                $period = "date";
                break;
            case $daysSince < (365 * 3): // Monthly Report
                $record = MonthlyReport::orderBy('id', 'asc')
                    ->get([...$reportables, 'month']);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::orderBy('id', 'asc')
                    ->get([...$reportables, 'year']);
                $period = "year";
        }

        return self::generateLineChartData($reportables, $record, $period);
    }


    private static function augmentMonthlyReport(Array $reportables, $record, $months)
    {
        $totalRecords = count($record);
        if ($totalRecords < $months) {
            $requiredRecords = $months - $totalRecords;
            $minDate = $record[0]->month;

            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    ...self::initializeReportables($reportables),
                    'month' => self::getPastMonth($minDate, $i),
                ]);
            }

            $collection = collect($additionalRecords);
            $objectsArray = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return count($record) ? $objectsArray->merge($record) : $objectsArray;
    }


    private static function augmentHourlyReport(Array $reportables, $record, $hours)
    {
        $totalRecords = count($record);
        if ($totalRecords < $hours) {
            $requiredRecords = $hours - $totalRecords;
            $minDate = isset($record[0]) ? $record[0]->hour : date('Y-m-d H');

            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    ...self::initializeReportables($reportables),
                    'hour' => self::getPastHour($minDate, $i),
                ]);
            }

            $collection = collect($additionalRecords);
            $objectsArray = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return count($record) ? $objectsArray->merge($record) : $objectsArray;
    }


    private static function getHourlyReport(Array $reportables, int $hours)
    {
        $record = self::getHourlyReportData($reportables, $hours);
        return self::generateLineChartData($reportables, (count($record) < $hours ? self::augmentHourlyReport($reportables, $record, $hours)  : $record), 'hour');
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

    private static function initializeReportables(Array $reportables)
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


    private static function augmentDailyReport(Array $reportables, $record, $days)
    {
        $totalRecords = count($record);
        if ($totalRecords < $days) {
            $requiredRecords = $days - $totalRecords;
            $minDate = $record[0]->date;
            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    ...self::initializeReportables($reportables),
                    'date' => self::getPastDay($minDate, $i),
                ]);
            }

            $collection = collect($additionalRecords);
            $objectsArray = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return count($record) ? $objectsArray->merge($record) : $objectsArray;
    }


    private static function getDailyReport(Array $reportables, int $days): array
    {
        $record = self::getDailyReportData($reportables, $days);
        return self::generateLineChartData($reportables, (count($record) < $days ? self::augmentDailyReport($reportables, $record, $days) : $record), 'date');
    }


    private static function getMonthlyReport(Array $reportables, int $months): array
    {
        $record = self::getMonthlyReportData($reportables, $months);
        return self::generateLineChartData($reportables, $record, 'month');
    }

    private static function generateLineChartData(Array $reportables, $records, $xkey): array
    {
        $data = [];
        $labels = [];
        $datasets = [];
        $lineColors = ['#ff2500', '#28a745', '#ff5000', '#ff9000','#ff2500', '#28a745', '#ff5000', '#ff9000'];

        foreach ($reportables as $value)
        {
            $data[$value] = [];
        }

        if (count($records) > 0) {
            foreach ($records->toArray() as &$record) {
                foreach ($record as $key => $value) {
                    // dd($record);
                    if ($key == $xkey) {
                        switch ($xkey) {
                            case 'hour':
                                $date = Carbon::createFromFormat('Y-m-d H', $value);
                                // $record[$key] = $date->format('h A');
                                array_push($labels, $date->format('h A'));
                                break;
                            case 'date':
                                $date = Carbon::createFromFormat('Y-m-d', $value);
                                // $record[$key] = $date->format('M d');
                                array_push($labels, $date->format('M d'));
                                break;
                            case 'month':
                                $date = Carbon::createFromFormat('Y-m', $value);
                                // $record[$key] = $date->format('M. Y');
                                array_push($labels, $date->format('M. Y'));
                                break;
                            case 'year':
                                $date = Carbon::createFromFormat('Y', $value);
                                // $record[$key] = $date->format('Y');
                                array_push($labels, $date->format('Y'));
                                break;
                        }
                    } else {
                        array_push($data[$key], $value);
                    }
                }
            }
        }

        $colorIndex = 0;
        foreach ($reportables as $value)
        {
            array_push($datasets, [
                'label' => $value,
                'backgroundColor' => $lineColors[$colorIndex],
                'borderColor' => $lineColors[$colorIndex],
                'data' => $data[$value],
            ]);
            $colorIndex++;
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];


        // return [
        //     'data' => $records,
        //     'xkey' => $xkey,
        //     'ykeys' => $reportables,
        //     'labels' => $reportables,
        //     'lineColors' => ['#ff2500', '#28a745', '#ff5000', '#ff9000'],
        // ];
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
     * @param Array $reportables Array of fields to fetch
     * @param integer $hours
     * @return Collection
     */
    private static function getHourlyReportData(Array $reportables, int $hours): Collection
    {
        $totalRecord = HourlyReport::count();
        return HourlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $hours)
            ->limit($hours)->get([...$reportables, 'hour']);
    }

    /**
     * Get Daily Report Data
     *
     * @param Array $reportables Array of fields to fetch
     * @param integer $days
     * @return Collection
     */
    private static function getDailyReportData(Array $reportables, int $days): Collection
    {
        $totalRecord = DailyReport::count();
        return DailyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $days)
            ->limit($days)->get([...$reportables, 'date']);
    }


    /**
     * Get Monthly Report Data
     *
     * @param Array $reportables Array of fields to fetch
     * @param integer $months
     * @return Collection
     */
    private static function getMonthlyReportData(Array $reportables, int $months): Collection
    {
        $totalRecord = MonthlyReport::count();
        return MonthlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $months)
            ->limit($months)->get([...$reportables, 'month']);
    }


     /**
     * Get Custom Period Report Data
     *
     * @param Array $reportables Array of fields to fetch
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Collection
     */
    private static function getCustomPeriodReportData(Array $reportables, DateTime $startDate, DateTime $endDate): Collection
    {
        return DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
            ->where('date', '<=', $endDate->format('Y-m-d'))
            ->orderBy('id', 'asc')
            ->get([...$reportables, 'date']);
    }
}
