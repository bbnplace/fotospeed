<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EmailTemplate;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\WhatsappTemplate;
use App\Report\ReportBuilder;
use Cecula\MessagingApi\Messaging;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::first();
        $smsTemplates = SmsTemplate::getSmsTemplatesArray();
        $whatsappTemplates = WhatsappTemplate::getWhatsappTemplatesArray();
        $emailTemplates = EmailTemplate::getEmailTemplatesArray();
        $reportStates = ReportBuilder::getReportStates();
        $banks = \App\Models\Bank::pluck('name')->toArray();
        array_unshift($smsTemplates, 'None');
        array_unshift($whatsappTemplates, 'None');
        array_unshift($emailTemplates, 'None');
        // array_unshift($reportStates, 'Received');

        return Inertia::render('Backend/Settings/Edit', [
            'settings' => $settings,
            'smsTemplates' => $smsTemplates,
            'whatsappTemplates' => $whatsappTemplates,
            'emailTemplates' => $emailTemplates,
            'reportStates' => $reportStates,
            'roles' => Role::getRolesArray(),
            'banks' => $banks,
        ]);
    }


    public function update(Request $request)
    {
        $rules = [
            'max_file_size' => 'required|integer|min:1024|max:1024000',
            'thumbnail_size' => 'required|integer|min:10|max:1080',
            'file_mime_types' => 'required|string|min:2|max:200',
            'cecula_sync_api_key' => 'nullable|string|min:32|max:64',
            'cecula_a2p_api_key' => 'nullable|string|min:32|max:64',
            'sms_type' => 'nullable|in:SIM,A2P',
            'a2p_identity' => 'nullable|string|max:11',
            'email_method' => 'nullable|in:API,SMTP',
            'email_api_provider' => 'nullable|string|max:64',
            'email_api_key' => 'nullable|string|max:255',
            'email_api_secret' => 'nullable|string|max:255',
            'email_api_endpoint' => 'nullable|string|max:255',
            'email_api_region' => 'nullable|string|max:64',
            'email_sender_name' => 'nullable|string|min:2|max:64',
            'from_email' => 'nullable|email:dns,rfc|max:128',
            'replyto_email' => 'nullable|email:dns,rfc|max:128',
            'email_host' => 'nullable|url',
            'email_port' => 'nullable|integer|digits_between:1,6',
            'email_password' => 'nullable|string|min:6|max:124',
            'min_order_processing_days' => 'nullable|integer|digits_between:1,3',
            'max_order_processing_days' => 'nullable|integer|digits_between:1,3',
            'paystack_secret_key' => 'nullable|string|min:32|max:64',
            'paystack_public_key' => 'nullable|string|min:32|max:64',
            'org_name' => 'nullable|string|max:128',
            'org_address' => 'nullable|string|max:128',
            'org_email' => 'nullable|email:dns,rfc|max:128',
            'org_phone' => 'nullable|string',
            'org_url' => 'nullable|url',
            'payment_sms_temp' => 'nullable|string|max:64',
            'payment_email_temp' => 'nullable|string|max:64',
            'wa_business_account_id' => 'nullable|string|max:64',
            'wa_app_id' => 'nullable|string|max:32',
            'wa_phone_id' => 'nullable|string|max:64',
            'wa_webhook_verification_token' => 'nullable|string|max:64',
            'wa_access_token' => 'nullable|string',
            'reportables' => 'required|array',
            'reportables.*' => sprintf('in:Received,%s', implode(',', ReportBuilder::getReportStates())),
            'reportViewers' => 'required|array',
            'reportViewers.*' => sprintf('in:%s', implode(',', Role::getRolesArray())),
            'invoice_no_src' => 'required|string|max:64',
            'loyalty_reward_multiplier' => 'nullable|numeric|min:0|max:1',
            'points_to_currency_ratio' => 'nullable|numeric|min:0',
            'points_expiry_months' => 'nullable|integer|min:0',
            'min_points_redeemable' => 'nullable|integer|min:0',
            'max_invoice_percentage_payable_by_points' => 'nullable|integer|min:0|max:100',
            'support_offline_payment' => 'nullable|boolean',
            'who_approves_offline_payment' => 'nullable|string|max:64',
            'who_handles_refunds' => 'nullable|string|max:64',
            'order_file_delible_states' => 'nullable|array',
            'auto_delete_order_files_after' => 'nullable|string|max:32',
            'customer_creation_whatsapp_template' => 'nullable|string|max:64',
            'order_cancellation_whatsapp_template' => 'nullable|string|max:64',
            'bank_name' => 'nullable|string|max:128',
            'account_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:128',
            'order_view_roles' => 'required|array',
            'order_view_roles.*' => sprintf('in:%s', implode(',', Role::getRolesArray())),
            'order_cancel_roles' => 'required|array',
            'order_cancel_roles.*' => sprintf('in:%s', implode(',', Role::getRolesArray())),
            'processing_branch_show_price' => 'nullable|boolean',
            'processing_branch_show_invoice' => 'nullable|boolean',
        ];

        $request->validate($rules);

        Log::info(strlen($request->wa_access_token));

        $settings = Setting::first();

        $settings->max_file_size = $request->max_file_size;
        $settings->thumbnail_size = $request->thumbnail_size;
        $settings->file_mime_types = $request->file_mime_types;
        $settings->email_method = $request->email_method;
        $settings->email_api_provider = $request->email_api_provider;
        $settings->email_api_key = $request->email_api_key;
        $settings->email_api_secret = $request->email_api_secret;
        $settings->email_api_endpoint = $request->email_api_endpoint;
        $settings->email_api_region = $request->email_api_region;
        $settings->email_sender_name = $request->email_sender_name;
        $settings->from_email = $request->from_email;
        $settings->replyto_email = $request->replyto_email;
        $settings->email_host = $request->email_host;
        $settings->email_port = $request->email_port;
        $settings->email_password = $request->email_password;
        $settings->min_order_processing_days = $request->min_order_processing_days;
        $settings->max_order_processing_days = $request->max_order_processing_days;
        $settings->cecula_sync_api_key = $request->cecula_sync_api_key;
        $settings->cecula_a2p_api_key = $request->cecula_a2p_api_key;
        $settings->sms_type = $request->sms_type;
        $settings->a2p_identity = $request->a2p_identity;
        $settings->paystack_secret_key = $request->paystack_secret_key;
        $settings->paystack_public_key = $request->paystack_public_key;
        $settings->org_name = $request->org_name;
        $settings->org_address = $request->org_address;
        $settings->org_email = $request->org_email;
        $settings->org_phone = $request->org_phone;
        $settings->org_url = $request->org_url;
        $settings->payment_sms_temp = $request->payment_sms_temp == 'None' ? null : $request->payment_sms_temp;
        $settings->payment_email_temp = $request->payment_email_temp  == 'None' ? null : $request->payment_email_temp;
        $settings->wa_business_account_id = $request->wa_business_account_id;
        $settings->wa_app_id = $request->wa_app_id;
        $settings->wa_phone_id = $request->wa_phone_id;
        $settings->wa_access_token = $request->wa_access_token;
        $settings->wa_webhook_verification_token = $request->wa_webhook_verification_token;
        $settings->reportables = json_encode($this->snakeCaseReportables($request->reportables));
        $settings->reports_permission = json_encode($request->reportViewers);
        $settings->invoice_no_src = $request->invoice_no_src;
        $settings->loyalty_reward_multiplier = $request->loyalty_reward_multiplier;
        $settings->points_to_currency_ratio = $request->points_to_currency_ratio ?? 1.00;
        $settings->points_expiry_months = $request->points_expiry_months ?? 12;
        $settings->min_points_redeemable = $request->min_points_redeemable ?? 100;
        $settings->max_invoice_percentage_payable_by_points = $request->max_invoice_percentage_payable_by_points ?? 100;
        $settings->support_offline_payment = $request->support_offline_payment;
        $settings->who_approves_offline_payment = $request->who_approves_offline_payment;
        $settings->who_handles_refunds = $request->who_handles_refunds;
        $settings->order_file_delible_states = json_encode($request->order_file_delible_states);
        $settings->auto_delete_order_files_after = $request->auto_delete_order_files_after;
        $settings->customer_creation_whatsapp_template = $request->customer_creation_whatsapp_template == 'None' ? null : $request->customer_creation_whatsapp_template;
        $settings->order_cancellation_whatsapp_template = $request->order_cancellation_whatsapp_template == 'None' ? null : $request->order_cancellation_whatsapp_template;
        $settings->bank_name = $request->bank_name;
        $settings->account_number = $request->account_number;
        $settings->account_name = $request->account_name;
        $settings->order_view_roles = json_encode($request->order_view_roles);
        $settings->order_cancel_roles = json_encode($request->order_cancel_roles);
        $settings->processing_branch_show_price = $request->processing_branch_show_price ?? false;
        $settings->processing_branch_show_invoice = $request->processing_branch_show_invoice ?? false;
        $settings->save();

        return redirect(route('settings'));
    }

    public function snakeCaseReportables(array $reportables): array
    {
        $snakeCasedReportables = [];
        foreach ($reportables as $value) {
            array_push($snakeCasedReportables, strtolower(str_replace(' ', '_', $value)));
        }
        return $snakeCasedReportables;
    }

    public function authBroadcast(Request $request)
    {
        dd($request);
    }

    public function fetchIdentities()
    {
        $settings = Setting::first();

        $ceculaA2pApiKey = trim($settings->cecula_a2p_api_key);

        if (strlen($ceculaA2pApiKey) < 32) {
            return [];
        }

        $ceculaA2pApiClient = new Messaging([
            'apiKey' => $ceculaA2pApiKey
        ]);

        return $ceculaA2pApiClient->getSenderNames();
    }
}
