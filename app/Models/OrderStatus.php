<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'role_id',
        'sms_template_id',
        'email_template_id',
        'next_process'
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

    public function nextProcess()
    {
        return $this->belongsTo(OrderStatus::class);
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
