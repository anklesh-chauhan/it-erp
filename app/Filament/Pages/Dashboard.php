<?php

namespace App\Filament\Pages;

use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        $employee = Auth::user()->employee;

        // If user is not an employee, hide attendance actions
        if (! $employee) {
            return [];
        }

        $today = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        // ────────────────────────────── CHECK IN ──────────────────────────────
        if (! $today) {
            return [
                Action::make('checkIn')
                    ->label('👋 Check In')
                    ->color('success')
                    ->icon('heroicon-o-clock')
                    ->modal(false) // ⬅ add this
                    ->requiresConfirmation(false) // ⬅ add this
                    ->extraAttributes([
                        'x-on:click.prevent' => "startGeolocation(\$wire, 'checkIn')",
                    ])
                    ->action(function (array $arguments) use ($employee) {

                        if (! isset($arguments['latitude'], $arguments['longitude'])) {
                            return false; // ⚠️ IMPORTANT
                        }

                        EmployeeAttendance::create([
                            'employee_id' => $employee->id,
                            'attendance_date' => today(),
                            'check_in' => now(),
                            'check_in_ip' => Request::ip(),
                            'check_in_latitude' => $arguments['latitude'],
                            'check_in_longitude' => $arguments['longitude'],
                            'status_id' => 9,
                        ]);

                        Notification::make()->title('Checked In Successfully!')->success()->send();
                    }),
            ];
        }

        // ────────────────────────────── CHECK OUT ──────────────────────────────
        if ($today && ! $today->check_out) {
            return [
                Action::make('checkOut')
                    ->label('🚪 Check Out')
                    ->color('danger')
                    ->modal(false) // ⬅ add this
                    ->requiresConfirmation(false) // ⬅ add this
                    ->extraAttributes([
                        'x-on:click.prevent' => "startGeolocation(\$wire, 'checkOut')",
                    ])
                    ->action(function (array $arguments) use ($today) {
                        if (! isset($arguments['latitude'], $arguments['longitude'])) {
                            return false; // ⚠️ IMPORTANT
                        }

                        $today->update([
                            'check_out' => now(),
                            'check_out_ip' => Request::ip(),
                            'check_out_latitude' => $arguments['latitude'] ?? null,
                            'check_out_longitude' => $arguments['longitude'] ?? null,
                            'status_id' => 1,
                        ]);

                        Notification::make()->title('Checked Out Successfully!')->warning()->send();

                    }),
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
