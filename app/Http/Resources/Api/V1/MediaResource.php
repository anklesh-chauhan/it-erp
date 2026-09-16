<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->url,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'captured_at' => optional($this->captured_at)?->toIso8601String(),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->pluck('slug')->values()),
        ];
    }
}
