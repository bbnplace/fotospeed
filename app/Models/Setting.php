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
        'cecula_a2p_api_key',
        'sms_type',
        'a2p_identity',
        'email_method',
        'email_api_provider',
        'email_api_key',
        'email_api_secret',
        'email_api_endpoint',
        'email_api_region',
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
        'wa_business_account_id',
        'wa_app_id',
        'wa_phone_id',
        'wa_access_token',
        'wa_webhook_verification_token',
        'invoice_no_src',
        'reportables',
        'reports_permission',
        'loyalty_reward_multiplier',
        'points_to_currency_ratio',
        'points_expiry_months',
        'min_points_redeemable',
        'max_invoice_percentage_payable_by_points',
        'support_offline_payment',
        'who_approves_offline_payment',
        'who_handles_refunds',
        'order_file_delible_states',
        'auto_delete_order_files_after',
        'customer_creation_whatsapp_template',
        'bank_name',
        'account_name',
        'order_view_roles',
        'order_cancel_roles',
        'order_cancellation_whatsapp_template',
        'order_waybill_roles',
        'processing_branch_show_price',
        'processing_branch_show_invoice',
    ];
}
