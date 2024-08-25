<?php

namespace App\Report;
use App\Config\OrderProcess;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Log;

class ReportBuilder
{
    /**
     * Init Report
     * This method initializes all the tables required for reporting
     * 
     * @return void
     */
    public static function init()
    {
        Hour::initialize();
        Day::initialize();
        Month::initialize();
        Year::initialize();
    }


    /**
     * Order Status is Reportable
     * This method checks if report can be generated for the Order process submitted 
     * @param string $statusName
     * @return bool
     */
    public static function isReportableOrderProcess(string $statusName): bool
    {
        $isReportableOrderStatus = false;
        $reportableStatuses = OrderStatus::list();
        foreach ($reportableStatuses as $status) {
            if (strtolower($status["name"]) == strtolower($statusName)) {
                $isReportableOrderStatus = true;
                break;
            }
        }

        return $isReportableOrderStatus;
    }
    
    /**
     * Build
     * This method increments the value of the relevant fields in the various reports tables
     * 
     * @param string $field     The name of the field on the reports table. Eg. received, produced, delivered, etc
     * @param int $ordersCount  The number of orders to add to the field
     * 
     * @return void
     */
    public static function build(string $field, int $ordersCount = 1): void
    {
        // If the field name is not part of the standard processes, don't build report for it
        if (self::isReportableOrderProcess($field)) {
            $field = strtolower(str_replace(" ", "_", $field));
            // Log::info($field);
            Hour::build($field, $ordersCount);
            Day::build($field, $ordersCount);
            Month::build($field, $ordersCount);
            Year::build($field, $ordersCount);
        }
    }


    /**
     * Get Report States
     * This method returns an array of possible states except 'Received' which is 
     * automatically applied to new orders.
     * 
     * @return array
     */
    public static function getReportStates(): array
    {
        $reportStates = OrderStatus::list();
        $states = [];
        foreach ($reportStates as $state) {
            array_push($states, $state["name"]);
        }
        return $states;
    }
}