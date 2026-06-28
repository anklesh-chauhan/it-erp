# Approval Workflow Guide

This guide explains how to configure, use, and operate the centralized approval framework.

The framework supports module-level approval settings, versioned approval flows, ordered approver steps, immutable request snapshots, current-step-only approvals, audit activity, delegation, SLA due dates, and overdue escalation.

## Core Concepts

- `Approval Settings`: Select which modules can use approvals.
- `Approval Flows`: Define the rule that decides when a record needs approval.
- `Approval Flow Steps`: Define the approver chain for a flow.
- `Approvals`: Store the submitted approval request and its snapshot.
- `Approval Steps`: Store each assigned approval action in order.
- `Approval Activities`: Store the audit history for submitted, assigned, approved, rejected, skipped, reassigned, escalated, and cancelled events.
- `Approval Delegations`: Route approvals to substitute approvers for out-of-office or temporary delegation.
- `My Approvals`: Shows only approval steps that are currently actionable by the signed-in user.

## Navigation

Menu location can depend on the Filament panel and permissions, but the approval screens are:

- `Approval Settings`
- `Approval Flows`
- `Approval Delegations`
- `Approvals`
- `My Approvals`

## Approval Settings

Approval Settings control which modules are allowed to use the approval workflow.

### Enable Approval for a Module

1. Open `Approval Settings`.
2. Edit the existing settings record.
3. In `Models Requiring Approval`, select the modules that should require approval.
4. Save.

Only selected modules can use approval flows. If a module is not enabled, users cannot submit records from that module for approval even if an approval flow exists.

## Approval Flows

Approval Flows define the matching rule used when a record is submitted.

### Flow Fields

- `Module`: The enabled module this flow applies to.
- `Condition Type`: The flow condition style. Current options include `Amount range` and `Unconditional`.
- `Priority`: Higher priority flows win when multiple active flows match.
- `Version`: Stored on the approval request at submission time so historical approvals keep the exact flow version used.
- `Active`: Only active flows are considered during submission.
- `Territory`: Optional. Use when the flow applies to one territory. Leave blank for a general flow.
- `Min Amount`: Optional lower amount boundary.
- `Max Amount`: Optional upper amount boundary.
- `Effective From`: Optional first date this flow can be used.
- `Effective To`: Optional last date this flow can be used.

### Create an Approval Flow

1. Open `Approval Flows`.
2. Click `Create`.
3. Complete `Definition`.
4. Complete `Matching`.
5. Save.
6. Open the saved flow and add approval steps.

### Flow Matching Rules

When a record is sent for approval, the system selects an active flow where:

- The module matches.
- The current date is inside the effective date window.
- The territory is blank or matches the record territory.
- The requested amount is inside the min/max amount range.

If multiple flows match, the system sorts by:

1. Highest `Priority`.
2. Territory-specific flow before general flow.
3. Highest `Version`.

### Overlap Validation

Active flows are validated to avoid overlapping definitions for the same:

- Module
- Territory
- Condition type
- Version
- Effective date range
- Amount range

This prevents ambiguous flow resolution. Use different priorities and non-overlapping amount/date ranges when creating multiple flows for the same module.

## Approval Flow Steps

Approval Flow Steps define the approver chain for a flow.

### Step Fields

- `Step Order`: The approval sequence. Lower numbers approve first.
- `Job Role`: The approver role for this step.
- `Territory Scope`: How territory is applied when finding the approver.
- `SLA Hours`: Number of hours before the assigned step becomes overdue.
- `Can Skip`: Allows the step to be skipped when no matching approver can be found.

### Territory Scope

- `Same Territory`: Finds an approver assigned to the same territory as the record.
- `Child Territories`: Finds an approver assigned to the same territory or child territories.
- `All Territories`: Finds an approver by job role without territory restriction.

### Step Order and Current-Step Rules

Approvals happen in step order.

Only the earliest pending step is actionable. Later pending steps do not appear in `My Approvals` and cannot be approved early.

### Seniority Rule

If the requester already has the same or a senior job role level compared with a configured step, that step is skipped.

This prevents a user from needing approval from the same or a more junior level.

### No Approver Behavior

If no approver is found:

