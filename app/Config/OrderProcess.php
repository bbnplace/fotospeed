<?php

namespace App\Config;
use App\Models\OrderStatus;

class OrderProcess
{
    public static function list()
    {
        return [
            [
                'id' => OrderStatus::STATUS_NEW,
                'name' => 'New',
                'next_process' => OrderStatus::STATUS_BILLING,
            ],
            [
                'id' => OrderStatus::STATUS_BILLING,
                'name' => 'Billing',
                'next_process' => OrderStatus::STATUS_PREPRESS,
            ],
            [
                'id' => OrderStatus::STATUS_PREPRESS,
                'name' => 'Prepress',
                'next_process' => OrderStatus::STATUS_PRODUCTION,
            ],
            [
                'id' => OrderStatus::STATUS_PRODUCTION,
                'name' => 'Production',
                'next_process' => OrderStatus::STATUS_FINISHING,
            ],
            [
                'id' => OrderStatus::STATUS_FINISHING,
                'name' => 'Finishing',
                'next_process' => OrderStatus::STATUS_PACKAGING,
            ],
            [
                'id' => OrderStatus::STATUS_PACKAGING,
                'name' => 'Packaging',
                'next_process'=> OrderStatus::STATUS_DISPATCH,
            ],
            [
                'id' => OrderStatus::STATUS_DISPATCH,
                'name' => 'Dispatch',
                'next_process'=> OrderStatus::STATUS_DELIVERED,
            ],
            [
                'id' => OrderStatus::STATUS_DELIVERED,
                'name' => 'Delivered'
            ],
            [
                'id' => OrderStatus::STATUS_CANCELLED,
                'name' => 'Cancelled'
            ],
            [
                'id' => OrderStatus::STATUS_RETURNED,
                'name' => 'Returned'
            ],
        ];
    }
}
