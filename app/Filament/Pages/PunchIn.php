<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\DailyAttendance;
use App\Models\EmployeeAttendanceStatus;
use App\Models\AttendancePunch;
use Illuminate\Support\Facades\Request;
use Filament\Facades\Filament;
use Filament\Support\Enums\Alignment;

class PunchIn extends Page
{
    protected string $view = 'filament.pages.punch-in';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Punch In/Out';
    protected static ?string $navigationLabel = 'Punch In/Out';

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        // Show in sidebar ONLY if user has 'marketing' role
        return $user?->hasRole('marketing') ?? false;
    }

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
                Action::make('punchIn')
                    ->label('👋 Punch In')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-clock')
                    ->modal(false)
                    ->requiresConfirmation(false)
                    ->extraAttributes([
                        'x-on:click.prevent' => "startPunchIn(\$wire)"
                    ])
            ];
        }

        // ────────────────────────────── CHECK OUT ──────────────────────────────
        if ($todayAttendance && $todayAttendance->punches()->where('punch_type', 'out')->doesntExist()) {
            return [
                Action::make('punchOut')
                    ->label('🚪 Punch Out')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->modal(false)
                    ->button()
                    ->requiresConfirmation(false)
                    ->extraAttributes([
                        'x-on:click.prevent' => "startPunchOut(\$wire)"
                    ])
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

    public function doPunchIn(array $data): void
    {
        $employee = Auth::user()->employee;

        if (! isset($data['latitude'], $data['longitude'])) {
            Notification::make()
                ->title('Location permission not allowed')
                ->danger()
                ->send();
            return;
        }

        if (! $employee) {
            Notification::make()
                ->title('Employee not linked')
                ->danger()
                ->send();
            return;
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

        $firstPunchIn = now()->format('H:i');

        $attendance = DailyAttendance::create([
            'employee_id' => $employee->id,
            'shift_master_id' => $shift->id,
            'attendance_date' => today(),
            'first_punch_in' => $firstPunchIn,
            'status_id' => EmployeeAttendanceStatus::where('status_code', 'SP')->value('id'),
        ]);

        AttendancePunch::create([
            'employee_id' => $employee->id,
            'punch_date' => today(),
            'punch_time' => $firstPunchIn,
            'punch_type' => 'in',
            'source' => 'punch-in-page',
            'raw_payload' => [
                'ip' => Request::ip(),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ],
        ]);

        Notification::make()
            ->title('Punch In Successfully!')
            ->success()
            ->send();
    }

    public function doPunchOut(array $data): void
    {
        $employee = Auth::user()->employee;

        if (! isset($data['latitude'], $data['longitude'])) {
            Notification::make()
                ->title('Location permission not allowed')
                ->danger()
                ->send();
            return;
        }

        $todayAttendance = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (! $todayAttendance) {
            Notification::make()
                ->title('No attendance record found for today')
                ->danger()
                ->send();
            return;
        }

        // Insert OUT punch
        AttendancePunch::create([
            'employee_id' => $employee->id,
            'punch_date' => today(),
            'punch_time' => now()->format('H:i'),
            'punch_type' => 'out',
            'source' => 'dashboard',
            'raw_payload' => [
                'ip' => Request::ip(),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ],
        ]);

        // Process attendance (calculate hours, status, etc.)
        dispatch_sync(new \App\Jobs\ProcessDailyAttendanceJob($todayAttendance->id));

        Notification::make()
            ->title('Checked Out Successfully!')
            ->success()
            ->send();

        // Refresh the dashboard to update button state
        $this->dispatch('$refresh');
    }

}
