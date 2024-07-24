<?php

namespace App\Config;

class OrderProcess
{
    public static function list()
    {
        return [
            [
                'id' => 1,
                'name' => 'New',
                'next_process' => 2,
            ],
            [
                'id' => 2,
                'name' => 'Billing',
                'next_process' => 3,
            ],
            [
                'id' => 3,
                'name' => 'Prepress',
                'next_process' => 4,
            ],
            [
                'id' => 4,
                'name' => 'Production',
                'next_process' => 5,
            ],
            [
                'id' => 5,
                'name' => 'Finishing',
                'next_process' => 6,
            ],
            [
                'id' => 6,
                'name' => 'Packaging',
                'next_process'=> 7,
            ],
            [
                'id' => 7,
                'name' => 'Dispatch',
                'next_process'=> 8,
            ],
            [
                'id' => 8,
                'name' => 'Delivered'
            ],
            [
                'id' => 9,
                'name' => 'Cancelled'
            ],
        ];
    }
}
