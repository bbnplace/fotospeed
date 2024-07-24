<?php

namespace App\Report;
use App\Models\OrderStatus;

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
        $field = strtolower($field);

        Hour::build($field, $ordersCount);
        Day::build($field, $ordersCount);
        Month::build($field, $ordersCount);
        Year::build($field, $ordersCount);
    }


    /**
     * Get Report States
     * This method returns an array of possible states except 'Received' which is 
     * automatically applied to new orders.
     * 
     * @return array
     */
    public static function getReportStates()
    {
        return OrderStatus::getOrderStatusesArray();
        // return [
        //     'Processing',
        //     'Produced',
        //     'Delivered',
        //     'Cancelled',
        //     'Dispatched',
        //     'Completed',
        //     'Packaged',
        // ];
    }
}