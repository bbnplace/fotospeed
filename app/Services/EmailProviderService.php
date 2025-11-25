<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailProviderService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
    }

    /**
     * Fetch templates from the configured email provider
     */
    public function fetchProviderTemplates()
    {
        if (!$this->settings || !$this->settings->email_method || $this->settings->email_method !== 'API') {
            return [
                'success' => false,
                'message' => 'Email method is not set to API'
            ];
        }

        $provider = $this->settings->email_api_provider;

        switch ($provider) {
            case 'SendGrid':
                return $this->fetchSendGridTemplates();
            case 'Mailgun':
                return $this->fetchMailgunTemplates();
            case 'Postmark':
                return $this->fetchPostmarkTemplates();
            case 'Sendpulse':
                return $this->fetchSendpulseTemplates();
            default:
                return [
                    'success' => false,
                    'message' => "Provider {$provider} template fetching is not yet supported"
                ];
        }
    }

    /**
     * Fetch templates from SendGrid
     */
    private function fetchSendGridTemplates()
    {
        try {
            $apiKey = $this->settings->email_api_key;
            
            if (empty($apiKey)) {
                return ['success' => false, 'message' => 'SendGrid API key not configured'];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json'
            ])->get('https://api.sendgrid.com/v3/templates', [
                'generations' => 'dynamic'
            ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch SendGrid templates: ' . $response->body()
                ];
            }

            $templates = $response->json()['result'] ?? $response->json()['templates'] ?? [];
            $synced = $this->syncTemplates($templates, 'SendGrid', function($template) {
                return [
                    'name' => $template['name'],
                    'template' => $template['name'], // SendGrid template ID for reference
                    'provider_template_id' => $template['id'],
                ];
            });

            return [
                'success' => true,
                'message' => "Synced {$synced} SendGrid templates",
                'count' => $synced
            ];

        } catch (\Exception $e) {
            Log::error('SendGrid template fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error fetching SendGrid templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fetch templates from Mailgun
     */
    private function fetchMailgunTemplates()
    {
        try {
            $apiKey = $this->settings->email_api_key;
            $domain = $this->settings->email_api_endpoint;
            $region = $this->settings->email_api_region ?? 'US';

            if (empty($apiKey) || empty($domain)) {
                return ['success' => false, 'message' => 'Mailgun API key or domain not configured'];
            }

            $baseUrl = $region === 'EU' ? 'https://api.eu.mailgun.net/v3' : 'https://api.mailgun.net/v3';
            
            $response = Http::withBasicAuth('api', $apiKey)
                ->get("{$baseUrl}/{$domain}/templates");

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch Mailgun templates: ' . $response->body()
                ];
            }

            $templates = $response->json()['items'] ?? [];
            $synced = $this->syncTemplates($templates, 'Mailgun', function($template) {
                return [
                    'name' => $template['name'],
                    'template' => $template['description'] ?? $template['name'],
                    'provider_template_id' => $template['name'],
                ];
            });

            return [
                'success' => true,
                'message' => "Synced {$synced} Mailgun templates",
                'count' => $synced
            ];

        } catch (\Exception $e) {
            Log::error('Mailgun template fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error fetching Mailgun templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fetch templates from Postmark
     */
    private function fetchPostmarkTemplates()
    {
        try {
            $serverToken = $this->settings->email_api_key;

            if (empty($serverToken)) {
                return ['success' => false, 'message' => 'Postmark server token not configured'];
            }

            $response = Http::withHeaders([
                'X-Postmark-Server-Token' => $serverToken,
                'Accept' => 'application/json'
            ])->get('https://api.postmarkapp.com/templates');

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch Postmark templates: ' . $response->body()
                ];
            }

            $templates = $response->json()['Templates'] ?? [];
            $synced = $this->syncTemplates($templates, 'Postmark', function($template) {
                return [
                    'name' => $template['Name'],
                    'template' => $template['Subject'] ?? $template['Name'],
                    'provider_template_id' => (string)$template['TemplateId'],
                ];
            });

            return [
                'success' => true,
                'message' => "Synced {$synced} Postmark templates",
                'count' => $synced
            ];

        } catch (\Exception $e) {
            Log::error('Postmark template fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error fetching Postmark templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fetch templates from Sendpulse
     */
    private function fetchSendpulseTemplates()
    {
        // Sendpulse requires OAuth2 authentication which is more complex
        // For now, return not supported
        return [
            'success' => false,
            'message' => 'Sendpulse template fetching requires OAuth2 implementation - coming soon'
        ];
    }

    /**
     * Sync templates to database
     */
    private function syncTemplates(array $templates, string $provider, callable $mapper)
    {
        $synced = 0;

        foreach ($templates as $template) {
            try {
                $data = $mapper($template);
                $data['provider'] = $provider;

                // Check if template already exists
                $existing = EmailTemplate::where('provider', $provider)
                    ->where('provider_template_id', $data['provider_template_id'])
                    ->first();

                if ($existing) {
                    // Update existing
                    $existing->update($data);
                } else {
                    // Create new
                    EmailTemplate::create($data);
                }

                $synced++;
            } catch (\Exception $e) {
                Log::error("Error syncing template: " . $e->getMessage());
                continue;
            }
        }

        return $synced;
    }
}
