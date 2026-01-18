<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\{
    LeaveInstance,
    Holiday,
    WeekOff
};

class LeaveDateValidator
{
    public function validate(
        int $employeeId,
        Carbon $from,
        Carbon $to
    ): void {
        foreach ($from->daysUntil($to) as $date) {

            // 1️⃣ Duplicate leave
            if ($this->leaveAlreadyExists($employeeId, $date)) {
                throw ValidationException::withMessages([
                    'leave' => [
                        "Leave already applied for {$date->toDateString()}",
                    ],
                ]);
            }

            // 2️⃣ Holiday
            if ($this->isHoliday($date)) {
                throw ValidationException::withMessages([
                    'leave' => [
                        "Leave not allowed on holiday: {$date->toDateString()}",
                    ],
                ]);
            }

            // 3️⃣ Weekoff
            if ($this->isWeekOff($employeeId, $date)) {
                throw ValidationException::withMessages([
                    'leave' => [
                        "Leave not allowed on weekly off: {$date->toDateString()}",
                    ],
                ]);
            }
        }
    }

    protected function leaveAlreadyExists(int $employeeId, Carbon $date): bool
    {
        return LeaveInstance::query()
            ->where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->whereIn('approval_status', ['applied', 'approved'])
            ->exists();
    }

    protected function isHoliday(Carbon $date): bool
    {
        return Holiday::whereDate('date', $date)->exists();
    }

    protected function isWeekOff(int $employeeId, Carbon $date): bool
    {
        return WeekOff::query()
            ->where('is_active', true)
            ->where('day_of_week', $date->dayOfWeek)
            ->where(function ($q) use ($employeeId) {
                $q->whereNull('employee_id')
                  ->orWhere('employee_id', $employeeId);
            })
            ->exists();
    }
}
