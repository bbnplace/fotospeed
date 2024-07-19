<?php

namespace App\Report;

use App\Events\SmsSent;
use App\Messaging\Conf;
use App\Models\DailyReport;
use App\Models\HourlyReport;
use App\Models\MonthlyReport;
use App\Models\SentSms;
use App\Models\User;
use App\Models\YearlyReport;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class Report
{
    public static function get24HoursReport(User $user): array
    {
        return self::getHourlyReport($user, 24);
    }

    public static function get7DaysReport(User $user): array
    {
        return self::getDailyReport($user, 7);
    }

    public static function get30DaysReport(User $user): array
    {
        return self::getDailyReport($user, 30);
    }

    public static function get90DaysReport(User $user): array
    {
        return self::getDailyReport($user, 90);
    }

    public static function getCustomReport(User $user, string $start, string $end): array
    {
        if (empty($start) || empty($end)) {
            return [
                'traffic' => [
                    'data' => [],
                    'xkey' => [],
                    'ykeys' => ['sent', 'inbox', 'queries', 'subscriptions'],
                    'labels' => ['Sent SMS', 'Received SMS', 'SMS Queries', 'Subscriptions'],
                    'lineColors' => ['#ff2500', '#28a745', '#ff5000', '#ff9000'],
                ]
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
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'hour']);
                $period = "hour";
                break;
            case $numberOfDays <= 61: // Daily Report
                $record = DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
                    ->where('date', '<=', $stopDate->format('Y-m-d'))
                    ->orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'date']);
                $period = "date";
                break;
            case $numberOfDays < (365 * 3): // Monthly Report
                $record = MonthlyReport::where('month', '>=', $startDate->format('Y-m'))
                    ->where('month', '<=', $stopDate->format('Y-m'))
                    ->orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'month']);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::where('year', '>=', $startDate->format('Y'))
                    ->where('year', '<=', $stopDate->format('Y'))
                    ->orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'year']);
                $period = "year";
        }

            return [
                'traffic' => self::generateLineChartData($record, $period),
            ];
    }



    public static function getThisYearReport(User $user): array
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
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'hour']);
                $record = count($record) < $dayOfYear ? self::augmentHourlyReport($record, $dayOfYear) : $record;
                break;
            case $dayOfYear < 31: // Daily
                $period = 'date';
                $record = DailyReport::where('date', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'date']);
                $record = count($record) < $dayOfYear ? self::augmentDailyReport($record, $dayOfYear) : $record;
                break;
            default: // Monthly
                $period = 'month';
                $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y')))
                    ->orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'month']);
        }

        return [
            'traffic' => self::generateLineChartData($record, $period),
        ];
    }

    public static function getLastYearReport(User $user): array
    {
        // For this, need to check the date a person signed up for the service. If date is

        $period = 'month';
        $record = MonthlyReport::where('month', 'LIKE', sprintf('%s%%', date('Y') - 1))
            ->orderBy('id', 'asc')
            ->get(['sent', 'inbox', 'queries', 'subscriptions', 'month']);
        if (count($record)) {
            $record = self::augmentMonthlyReport($record, 12 - count($record));
        } else {
            $minDate = sprintf("%d-01", date("Y"));

            $additionalRecords = [];
            for ($i=12; $i > 0; $i--) {
                array_push($additionalRecords, [
                    'sent' => 0,
                    'inbox' => 0,
                    'queries' => 0,
                    'subscriptions' => 0,
                    'month' => self::getPastMonth($minDate, $i),
                ]);
            }

            $collection = collect($additionalRecords);
            $record = $collection->map(function ($item) {
                return (object)$item;
            });
        }

        return [
            'traffic' => self::generateLineChartData($record, $period),
        ];
    }

    public static function getAllTimeReport(User $user): array
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
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'hour']);
                $period = "hour";
                break;
            case $daysSince <= 31: // Daily Report
                $record = DailyReport::orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'date']);
                $period = "date";
                break;
            case $daysSince < (365 * 3): // Monthly Report
                $record = MonthlyReport::orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'month']);
                $period = "month";
                break;
            default: // Yearly report
                $record = YearlyReport::orderBy('id', 'asc')
                    ->get(['sent', 'inbox', 'queries', 'subscriptions', 'year']);
                $period = "year";
        }

        return [
            'traffic' => self::generateLineChartData($record, $period),
        ];
    }


    private static function augmentMonthlyReport($record, $months)
    {
        $totalRecords = count($record);
        if ($totalRecords < $months) {
            $requiredRecords = $months - $totalRecords;
            $minDate = $record[0]->month;

            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    'sent' => 0,
                    'inbox' => 0,
                    'queries' => 0,
                    'subscriptions' => 0,
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


    private static function augmentHourlyReport($record, $hours)
    {
        $totalRecords = count($record);
        if ($totalRecords < $hours) {
            $requiredRecords = $hours - $totalRecords;
            $minDate = isset($record[0]) ? $record[0]->hour : date('Y-m-d H');

            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    'sent' => 0,
                    'inbox' => 0,
                    'queries' => 0,
                    'subscriptions' => 0,
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


    private static function getHourlyReport(User $user, int $hours)
    {
        $record = self::getHourlyReportData($user, $hours);
        return [
            'traffic' => self::generateLineChartData(count($record) < $hours ? self::augmentHourlyReport($record, $hours)  : $record, 'hour')
        ];
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


    private static function augmentDailyReport($record, $days)
    {
        $totalRecords = count($record);
        if ($totalRecords < $days) {
            $requiredRecords = $days - $totalRecords;
            $minDate = $record[0]->date;
            $additionalRecords = [];
            for ($i=$requiredRecords; $i > 0; $i--) {
                array_push($additionalRecords, [
                    'sent' => 0,
                    'inbox' => 0,
                    'queries' => 0,
                    'subscriptions' => 0,
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


    private static function getDailyReport(User $user, int $days): array
    {
        $record = self::getDailyReportData($user, $days);
        return [
            'traffic' => self::generateLineChartData(count($record) < $days ? self::augmentDailyReport($record, $days) : $record, 'date'),
        ];
    }


    private static function getMonthlyReport(User $user, int $months): array
    {
        $record = self::getMonthlyReportData($user, $months);
        return [
            'traffic' => self::generateLineChartData($record, 'month'),
        ];
    }

    private static function generateLineChartData($records, $xkey): array
    {

        if (count($records) > 0) {
            foreach ($records as &$record) {
                foreach ($record as $key => $value) {
                    if ($key == $xkey) {
                        switch ($xkey) {
                            case 'hour':
                                $date = Carbon::createFromFormat('Y-m-d H', $value);
                                $record->{$key} = $date->format('h A');
                                break;
                            case 'date':
                                $date = Carbon::createFromFormat('Y-m-d', $value);
                                $record->{$key} = $date->format('M d');
                                break;
                            case 'month':
                                $date = Carbon::createFromFormat('Y-m', $value);
                                $record->{$key} = $date->format('M. Y');
                                break;
                            case 'year':
                                $date = Carbon::createFromFormat('Y', $value);
                                $record->{$key} = $date->format('Y');
                                break;
                        }
                    }
                }
            }
        }

        return [
            'data' => $records,
            'xkey' => $xkey,
            'ykeys' => ['sent', 'inbox', 'queries', 'subscriptions'],
            'labels' => ['Sent SMS', 'Received SMS', 'SMS Queries', 'Subscriptions'],
            'lineColors' => ['#ff2500', '#28a745', '#ff5000', '#ff9000'],
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
     * @param User $user
     * @param integer $hours
     * @return Collection
     */
    private static function getHourlyReportData(User $user, int $hours): Collection
    {
        $totalRecord = HourlyReport::count();
        return HourlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $hours)
            ->limit($hours)->get(['sent', 'inbox', 'queries', 'subscriptions', 'hour']);
    }

    /**
     * Get Daily Report Data
     *
     * @param User $user
     * @param integer $days
     * @return Collection
     */
    private static function getDailyReportData(User $user, int $days): Collection
    {
        $totalRecord = DailyReport::count();
        return DailyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $days)
            ->limit($days)->get(['sent', 'inbox', 'queries', 'subscriptions', 'date']);
    }


    /**
     * Get Monthly Report Data
     *
     * @param User $user
     * @param integer $months
     * @return Collection
     */
    private static function getMonthlyReportData(User $user, int $months): Collection
    {
        $totalRecord = MonthlyReport::count();
        return MonthlyReport::orderBy('id', 'asc')
            ->offset($totalRecord - $months)
            ->limit($months)->get(['sent', 'inbox', 'queries', 'subscriptions', 'month']);
    }


     /**
     * Get Custom Period Report Data
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Collection
     */
    private static function getCustomPeriodReportData(User $user, DateTime $startDate, DateTime $endDate): Collection
    {
        return DailyReport::where('date', '>=', $startDate->format('Y-m-d'))
            ->where('date', '<=', $endDate->format('Y-m-d'))
            ->orderBy('id', 'asc')
            ->get(['sent', 'inbox', 'queries', 'subscriptions', 'date']);
    }
}
