<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_BILLING = 2;
    const STATUS_PREPRESS = 3;
    const STATUS_PRODUCTION = 4;
    const STATUS_FINISHING = 5;
    const STATUS_PACKAGING = 6;
    const STATUS_DISPATCH = 7;
    const STATUS_DELIVERED = 8;
    const STATUS_CANCELLED = 9;
    const STATUS_RETURNED = 10;

    protected $fillable = [
        'name',
        'description',
        'role_id',
        'sms_template_id',
        'email_template_id',
        'next_process',
        'sms_team',
        'email_team',
        'sms_customer',
        'email_customer',
        'customer_sms_template_id',
        'customer_email_template_id',
        'report_as',
        'report_process'
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
}
