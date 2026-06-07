<?php

namespace App\Enums;

enum IntegrationProvider: string
{
    case GoogleMaps = 'google_maps';
    case Meta = 'meta';
    case Sms = 'sms';
    case Email = 'email';
    case AttendanceDevice = 'attendance_device';

    public function label(): string
    {
        return config("integrations.providers.{$this->value}.label")
            ?? str($this->value)->replace('_', ' ')->title()->toString();
    }

    /**
     * @return array<string, mixed>
     */
    public function config(): array
    {
        return config("integrations.providers.{$this->value}", []);
    }

    public function isTestable(): bool
    {
        return (bool) ($this->config()['testable'] ?? false);
    }
}
