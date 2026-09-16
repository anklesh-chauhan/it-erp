<?php

namespace App\Http\Resources\Api\V1;

use App\Models\FieldGpsPoint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FieldGpsPoint
 */
class GpsPointResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_uuid' => $this->client_uuid,
            'visit_id' => $this->visit_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'recorded_at' => optional($this->recorded_at)?->toIso8601String(),
            'accuracy_meters' => $this->accuracy_meters,
            'source' => $this->source,
        ];
    }
}
