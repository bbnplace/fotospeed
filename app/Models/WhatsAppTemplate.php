<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template'
    ];

    public static function getWhatsAppTemplatesArray()
    {
        $whatsAppTemplates = [];
        $whatsAppTemplatesCollection = self::get('name');
        if(!empty($whatsAppTemplatesCollection))
        {
            foreach($whatsAppTemplatesCollection as $whatsAppTemplate){
                array_push($whatsAppTemplates, $whatsAppTemplate->name);
            }
        }

        return $whatsAppTemplates;
    }
}
