<?php

use App\Enums\ApprovalActivityAction;
use App\Events\ApprovalStatusChanged;
use App\Models\ApprovalActivity;
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowStep;
use App\Models\ApprovalSetting;
use App\Models\Employee;
use App\Models\JobRole;
use App\Models\Position;
use App\Models\Territory;
use App\Models\User;
use App\Services\Approval\ApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

function approvalUser(string $name, JobRole $jobRole, Territory $territory): User
{
    $user = User::factory()->create([
        'name' => $name,
        'email' => str($name)->slug().'-'.fake()->unique()->numberBetween(1000, 9999).'@example.test',
    ]);

    $employee = Employee::query()->create([
        'employee_id' => fake()->unique()->numerify('EMP###'),
        'first_name' => $name,
        'last_name' => 'User',
        'email' => $user->email,
        'mobile_number' => fake()->unique()->numerify('90000#####'),
        'login_id' => $user->id,
        'is_active' => true,
    ]);

    $position = Position::query()->create([
        'name' => "{$name} Position",
        'code' => strtoupper(fake()->unique()->bothify('POS###??')),
        'job_role_id' => $jobRole->id,
    ]);

    $position->territories()->attach($territory->id);
    $employee->positions()->attach($position->id, ['is_primary' => true]);

    return $user;
}

function approvalFixture(array $enabledModules = ['Territory']): array
{
    ApprovalSetting::query()->update(['enabled_modules' => $enabledModules]);

    $territory = Territory::query()->create([
        'name' => 'North',
        'code' => fake()->unique()->bothify('T###'),
    ]);

    $requesterRole = JobRole::query()->create(['name' => 'Representative', 'code' => 'REP', 'level' => 1]);
    $managerRole = JobRole::query()->create(['name' => 'Manager', 'code' => 'MGR', 'level' => 2]);
    $directorRole = JobRole::query()->create(['name' => 'Director', 'code' => 'DIR', 'level' => 3]);

    $requester = approvalUser('Requester', $requesterRole, $territory);
    $manager = approvalUser('Manager', $managerRole, $territory);
    $director = approvalUser('Director', $directorRole, $territory);

    return compact('territory', 'requesterRole', 'managerRole', 'directorRole', 'requester', 'manager', 'director');
}

test('it resolves the highest priority effective approval flow and snapshots the request', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    ApprovalFlow::query()->create([
        'module' => 'Territory',
        'territory_id' => $fixture['territory']->id,
        'min_amount' => 0,
        'max_amount' => 1000,
        'priority' => 1,
        'version' => 1,
        'effective_from' => now()->subDay(),
        'effective_to' => now()->addDay(),
    ]);

    $selectedFlow = ApprovalFlow::query()->create([
        'module' => 'Territory',
        'territory_id' => $fixture['territory']->id,
        'min_amount' => 0,
        'max_amount' => 1000,
        'priority' => 10,
        'version' => 2,
        'effective_from' => now()->subDay(),
        'effective_to' => now()->addDay(),
    ]);

    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $selectedFlow->id,
        'step_order' => 1,
        'job_role_id' => $fixture['managerRole']->id,
        'territory_scope' => 'self',
    ]);

    $approval = app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 500);

    expect($approval->approval_flow_id)->toBe($selectedFlow->id)
        ->and($approval->flow_version)->toBe(2)
        ->and($approval->module)->toBe('Territory')
        ->and($approval->record_type)->toBe(Territory::class)
        ->and((float) $approval->requested_amount)->toBe(500.0)
        ->and($approval->selected_steps)->toHaveCount(1)
        ->and($approval->selected_approvers)->toHaveCount(1)
        ->and($approval->submitted_record_summary['id'])->toBe($fixture['territory']->id);
});

