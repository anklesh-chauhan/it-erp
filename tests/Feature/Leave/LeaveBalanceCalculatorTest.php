<?php

use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\LeaveInstance;
use App\Services\Attendance\LeaveBalanceCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('subtracts approved leave instances from opening balance', function () {
    $rep = reportingUser('BalanceRep');
    $type = reportingLeaveType('Privilege');

    $this->actingAs($rep);

    LeaveBalance::query()->create([
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'opening_balance' => 10,
        'year_start_date' => now()->startOfYear(),
        'year_end_date' => now()->endOfYear(),
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $leave = LeaveApplication::query()->create([
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'from_date' => today(),
        'to_date' => today()->addDay(),
        'total_days' => 2,
        'approval_status' => 'approved',
        'reason' => 'Vacation',
        'applied_at' => now(),
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    LeaveInstance::query()->create([
        'leave_application_id' => $leave->id,
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'date' => today(),
        'pay_factor' => 1,
        'approval_status' => 'approved',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    LeaveInstance::query()->create([
        'leave_application_id' => $leave->id,
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'date' => today()->addDay(),
        'pay_factor' => 1,
        'approval_status' => 'approved',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $result = app(LeaveBalanceCalculator::class)->calculate(
        employeeId: (int) $rep->employee_id,
        leaveTypeId: $type->id,
        asOnDate: today()->addDay(),
    );

    expect((float) $result['applied'])->toBe(2.0)
        ->and((float) $result['closing'])->toBe(8.0);
});
