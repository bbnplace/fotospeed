<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template',
        'usage',
        'timing',
        'target',
    ];

    public static function getWhatsappTemplatesArray()
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
