<?php

namespace App\Services\Travel;

use App\Enums\IntegrationProvider;
use App\Models\CityPinCode;
use App\Models\IntegrationSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleRoutesService
{
    public function isConfigured(): bool
    {
        return filled($this->apiKey());
    }

    public function testConnection(): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $response = Http::timeout(15)->get(config('services.google_maps.geocode_url'), [
            'address' => 'Mumbai, India',
            'key' => $this->apiKey(),
            'region' => 'in',
        ]);

        if (! $response->successful()) {
            return false;
        }

        return $response->json('status') === 'OK';
    }

    /**
     * @return array{latitude: float, longitude: float}|null
     */
    public function resolveCoordinates(CityPinCode $areaTown): ?array
    {
        if ($areaTown->latitude !== null && $areaTown->longitude !== null) {
            return [
                'latitude' => (float) $areaTown->latitude,
                'longitude' => (float) $areaTown->longitude,
            ];
        }

        $coordinates = $this->geocode($areaTown);

        if ($coordinates === null) {
            return null;
        }

        $areaTown->forceFill([
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ])->saveQuietly();

        return $coordinates;
    }

    /**
     * @return array{latitude: float, longitude: float}|null
     */
    public function geocode(CityPinCode $areaTown): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $address = $areaTown->full_location;

        if (blank($address)) {
            return null;
        }

        $response = Http::timeout(15)->get(config('services.google_maps.geocode_url'), [
            'address' => $address,
            'key' => $this->apiKey(),
            'region' => 'in',
        ]);

        if (! $response->successful()) {
            Log::warning('Google Geocoding API request failed', [
                'status' => $response->status(),
                'area_town_id' => $areaTown->id,
            ]);

            return null;
        }

        $result = $response->json('results.0.geometry.location');

        if (! is_array($result) || ! isset($result['lat'], $result['lng'])) {
            return null;
        }

        return [
            'latitude' => (float) $result['lat'],
            'longitude' => (float) $result['lng'],
        ];
    }

    public function computeDrivingDistanceKm(
        float $originLat,
        float $originLng,
        float $destinationLat,
        float $destinationLng
    ): ?float {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::timeout(15)
            ->withHeaders([
                'X-Goog-Api-Key' => $this->apiKey(),
                'X-Goog-FieldMask' => 'routes.distanceMeters',
            ])
            ->post(config('services.google_maps.routes_url'), [
                'origin' => [
                    'location' => [
                        'latLng' => [
                            'latitude' => $originLat,
                            'longitude' => $originLng,
                        ],
                    ],
                ],
                'destination' => [
                    'location' => [
                        'latLng' => [
                            'latitude' => $destinationLat,
                            'longitude' => $destinationLng,
                        ],
                    ],
                ],
                'travelMode' => 'DRIVE',
                'routingPreference' => 'TRAFFIC_UNAWARE',
            ]);

        if (! $response->successful()) {
            Log::warning('Google Routes API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $meters = $response->json('routes.0.distanceMeters');

        if (! is_numeric($meters)) {
            return null;
        }

        return round((float) $meters / 1000, 2);
    }

    public function computeDistanceBetweenAreaTowns(CityPinCode $from, CityPinCode $to): ?float
    {
        if ((int) $from->id === (int) $to->id) {
            return 0.0;
        }

        $origin = $this->resolveCoordinates($from);
        $destination = $this->resolveCoordinates($to);

        if ($origin === null || $destination === null) {
            return null;
        }

        return $this->computeDrivingDistanceKm(
            $origin['latitude'],
            $origin['longitude'],
            $destination['latitude'],
            $destination['longitude'],
        );
    }

    protected function apiKey(): ?string
    {
        return IntegrationSetting::resolve(IntegrationProvider::GoogleMaps, 'api_key');
    }
}
