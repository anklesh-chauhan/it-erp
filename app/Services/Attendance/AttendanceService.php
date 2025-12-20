<?php

namespace App\Services\Attendance;

use App\Models\AttendancePunch;
use App\Models\DailyAttendance;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AttendanceService
{
    public static function punchOut(array $data): void
    {
        $employee = Auth::user()->employee;

        if (! isset($data['latitude'], $data['longitude'])) {
            Notification::make()
                ->title('Location permission not allowed')
                ->danger()
                ->send();
            return;
        }

        $attendance = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (! $attendance) {
            Notification::make()
                ->title('No attendance record found for today')
                ->danger()
                ->send();
            return;
        }

        AttendancePunch::create([
            'employee_id' => $employee->id,
            'punch_date' => today(),
            'punch_time' => now()->format('H:i'),
            'punch_type' => 'out',
            'source' => 'sidebar',
            'raw_payload' => [
                'ip' => Request::ip(),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ],
        ]);

        dispatch_sync(
            new \App\Jobs\ProcessDailyAttendanceJob($attendance->id)
        );

        Notification::make()
            ->title('Checked Out Successfully!')
            ->success()
            ->send();
    }
}
