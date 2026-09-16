<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreGpsPointsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'source' => ['nullable', 'string', 'max:100'],
            'device_id' => ['nullable', 'string', 'max:255'],
            'points' => ['required', 'array', 'min:1', 'max:500'],
            'points.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'points.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'points.*.recorded_at' => ['required', 'date'],
            'points.*.accuracy_meters' => ['nullable', 'numeric', 'min:0'],
            'points.*.visit_id' => ['nullable', 'integer', 'exists:visits,id'],
            'points.*.client_uuid' => ['nullable', 'uuid'],
            'points.*.source' => ['nullable', 'string', 'max:100'],
            'points.*.device_id' => ['nullable', 'string', 'max:255'],
            'points.*.raw_payload' => ['nullable', 'array'],
        ];
    }
}
