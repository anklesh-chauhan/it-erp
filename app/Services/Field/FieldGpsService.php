<?php

namespace App\Services\Field;

use App\Models\FieldGpsPoint;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FieldGpsService
{
    /**
     * @param  array{points: list<array<string, mixed>>, source?: string|null, device_id?: string|null}  $payload
     * @return Collection<int, FieldGpsPoint>
     */
    public function storeMany(User $user, array $payload): Collection
    {
        $source = $payload['source'] ?? 'field-api';
        $deviceId = $payload['device_id'] ?? null;
        $employeeId = $user->employee?->id;

        return DB::transaction(function () use ($user, $payload, $source, $deviceId, $employeeId): Collection {
            $stored = collect();

            foreach ($payload['points'] as $point) {
                $visitId = isset($point['visit_id']) ? (int) $point['visit_id'] : null;

                if ($visitId !== null) {
                    $this->assertVisitOwned($user, $visitId);
                }

                $clientUuid = $point['client_uuid'] ?? null;

                if (filled($clientUuid)) {
                    $existing = FieldGpsPoint::query()
                        ->where('user_id', $user->id)
                        ->where('client_uuid', $clientUuid)
                        ->first();

                    if ($existing) {
                        $stored->push($existing);

                        continue;
                    }
                }

                $stored->push(FieldGpsPoint::query()->create([
                    'user_id' => $user->id,
                    'employee_id' => $employeeId,
                    'visit_id' => $visitId,
                    'latitude' => (float) $point['latitude'],
                    'longitude' => (float) $point['longitude'],
                    'recorded_at' => $point['recorded_at'],
                    'accuracy_meters' => $point['accuracy_meters'] ?? null,
                    'source' => $point['source'] ?? $source,
                    'device_id' => $point['device_id'] ?? $deviceId,
                    'client_uuid' => $clientUuid,
                    'raw_payload' => $point['raw_payload'] ?? null,
                ]));
            }

            return $stored;
        });
    }

    private function assertVisitOwned(User $user, int $visitId): void
    {
        $ownsVisit = Visit::query()
            ->whereKey($visitId)
            ->where('employee_id', $user->id)
            ->exists();

        if (! $ownsVisit) {
            throw ValidationException::withMessages([
                'visit_id' => 'Visit not found.',
            ]);
        }
    }
}
