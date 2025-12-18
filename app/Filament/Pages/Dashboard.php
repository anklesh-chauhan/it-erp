<?php

namespace App\Filament\Pages;

use App\Models\DailyAttendance;
use App\Models\AttendancePunch;
use App\Models\EmployeeAttendanceStatus;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Filament\Actions\Action;
use Spatie\Multitenancy\Models\Tenant;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        $employee = Auth::user()->employee;

        // If user is not an employee, hide attendance actions
        if (! $employee) {
            return [];
        }

        $todayAttendance = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        // ────────────────────────────── CHECK IN ──────────────────────────────
        if (! $todayAttendance) {
            return [
                Action::make('checkIn')
                    ->label('👋 Check In')
                    ->color('success')
                    ->icon('heroicon-o-clock')
                    ->modal(false)
                    ->requiresConfirmation(false)
                    ->extraAttributes([
                        'x-on:click.prevent' => "startGeolocation(\$wire, 'checkIn')",
                    ])
                    ->action(function (array $arguments) use ($employee) {

                        if (! isset($arguments['latitude'], $arguments['longitude'])) {
                            return false;
                        }

                        $shift = $employee->currentShiftForDate(today());

                        if (! $shift) {
                            Notification::make()
                                ->title('Shift not assigned')
                                ->body('No active shift is assigned for today. Please contact HR.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // 1️⃣ Create daily attendance shell
                        $attendance = DailyAttendance::create([
                            'employee_id' => $employee->id,
                            'shift_master_id' => $shift->id,
                            'attendance_date' => today(),
                            'status_id' => EmployeeAttendanceStatus::where('status_code', 'SP')->value('id'),
                        ]);

                        // 2️⃣ Insert IN punch
                        AttendancePunch::create([
                            'employee_id' => $employee->id,
                            'punch_date' => today(),
                            'punch_time' => now()->format('H:i'),
                            'punch_type' => 'in',
                            'source' => 'dashboard',
                            'raw_payload' => [
                                'ip' => Request::ip(),
                                'latitude' => $arguments['latitude'],
                                'longitude' => $arguments['longitude'],
                            ],
                        ]);

                        $attendance->save();

                        Notification::make()
                            ->title('Checked In Successfully!')
                            ->success()
                            ->send();
                    })->after(fn (Action $action) => $action->getLivewire()->dispatch('$refresh')),
            ];
        }

        // ────────────────────────────── CHECK OUT ──────────────────────────────
        if ($todayAttendance && $todayAttendance->punches()->where('punch_type', 'out')->doesntExist()) {
            return [
                Action::make('checkOut')
                    ->label('🚪 Check Out')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->modal(false)
                    ->button()
                    ->requiresConfirmation(false)
                    ->extraAttributes([
                        'x-on:click.prevent' => "startGeolocation(\$wire, 'checkOut')",
                    ])
                    ->action(function (array $arguments) use ($todayAttendance, $employee) {

                        if (! isset($arguments['latitude'], $arguments['longitude'])) {
                            return false;
                        }

                        // 1️⃣ Insert OUT punch
                        AttendancePunch::create([
                            'employee_id' => $employee->id,
                            'punch_date' => today(),
                            'punch_time' => now()->format('H:i'),
                            'punch_type' => 'out',
                            'source' => 'dashboard',
                            'raw_payload' => [
                                'ip' => Request::ip(),
                                'latitude' => $arguments['latitude'],
                                'longitude' => $arguments['longitude'],
                            ],
                        ]);

                        // dispatch(new \App\Jobs\ProcessDailyAttendanceJob(
                        //     $employee->id,
                        //     today()
                        // ))->onTenant(Tenant::current());

                        dispatch_sync(new \App\Jobs\ProcessDailyAttendanceJob(
                            $todayAttendance->id
                        ));

                        // 2️⃣ Status remains SP until calculation job runs
                        Notification::make()
                            ->title('Checked Out Successfully!')
                            ->warning()
                            ->send();

                    })->after(fn (Action $action) => $action->getLivewire()->dispatch('$refresh')),
            ];
        }

        // ────────────────────────────── COMPLETED ──────────────────────────────
        return [
            Action::make('completed')
                ->label('✔ Attendance Completed')
                ->color('gray')
                ->disabled()
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
