<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_file_size',
        'thumbnail_size',
        'file_mime_types',
        'cecula_sync_api_key',
        'email_sender_name',
        'from_email',
        'replyto_email',
        'email_host',
        'email_port',
        'email_password',
        'min_order_processing_days',
        'max_order_processing_days',
        'paystack_secret_key',
        'paystack_public_key',
        'org_name',
        'org_address',
        'org_email',
        'org_phone',
        'org_url',
        'payment_sms_temp',
        'payment_email_temp',
        'wa_phone_id',
        'wa_access_token',
    ];
}