- If `Can Skip` is enabled, the step is skipped and an audit activity is written.
- If `Can Skip` is disabled, submission fails with a no-approver error.

## Delegation and Substitute Approvers

Approval Delegations allow approvals assigned to one user to be routed to another user.

### Delegation Fields

- `Delegator`: The original approver.
- `Delegate`: The substitute approver.
- `Module`: Optional. Leave blank to apply to all modules.
- `Starts At`: Optional start date/time.
- `Ends At`: Optional end date/time.
- `Active`: Only active delegations are used.
- `Reason`: Optional note.

### Delegation Behavior

When an approval step is assigned, the system checks for an active delegation for the selected approver and module.

If a delegation exists:

- The step is assigned to the delegate.
- The original approver is stored as `reassigned_from_user_id`.
- The selected approver snapshot records the delegated-from user.

## Sending Records for Approval

Users can send a record for approval when:

- The module is enabled in `Approval Settings`.
- An active matching approval flow exists.
- The record does not already have an active approval.
- The record status allows approval submission.

### Send One Record

1. Open the module record list or record page.
2. Click `Send for Approval`.
3. The system creates an approval request.
4. The system snapshots the submitted record and assigns the first actionable step.

### Send Multiple Records

1. Open the module record list.
2. Select records.
3. Click `Send for Bulk Approval`.
4. Review the summary notification for sent, skipped, and unsupported records.

## Approval Request Snapshot

When a record is submitted, the approval request stores a snapshot of the approval decision context.

Stored fields include:

- Module
- Record type
- Record ID
- Requested amount
- Territory
- Requester
- Approval flow ID
- Flow version
- Selected steps
- Selected approvers
- Submitted record summary
- Approval status
- Completed timestamp
- Finalized timestamp

The snapshot is shown on the `Approvals` view. This makes old approvals understandable even if the original record or flow definition changes later.

## Approving or Rejecting

Approvers should normally use `My Approvals`.

`My Approvals` shows only pending steps assigned to the signed-in user where that step is the current actionable step for the approval request.

### Approve

1. Open `My Approvals`.
2. Review the approval request and submitted record summary.
3. Approve the current pending step.

When the final required step is approved:

- The approval becomes `Approved`.
- `completed_at` and `finalized_at` are set.
- The approval status event is dispatched after the database transaction commits.
- The module-specific approval handler updates the original record.

### Reject

1. Open `My Approvals`.
2. Review the approval request.
3. Reject the current pending step and enter comments.

When a step is rejected:

- The approval becomes `Rejected`.
- `completed_at` and `finalized_at` are set.
- The approval status event is dispatched after commit.
- The module-specific approval handler updates the original record where a handler exists.

## State Machine

Approval step statuses use explicit transitions.

Allowed current-step transitions:

- `pending` to `approved`
- `pending` to `rejected`
- `pending` to `skipped`
- `pending` to `reassigned`
- `pending` to `cancelled`

Terminal step statuses cannot transition again.

Approval request statuses include:

- `draft`: Approval is not active.
- `pending`: Approval has started and is waiting for one or more approvers.
- `approved`: All required approval steps are complete.
- `rejected`: One approver rejected the request.
- `cancelled`: Approval was cancelled before completion.

## Audit Activity

The approval framework writes activity rows for important events.

Activity actions include:

- `submitted`
- `assigned`
- `approved`
- `rejected`
- `skipped`
- `reassigned`
- `escalated`
- `cancelled`

Each activity can store:

- Approval ID
- Approval step ID
- Actor
- Action
- From status
- To status
- Comments
- Metadata
- Timestamp

The `Approvals` view shows the activity log.

## SLA, Reminders, and Escalation

Each approval flow step has `SLA Hours`.

When the step is assigned:

- `due_at` is calculated from `SLA Hours`.
- `reminded_at` is available for reminder tracking.
- `escalated_at` is available for escalation tracking.

The `EscalateOverdueApprovals` job finds pending steps where:

- `due_at` is in the past.
- `escalated_at` is empty.

The job then:

- Sets `escalated_at`.
- Writes an `escalated` approval activity.

Schedule or dispatch this job according to business needs.

## Finalization and Domain Handlers

There is one finalization path.

The approval service:

