<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitPhotoRequest extends FormRequest
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
            'photo' => ['required', 'file', 'image', 'max:10240'],
            'tag' => ['required', 'string', Rule::in(['check-in', 'check-out', 'general-visit'])],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'captured_at' => ['nullable', 'date'],
        ];
    }
}
