<?php

use App\Models\Approval;
use App\Models\ApprovalFlow;
use App\Models\LeaveApplication;
use App\Models\Quote;
use App\Services\Approval\ApprovalDomainDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dispatches leave approvals to the leave handler', function () {
    $rep = reportingUser('DispatchLeave');
    $type = reportingLeaveType('On Duty');

    $this->actingAs($rep);

    $leave = LeaveApplication::query()->create([
        'employee_id' => $rep->employee_id,
        'leave_type_id' => $type->id,
        'from_date' => today(),
        'to_date' => today(),
        'total_days' => 1,
        'approval_status' => 'applied',
        'reason' => 'Field duty',
        'applied_at' => now(),
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

    app(ApprovalDomainDispatcher::class)->dispatch($approval);

    expect($leave->fresh()->approval_status)->toBe('approved');
});

it('dispatches quote approvals to the quote handler', function () {
    $rep = reportingUser('DispatchQuote');
    $account = reportingDoctor($rep, 'Dispatch Doctor');
    $contact = reportingContact('Dispatch', 'Contact');

    $this->actingAs($rep);

    $quote = Quote::query()->create([
        'document_number' => 'QT-DSP-'.fake()->unique()->numerify('####'),
        'contact_detail_id' => $contact->id,
        'account_master_id' => $account->id,
        'date' => today(),
        'status' => 'draft',
        'approval_status' => 'pending',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $flow = ApprovalFlow::query()->create(['module' => 'Quote']);

    $approval = Approval::query()->create([
        'approvable_type' => Quote::class,
        'approvable_id' => $quote->id,
        'approval_flow_id' => $flow->id,
        'module' => 'Quote',
        'record_type' => Quote::class,
        'record_id' => $quote->id,
        'requested_by' => $rep->id,
        'approval_status' => 'approved',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    app(ApprovalDomainDispatcher::class)->dispatch($approval);

    expect($quote->fresh()->approval_status)->toBe('approved');
});
