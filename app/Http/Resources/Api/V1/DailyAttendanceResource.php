<?php

namespace App\Http\Resources\Api\V1;

use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DailyAttendance
 */
class DailyAttendanceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'attendance_date' => optional($this->attendance_date)?->toDateString(),
            'first_punch_in' => optional($this->first_punch_in)?->format('H:i'),
            'last_punch_out' => optional($this->last_punch_out)?->format('H:i'),
            'status' => $this->whenLoaded('status', fn (): ?array => $this->status ? [
                'id' => $this->status->id,
                'status_code' => $this->status->status_code,
                'status' => $this->status->status,
            ] : null),
            'shift' => $this->whenLoaded('shift', fn (): ?array => $this->shift ? [
                'id' => $this->shift->id,
                'code' => $this->shift->code,
                'name' => $this->shift->name,
            ] : null),
        ];
    }
}
