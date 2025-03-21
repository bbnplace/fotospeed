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
        'template_detail',
        'whatsapp_reference',
        'status',
        'language',
        'category',
        'sub_category',
        'parameter_format',
        'usage',
        'timing',
        'target',
    ];

    public static function getWhatsappTemplatesArray()
    {
        $whatsAppTemplates = [];
        $whatsAppTemplatesCollection = self::where('status', 'APPROVED')->get('name');
        if(!empty($whatsAppTemplatesCollection))
        {
            foreach($whatsAppTemplatesCollection as $whatsAppTemplate){
                array_push($whatsAppTemplates, $whatsAppTemplate->name);
            }
        }

        return $whatsAppTemplates;
    }
}
