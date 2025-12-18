<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;
use Carbon\Carbon;

class PunchNormalizationService
{
    public function normalize(ShiftMaster $shift, array $rawPunches): array
    {
        if (empty($rawPunches)) {
            return $this->empty();
        }

        // Sort punches
        usort($rawPunches, fn ($a, $b) => strcmp($a['time'], $b['time']));

        $firstIn = collect($rawPunches)->firstWhere('type', 'in');
        $lastOut = collect($rawPunches)->reverse()->firstWhere('type', 'out');

        if (! $firstIn || ! $lastOut) {
            return $this->empty();
        }

        // ✅ SAFE TIME PARSING (KEY FIX)
        $shiftStart = $this->time($shift->start_time);
        $shiftEnd   = $this->time($shift->end_time);

        // Night shift handling
        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        $firstPunchIn = $this->time($firstIn['time']);
        $lastPunchOut = $this->time($lastOut['time']);

        if ($lastPunchOut->lessThan($firstPunchIn)) {
            $lastPunchOut->addDay();
        }

        return [
            'first_punch_in' => $firstPunchIn->format('H:i'),
            'last_punch_out' => $lastPunchOut->format('H:i'),

            'actual_working_minutes' => $this->workingMinutes($rawPunches),

            'late_in_minutes'  => max(0, $shiftStart->diffInMinutes($firstPunchIn, false)),
            'early_in_minutes' => max(0, $firstPunchIn->diffInMinutes($shiftStart, false)),

            'early_out_minutes' => max(0, $lastPunchOut->diffInMinutes($shiftEnd, false)),
            'late_out_minutes'=> max(0, $shiftEnd->diffInMinutes($lastPunchOut, false)),
        ];
    }

    /**
     * Convert ANY time value into Carbon safely
     */
    protected function time($value): Carbon
    {
        if ($value instanceof Carbon) {
            return $value->copy();
        }

        // Remove microseconds if present
        $value = preg_replace('/\.\d+$/', '', (string) $value);

        return Carbon::createFromTimeString($value);
    }

    protected function workingMinutes(array $punches): int
    {
        $minutes = 0;
        $lastIn = null;

        foreach ($punches as $punch) {
            $time = $this->time($punch['time']);

            if ($punch['type'] === 'in') {
                $lastIn = $time;
            }

            if ($punch['type'] === 'out' && $lastIn) {
                if ($time->lessThan($lastIn)) {
                    $time->addDay();
                }

                $minutes += $lastIn->diffInMinutes($time);
                $lastIn = null;
            }
        }

        return $minutes;
    }

    protected function empty(): array
    {
        return [
            'first_punch_in' => null,
            'last_punch_out' => null,
            'actual_working_minutes' => 0,
            'late_in_minutes' => 0,
            'early_out_minutes' => 0,
            'early_in_minutes' => 0,
            'late_out_minutes' => 0,
        ];
    }
}
