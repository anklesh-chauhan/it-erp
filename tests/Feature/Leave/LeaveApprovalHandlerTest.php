<?php

use App\Domains\Leave\Handlers\LeaveApprovalHandler;
use App\Models\Approval;
use App\Models\ApprovalFlow;
use App\Models\LeaveApplication;
use App\Models\LeaveInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('approves a leave application and its instances once', function () {
    $rep = reportingUser('LeaveApprove');
    $type = reportingLeaveType('Casual');

    $this->actingAs($rep);

    $leave = LeaveApplication::query()->create([
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'from_date' => today(),
        'to_date' => today(),
        'total_days' => 1,
        'approval_status' => 'applied',
        'reason' => 'Family',
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
        'approval_status' => 'applied',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $flow = ApprovalFlow::query()->create(['module' => 'LeaveApplication']);

    $approval = Approval::query()->create([
        'approvable_type' => LeaveApplication::class,
        'approvable_id' => $leave->id,
        'approval_flow_id' => $flow->id,
        'module' => 'LeaveApplication',
        'record_type' => LeaveApplication::class,
        'record_id' => $leave->id,
        'requested_by' => $rep->id,
        'approval_status' => 'approved',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $handler = app(LeaveApprovalHandler::class);
    $handler->handle($leave, $approval);
    $handler->handle($leave->fresh(), $approval);

    expect($leave->fresh()->approval_status)->toBe('approved')
        ->and($leave->instances()->where('approval_status', 'approved')->count())->toBe(1);
});

it('rejects a leave application and its instances', function () {
    $rep = reportingUser('LeaveReject');
    $type = reportingLeaveType('Sick');

    $this->actingAs($rep);

    $leave = LeaveApplication::query()->create([
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'from_date' => today(),
        'to_date' => today(),
        'total_days' => 1,
        'approval_status' => 'applied',
        'reason' => 'Fever',
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
        'approval_status' => 'applied',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $flow = ApprovalFlow::query()->create(['module' => 'LeaveApplication']);

    $approval = Approval::query()->create([
        'approvable_type' => LeaveApplication::class,
        'approvable_id' => $leave->id,
        'approval_flow_id' => $flow->id,
        'module' => 'LeaveApplication',
        'record_type' => LeaveApplication::class,
        'record_id' => $leave->id,
        'requested_by' => $rep->id,
        'approval_status' => 'rejected',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    app(LeaveApprovalHandler::class)->handle($leave, $approval);

    expect($leave->fresh()->approval_status)->toBe('rejected')
        ->and($leave->instances()->where('approval_status', 'rejected')->count())->toBe(1);
});
