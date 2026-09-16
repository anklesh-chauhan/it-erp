<?php

use App\Models\AttendancePunch;
use App\Models\DailyAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('punches in with gps and creates daily attendance', function () {
    $user = reportingUser('PunchApi');
    $shift = reportingShift('Field Shift');
    reportingAssignShift($user, $shift);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/attendance/punch-in', [
        'latitude' => 19.0760,
        'longitude' => 72.8777,
        'source' => 'flutter',
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.employee_id', $user->employee->id);

    expect(DailyAttendance::query()->where('employee_id', $user->employee->id)->count())->toBe(1)
        ->and(AttendancePunch::query()->where('employee_id', $user->employee->id)->where('punch_type', 'in')->count())->toBe(1);
});

it('rejects punch in without a shift', function () {
    $user = reportingUser('NoShiftApi');

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/attendance/punch-in', [
        'latitude' => 19.0760,
        'longitude' => 72.8777,
    ])->assertUnprocessable();
});

it('punches out after punch in', function () {
    $user = reportingUser('PunchOutApi');
    $shift = reportingShift('Out Shift');
    reportingAssignShift($user, $shift);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/attendance/punch-in', [
        'latitude' => 19.0760,
        'longitude' => 72.8777,
    ])->assertSuccessful();

    $this->postJson('/api/v1/attendance/punch-out', [
        'latitude' => 19.0800,
        'longitude' => 72.8800,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.employee_id', $user->employee->id);

    expect(AttendancePunch::query()->where('employee_id', $user->employee->id)->where('punch_type', 'out')->count())->toBe(1);
});

it('returns today attendance', function () {
    $user = reportingUser('TodayAttApi');
    $shift = reportingShift('Today Shift');
    reportingAssignShift($user, $shift);

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/attendance/today')
        ->assertSuccessful()
        ->assertJsonPath('data', null);

    $this->postJson('/api/v1/attendance/punch-in', [
        'latitude' => 19.0760,
        'longitude' => 72.8777,
    ])->assertSuccessful();

    $this->getJson('/api/v1/attendance/today')
        ->assertSuccessful()
        ->assertJsonPath('data.employee_id', $user->employee->id);
});
