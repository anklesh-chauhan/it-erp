<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ManageCloudflareCname implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenant;
    protected $action;
    protected $oldDomain;

    public function __construct(Tenant $tenant, string $action, ?string $oldDomain = null)
    {
        $this->tenant = $tenant;
        $this->action = $action;
        $this->oldDomain = $oldDomain;
    }

    public function handle()
    {
        $zoneId = env('CLOUDFLARE_ZONE_ID');
        $apiToken = env('CLOUDFLARE_API_TOKEN');
        $target = env('CLOUDFLARE_TARGET', 'iterp.in');
        $baseDomain = env('APP_DOMAIN', 'iterp.in'); // Add APP_DOMAIN to .env for your base domain

        try {
            $headers = [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ];

            if ($this->action === 'create') {
                // Create CNAME record
                $response = Http::withHeaders($headers)->post("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records", [
                    'type' => 'CNAME',
                    'name' => "{$this->tenant->domain}.{$baseDomain}",
                    'content' => $target,
                    'ttl' => 3600,
                    'proxied' => true, // Set to false for DNS-only
                ]);

                if ($response->successful()) {
                    $recordId = $response->json()['result']['id'];
                    $this->tenant->update(['cloudflare_record_id' => $recordId]);
                    Log::info("Created CNAME record for {$this->tenant->domain}.{$baseDomain}, record ID: {$recordId}");
                } else {
                    Log::error("Failed to create CNAME: " . $response->body());
                }
            } elseif ($this->action === 'update' && $this->tenant->cloudflare_record_id) {
                // Update CNAME record
                $response = Http::withHeaders($headers)->patch("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records/{$this->tenant->cloudflare_record_id}", [
                    'type' => 'CNAME',
                    'name' => "{$this->tenant->domain}.{$baseDomain}",
                    'content' => $target,
                    'ttl' => 3600,
                    'proxied' => true,
                ]);

                if ($response->successful()) {
                    Log::info("Updated CNAME record for {$this->tenant->domain}.{$baseDomain}, record ID: {$this->tenant->cloudflare_record_id}");
                } else {
                    Log::error("Failed to update CNAME: " . $response->body());
                }
            } elseif ($this->action === 'delete' && $this->tenant->cloudflare_record_id) {
                // Delete CNAME record
                $response = Http::withHeaders($headers)->delete("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records/{$this->tenant->cloudflare_record_id}");

                if ($response->successful()) {
                    Log::info("Deleted CNAME record for {$this->tenant->domain}.{$baseDomain}, record ID: {$this->tenant->cloudflare_record_id}");
                } else {
                    Log::error("Failed to delete CNAME: " . $response->body());
                }
            }
        } catch (\Exception $e) {
            Log::error("Error processing Cloudflare {$this->action} for tenant {$this->tenant->name}: {$e->getMessage()}", ['exception' => $e->getTraceAsString()]);
        }
    }
}
