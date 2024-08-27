<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EmailTemplate;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Report\ReportBuilder;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::first();
        $smsTemplates = SmsTemplate::getSmsTemplatesArray();
        $emailTemplates = EmailTemplate::getEmailTemplatesArray();
        $reportStates = ReportBuilder::getReportStates();
        array_unshift($smsTemplates, 'None');
        array_unshift($emailTemplates, 'None');
        // array_unshift($reportStates, 'Received');

        return Inertia::render('Backend/Settings/Edit', [
            'settings' => $settings,
            'smsTemplates' => $smsTemplates,
            'emailTemplates' => $emailTemplates,
            'reportStates' => $reportStates,
            'roles' => Role::getRolesArray(),
        ]);
    }


    public function update(Request $request)
    {
        $rules = [
            'max_file_size' => 'required|integer|min:1024|max:1024000',
            'thumbnail_size' => 'required|integer|min:10|max:1080',
            'file_mime_types' => 'required|string|min:2|max:200',
            'cecula_sync_api_key' => 'nullable|string|min:32|max:64',
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
            'wa_phone_id' => 'nullable|string|max:64',
            'wa_access_token' => 'nullable|string|max:64',
            'reportables' => 'required|array',
            'reportables.*' => sprintf('in:Received,%s', implode(',', ReportBuilder::getReportStates())),
            'reportViewers' => 'required|array',
            'reportViewers.*' => sprintf('in:%s', implode(',', Role::getRolesArray())),
            'invoice_no_src' => 'required|string|max:64',
            'loyalty_reward_formula' => 'nullable|string|max:64',
            'support_offline_payment' => 'nullable|boolean',
            'who_approves_offline_payment' => 'nullable|string|max:64',
        ];

        $request->validate($rules);

        $settings = Setting::first();

        $settings->max_file_size = $request->max_file_size;
        $settings->thumbnail_size = $request->thumbnail_size;
        $settings->file_mime_types = $request->file_mime_types;
        $settings->email_sender_name = $request->email_sender_name;
        $settings->from_email = $request->from_email;
        $settings->replyto_email = $request->replyto_email;
        $settings->email_host = $request->email_host;
        $settings->email_port = $request->email_port;
        $settings->email_password = $request->email_password;
        $settings->min_order_processing_days = $request->min_order_processing_days;
        $settings->max_order_processing_days = $request->max_order_processing_days;
        $settings->cecula_sync_api_key = $request->cecula_sync_api_key;
        $settings->paystack_secret_key = $request->paystack_secret_key;
        $settings->paystack_public_key = $request->paystack_public_key;
        $settings->org_name = $request->org_name;
        $settings->org_address = $request->org_address;
        $settings->org_email = $request->org_email;
        $settings->org_phone = $request->org_phone;
        $settings->org_url = $request->org_url;
        $settings->payment_sms_temp = $request->payment_sms_temp == 'None' ? null : $request->payment_sms_temp;
        $settings->payment_email_temp = $request->payment_email_temp  == 'None' ? null : $request->payment_email_temp;
        $settings->wa_phone_id = $request->wa_phone_id;
        $settings->wa_access_token = $request->wa_access_token;
        $settings->reportables = json_encode($this->snakeCaseReportables($request->reportables));
        $settings->reports_permission = json_encode($request->reportViewers);
        $settings->invoice_no_src = $request->invoice_no_src;
        $settings->loyalty_reward_formula = $request->loyalty_reward_formula;
        $settings->support_offline_payment = $request->support_offline_payment;
        $settings->who_approves_offline_payment = $request->who_approves_offline_payment;
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
}
