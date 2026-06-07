<?php

namespace App\Models;

use App\Enums\IntegrationProvider;

class IntegrationSetting extends BaseModel
{
    protected $fillable = [
        'provider',
        'credentials',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'encrypted:array',
            'is_enabled' => 'boolean',
        ];
    }

    public static function forProvider(IntegrationProvider $provider): self
    {
        return static::firstOrCreate(
            ['provider' => $provider->value],
            [
                'is_enabled' => $provider === IntegrationProvider::GoogleMaps,
                'credentials' => [],
            ]
        );
    }

    public function credential(string $key): ?string
    {
        $value = data_get($this->credentials, $key);

        return filled($value) ? (string) $value : null;
    }

    public function hasCredential(string $key): bool
    {
        return filled($this->credential($key));
    }

    public static function resolve(IntegrationProvider $provider, string $key): ?string
    {
        /** @var self|null $setting */
        $setting = static::query()
            ->where('provider', $provider->value)
            ->first();

        if ($setting !== null && $setting->is_enabled) {
            $stored = $setting->credential($key);

            if ($stored !== null) {
                return $stored;
            }
        }

        $envPath = config("integrations.providers.{$provider->value}.env_fallbacks.{$key}");

        if (! filled($envPath)) {
            return null;
        }

        $envValue = config($envPath);

        return filled($envValue) ? (string) $envValue : null;
    }
}