test('it approves sequentially and finalizes once', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);

    foreach ([[$fixture['managerRole'], 1], [$fixture['directorRole'], 2]] as [$role, $order]) {
        ApprovalFlowStep::query()->create([
            'approval_flow_id' => $flow->id,
            'step_order' => $order,
            'job_role_id' => $role->id,
            'territory_scope' => 'self',
        ]);
    }

    $approval = app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);

    expect(app(ApprovalService::class)->approve($approval, $fixture['director']->id))->toBeFalse();
    expect(app(ApprovalService::class)->approve($approval, $fixture['manager']->id))->toBeTrue();
    expect($approval->refresh()->approval_status)->toBe('pending');
    expect(app(ApprovalService::class)->approve($approval, $fixture['director']->id))->toBeTrue();
    expect($approval->refresh()->approval_status)->toBe('approved')
        ->and($approval->finalized_at)->not->toBeNull();

    expect($approval->finalized_at)->not->toBeNull();
});

test('it rejects the current step and writes immutable activity', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);
    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 1,
        'job_role_id' => $fixture['managerRole']->id,
        'territory_scope' => 'self',
    ]);

    $approval = app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);

    expect(app(ApprovalService::class)->reject($approval, $fixture['manager']->id, 'Not enough detail'))->toBeTrue()
        ->and($approval->refresh()->approval_status)->toBe('rejected');

    expect(ApprovalActivity::query()->where('approval_id', $approval->id)->where('action', ApprovalActivityAction::Rejected->value)->exists())->toBeTrue();
});

test('it skips optional steps without an approver and keeps later actionable steps', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $missingRole = JobRole::query()->create(['name' => 'Missing', 'code' => 'MISS', 'level' => 4]);
    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);

    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 1,
        'job_role_id' => $missingRole->id,
        'territory_scope' => 'self',
        'can_skip' => true,
    ]);
    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 2,
        'job_role_id' => $fixture['managerRole']->id,
        'territory_scope' => 'self',
    ]);

    $approval = app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);

    expect($approval->steps)->toHaveCount(1)
        ->and($approval->steps->first()->assigned_user_id)->toBe($fixture['manager']->id)
        ->and(ApprovalActivity::query()->where('approval_id', $approval->id)->where('action', ApprovalActivityAction::Skipped->value)->exists())->toBeTrue();
});

test('it fails when a required approver cannot be resolved', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $missingRole = JobRole::query()->create(['name' => 'Missing', 'code' => 'MISS', 'level' => 4]);
    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);

    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 1,
        'job_role_id' => $missingRole->id,
        'territory_scope' => 'self',
        'can_skip' => false,
    ]);

    app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);
})->throws(LogicException::class, 'No approver found');

test('it fails when approval is disabled for the module', function () {
    $fixture = approvalFixture([]);
    $this->actingAs($fixture['requester']);

    app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);
})->throws(LogicException::class, 'Approval is not enabled');

test('it prevents duplicate active approval requests', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);
    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 1,
        'job_role_id' => $fixture['managerRole']->id,
        'territory_scope' => 'self',
    ]);

    app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);
    app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);
})->throws(LogicException::class, 'Approval already exists');

test('it treats a second approval attempt as idempotent after finalization', function () {
    Event::fake([ApprovalStatusChanged::class]);
    $fixture = approvalFixture();
    $this->actingAs($fixture['requester']);

    $flow = ApprovalFlow::query()->create(['module' => 'Territory', 'territory_id' => $fixture['territory']->id]);
    ApprovalFlowStep::query()->create([
        'approval_flow_id' => $flow->id,
        'step_order' => 1,
        'job_role_id' => $fixture['managerRole']->id,
        'territory_scope' => 'self',
    ]);

    $approval = app(ApprovalService::class)->start($fixture['territory'], 'Territory', $fixture['territory']->id, 100);

    expect(app(ApprovalService::class)->approve($approval, $fixture['manager']->id))->toBeTrue();
    expect(app(ApprovalService::class)->approve($approval, $fixture['manager']->id))->toBeFalse();
    expect(ApprovalActivity::query()->where('approval_id', $approval->id)->where('action', ApprovalActivityAction::Approved->value)->count())->toBe(1);
});
