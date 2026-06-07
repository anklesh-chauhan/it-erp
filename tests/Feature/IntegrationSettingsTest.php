<?php

use App\Enums\IntegrationProvider;
use App\Models\IntegrationSetting;
use App\Services\IntegrationSettingsService;
use App\Services\Travel\GoogleRoutesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('integration setting stores encrypted credentials per provider', function () {
    $setting = IntegrationSetting::forProvider(IntegrationProvider::GoogleMaps);

    $setting->update([
        'is_enabled' => true,
        'credentials' => [
            'api_key' => 'stored-secret-key',
        ],
    ]);

    expect(IntegrationSetting::resolve(IntegrationProvider::GoogleMaps, 'api_key'))
        ->toBe('stored-secret-key');
});

test('integration setting falls back to env when disabled', function () {
    Config::set('services.google_maps.api_key', 'env-fallback-key');

    IntegrationSetting::forProvider(IntegrationProvider::GoogleMaps)->update([
        'is_enabled' => false,
        'credentials' => [
            'api_key' => 'stored-secret-key',
        ],
    ]);

    expect(IntegrationSetting::resolve(IntegrationProvider::GoogleMaps, 'api_key'))
        ->toBe('env-fallback-key');
});

test('integration setting prefers stored credentials over env when enabled', function () {
    Config::set('services.google_maps.api_key', 'env-fallback-key');

    IntegrationSetting::forProvider(IntegrationProvider::GoogleMaps)->update([
        'is_enabled' => true,
        'credentials' => [
            'api_key' => 'stored-secret-key',
        ],
    ]);

    expect(IntegrationSetting::resolve(IntegrationProvider::GoogleMaps, 'api_key'))
        ->toBe('stored-secret-key');
});

test('integration settings service saves provider credentials without clearing existing secrets', function () {
    IntegrationSetting::forProvider(IntegrationProvider::Sms)->update([
        'is_enabled' => true,
        'credentials' => [
            'api_key' => 'existing-sms-key',
            'sender_id' => 'OLDCO',
        ],
    ]);

    app(IntegrationSettingsService::class)->saveProvider(IntegrationProvider::Sms, [
        'is_enabled' => true,
        'sender_id' => 'NEWCO',
    ]);

    $setting = IntegrationSetting::forProvider(IntegrationProvider::Sms)->fresh();

    expect($setting->credential('api_key'))->toBe('existing-sms-key');
    expect($setting->credential('sender_id'))->toBe('NEWCO');
});

test('google routes service uses integration settings api key for requests', function () {
    IntegrationSetting::forProvider(IntegrationProvider::GoogleMaps)->update([
        'is_enabled' => true,
        'credentials' => [
            'api_key' => 'stored-secret-key',
        ],
    ]);

    Http::fake([
        'maps.googleapis.com/*' => Http::response([
            'status' => 'OK',
            'results' => [
                [
                    'geometry' => [
                        'location' => [
                            'lat' => 19.0760,
                            'lng' => 72.8777,
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    expect(app(GoogleRoutesService::class)->testConnection())->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->data()['key'] === 'stored-secret-key';
    });
});