- Locks the approval and current step during approve/reject.
- Updates approval status.
- Sets `completed_at` and `finalized_at`.
- Dispatches `ApprovalStatusChanged` after the transaction commits.

The listener handles the event and calls the module-specific domain handler through `ApprovalDomainDispatcher`.

This avoids double-finalization and prevents domain handlers from running before the approval transaction is committed.

## Concurrency Protection

Approval actions are protected by database locks:

- The approvable record is locked during submission.
- The approval is locked during approve/reject.
- The current step is locked during approve/reject.

Finalization is idempotent:

- Only approvals with empty `finalized_at` can be finalized.
- A second approval attempt after finalization returns false and does not write another approval activity.

## Duplicate Approval Prevention

The system prevents starting a second active approval for the same record when an existing approval is:

- `pending`
- `approved`

Rejected or cancelled approvals can be handled according to the module’s resubmission rules.

## Common Issues

### Send for Approval Is Not Visible

Check that:

- The module is selected in `Approval Settings`.
- An active approval flow exists for the module.
- The record matches flow territory, amount, and effective date conditions.
- The record does not already have an active approval.
- The record status allows approval submission.

### Approval Fails with No Flow Found

Create or activate an approval flow for the module.

Also confirm:

- Effective dates include today.
- Territory matches or is blank.
- Amount falls inside min/max.
- The module is enabled in `Approval Settings`.

### Approval Fails with No Approver Found

Check the approval step:

- The selected `Job Role` has at least one user assigned through employee positions.
- The approver is assigned to the required territory when territory scope is not `All Territories`.
- A delegation rule is active if the original approver is unavailable.
- Set `Can Skip` only when the business allows that step to be optional.

### A Later Approver Cannot Approve

This is expected.

Only the earliest pending step is actionable. The current pending step must be approved or rejected first.

### Module Appears in Settings but Not in Flow Module List

Save `Approval Settings` again and reload the `Approval Flows` form.

The flow module list is based on enabled modules from Approval Settings.

### Flow Cannot Be Saved Because of Overlap

Check existing active flows for the same module, territory, condition type, version, effective dates, and amount range.

Resolve the conflict by changing one of:

- Date range
- Amount range
- Version
- Territory
- Active status

### Overdue Steps Are Not Escalating

Check that:

- `SLA Hours` is configured on the flow step.
- The approval step has a `due_at` value.
- The `EscalateOverdueApprovals` job is scheduled or dispatched.
- Queue workers are running if the job is queued.

## Best Practices

- Enable only modules that truly require approval.
- Start with one general flow per module before adding territory-specific flows.
- Use priority deliberately when multiple flows can match.
- Version flows when changing approval policy.
- Avoid overlapping active flows.
- Keep step orders simple, such as `1`, `2`, and `3`.
- Use `Can Skip` only for genuinely optional approval levels.
- Configure realistic `SLA Hours`.
- Use delegation for known out-of-office periods.
- Review the approval activity log during troubleshooting.
- Test each new flow with a sample record before relying on it in daily work.

## Developer Notes

Important implementation files:

- `app/Services/Approval/ApprovalService.php`
- `app/Models/Approval.php`
- `app/Models/ApprovalFlow.php`
- `app/Models/ApprovalFlowStep.php`
- `app/Models/ApprovalStep.php`
- `app/Models/ApprovalActivity.php`
- `app/Models/ApprovalDelegation.php`
- `app/Enums/ApprovalStatus.php`
- `app/Enums/ApprovalStepStatus.php`
- `app/Enums/ApprovalActivityAction.php`
- `app/Events/ApprovalStatusChanged.php`
- `app/Listeners/ApprovalListener.php`
- `app/Jobs/EscalateOverdueApprovals.php`
- `app/Filament/Pages/MyApprovals.php`
- `app/Filament/Resources/ApprovalFlows`
- `app/Filament/Resources/Approvals`
- `app/Filament/Resources/ApprovalDelegations`

Important database areas:

- `approval_flows`
- `approval_flow_steps`
- `approvals`
- `approval_steps`
- `approval_activities`
- `approval_delegations`

Test coverage is in:

- `tests/Feature/ApprovalFrameworkTest.php`
