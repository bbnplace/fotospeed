<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const PENDING = 1;
    const ORDER_CONFIRMED = 2;
    const AWAITING_PAYMENT = 3;
    const PAYMENT_CONFIRMED = 4;
    const PRODUCTION_STARTED = 5;
    const IN_PRODUCTION = 6;
    const PRODUCTION_COMPLETED = 7;
    const FULFILLED = 8;
    const ON_HOLD = 9;
    const DISPATCHED = 10;
    const SHIPPING = 11;
    const IN_TRANSIT = 12;
    const DELIVERED = 13;
    const DELIVERY_FAILED = 14;
    const CANCELLED = 15;
    const RETURNED = 16;

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function smsTemplate()
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function customerSmsTemplate()
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    public function customerEmailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function nextProcess()
    {
        return $this->belongsTo(OrderStatus::class, 'next_process');
    }

    public static function getOrderStatusesArray()
    {
        $orderStatuses = [];
        $orderStatusesCollection = self::get('name');
        if(!empty($orderStatusesCollection))
        {
            foreach($orderStatusesCollection as $orderStatus){
                array_push($orderStatuses, $orderStatus->name);
            }
        }

        return $orderStatuses;
    }

    public static function list()
    {
        return [
            [
                'id' => self::PENDING,
                'name' => 'Pending',
            ],
            [
                'id' => self::ORDER_CONFIRMED,
                'name' => 'Order Confirmed',
            ],
            [
                'id' => self::AWAITING_PAYMENT,
                'name' => 'Awaiting Payment',
            ],
            [
                'id' => self::PAYMENT_CONFIRMED,
                'name' => 'Payment Confirmed',
            ],
            [
                'id' => self::PRODUCTION_STARTED,
                'name' => 'Production Started',
            ],
            [
                'id' => self::PRODUCTION_COMPLETED,
                'name' => 'Production Completed',
            ],
            [
                'id' => self::IN_PRODUCTION,
                'name' => 'In Production',
            ],
            [
                'id' => self::FULFILLED,
                'name' => 'Fulfilled'
            ],
            [
                'id' => self::ON_HOLD,
                'name' => 'On Hold'
            ],
            [
                'id' => self::DISPATCHED,
                'name' => 'Dispatched'
            ],
            [
                'id' => self::SHIPPING,
                'name' => 'Shipping'
            ],
            [
                'id' => self::IN_TRANSIT,
                'name' => 'In Transit'
            ],
            [
                'id' => self::DELIVERED,
                'name' => 'Delivered'
            ],
            [
                'id' => self::DELIVERY_FAILED,
                'name' => 'Delivery Failed'
            ],
            [
                'id' => self::CANCELLED,
                'name' => 'Cancelled'
            ],
            [
                'id' => self::RETURNED,
                'name' => 'Returned'
            ],
        ];
    }
}
