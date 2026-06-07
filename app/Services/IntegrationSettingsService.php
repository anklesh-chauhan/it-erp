<?php

namespace App\Services;

use App\Enums\IntegrationProvider;
use App\Models\IntegrationSetting;
use App\Services\Travel\GoogleRoutesService;
use Filament\Notifications\Notification;

class IntegrationSettingsService
{
    public function resolve(IntegrationProvider $provider, string $key): ?string
    {
        return IntegrationSetting::resolve($provider, $key);
    }

    /**
     * @return array<string, mixed>
     */
    public function formStateForProvider(IntegrationProvider $provider): array
    {
        $setting = IntegrationSetting::forProvider($provider);
        $config = $provider->config();

        $state = [
            'is_enabled' => $setting->is_enabled,
        ];

        foreach (array_keys($config['fields'] ?? []) as $fieldKey) {
            $state["has_{$fieldKey}"] = $setting->hasCredential($fieldKey);
            $state[$fieldKey] = null;
        }

        return $state;
    }

    /**
     * @param  array<string, mixed>  $state
     */
    public function saveProvider(IntegrationProvider $provider, array $state): void
    {
        $setting = IntegrationSetting::forProvider($provider);
        $credentials = $setting->credentials ?? [];

        foreach (array_keys($provider->config()['fields'] ?? []) as $fieldKey) {
            if (filled($state[$fieldKey] ?? null)) {
                $credentials[$fieldKey] = $state[$fieldKey];
            }
        }

        $setting->update([
            'is_enabled' => (bool) ($state['is_enabled'] ?? false),
            'credentials' => $credentials,
        ]);
    }

    public function testProvider(IntegrationProvider $provider): bool
    {
        return match ($provider) {
            IntegrationProvider::GoogleMaps => app(GoogleRoutesService::class)->testConnection(),
            default => false,
        };
    }

    public function notifyTestResult(IntegrationProvider $provider, bool $passed): void
    {
        if ($passed) {
            Notification::make()
                ->title("{$provider->label()} connection successful")
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title("{$provider->label()} connection failed")
            ->body('Verify credentials and that required APIs are enabled.')
            ->danger()
            ->send();
    }
}
