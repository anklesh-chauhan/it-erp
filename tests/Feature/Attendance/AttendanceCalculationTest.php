<?php

use App\Models\ShiftMaster;
use App\Services\Attendance\AttendanceCalculationService;
use App\Services\Attendance\PunchNormalizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('normalizes punches and calculates working minutes from shift setups', function () {
    $this->actingAs(reportingUser('PunchCalc'));

    $shift = ShiftMaster::query()->create([
        'code' => 'GEN_'.fake()->unique()->numerify('##'),
        'name' => 'General',
        'start_time' => '09:00:00',
        'end_time' => '18:00:00',
        'shift_type' => 'fixed',
        'week_off_type' => 'none',
    ]);

    expect($shift->generalSetup)->not->toBeNull();

    $normalized = app(PunchNormalizationService::class)->normalize($shift, [
        ['type' => 'in', 'time' => '09:15:00'],
        ['type' => 'out', 'time' => '18:00:00'],
    ]);

    expect((int) $normalized['late_in_minutes'])->toBe(15)
        ->and($normalized['actual_working_minutes'])->toBe(525);

    $result = app(AttendanceCalculationService::class)->calculate($shift, $normalized);

    expect($result['final_working_minutes'])->toBe(525)
        ->and($result['is_absent'])->toBeFalse();
});
