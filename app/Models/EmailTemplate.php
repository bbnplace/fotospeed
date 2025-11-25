<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template',
        'usage',
        'timing',
        'target',
        'provider',
        'provider_template_id',
    ];

    public static function getEmailTemplatesArray()
    {
        $emailTemplates = [];
        $emailTemplatesCollection = self::get('name');
        if(!empty($emailTemplatesCollection))
        {
            foreach($emailTemplatesCollection as $emailTemplate){
                array_push($emailTemplates, $emailTemplate->name);
            }
        }

        return $emailTemplates;
    }
}
