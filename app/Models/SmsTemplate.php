<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template'
    ];

    public static function getSmsTemplatesArray()
    {
        $smsTemplates = [];
        $smsTemplatesCollection = self::get('name');
        if(!empty($smsTemplatesCollection))
        {
            foreach($smsTemplatesCollection as $smsTemplate){
                array_push($smsTemplates, $smsTemplate->name);
            }
        }

        return $smsTemplates;
    }
}
