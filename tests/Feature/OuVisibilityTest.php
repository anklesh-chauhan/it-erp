<?php

use App\Models\User;
use App\Models\Patch;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\OrganizationalUnit;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate([
        'name' => 'ViewAny:Patch',
        'guard_name' => 'web',
    ]);

    Permission::firstOrCreate([
        'name' => 'ViewOwnOU:Patch',
        'guard_name' => 'web',
    ]);
});

test('user can only see patches from own OU', function () {

    /* ================= ORGANIZATIONAL UNITS ================= */
    $salesOu = OrganizationalUnit::create([
        'name' => 'Sales',
        'code' => 'SALES',
        'is_active' => true,
    ]);

    $opsOu = OrganizationalUnit::create([
        'name' => 'Operations',
        'code' => 'OPS',
        'is_active' => true,
    ]);

    /* ================= USERS ================= */
    $salesUser = User::factory()->create();
    $opsUser   = User::factory()->create();

    $salesUser->givePermissionTo('ViewAny:Patch', 'ViewOwnOU:Patch');
    $opsUser->givePermissionTo('ViewAny:Patch', 'ViewOwnOU:Patch');

    /* ================= EMPLOYEES ================= */
    attachUserToOu($salesUser, $salesOu);
    attachUserToOu($opsUser, $opsOu);

    /* ================= PATCHES ================= */
    Patch::create([
        'name' => 'Sales Patch',
        'code' => 'SP-1',
        'created_by' => $salesUser->id,
    ]);

    Patch::create([
        'name' => 'Ops Patch',
        'code' => 'OP-1',
        'created_by' => $opsUser->id,
    ]);

    /* ================= ASSERT VISIBILITY ================= */

    actingAs($salesUser);

    $salesVisible = Patch::query()
        ->applyVisibility('Patch')
        ->pluck('name')
        ->toArray();

    expect($salesVisible)
        ->toContain('Sales Patch')
        ->not->toContain('Ops Patch');

    actingAs($opsUser);

    $opsVisible = Patch::query()
        ->applyVisibility('Patch')
        ->pluck('name')
        ->toArray();

    expect($opsVisible)
        ->toContain('Ops Patch')
        ->not->toContain('Sales Patch');
});

/* =========================================================
 | HELPERS
 ========================================================= */

function attachUserToOu(User $user, OrganizationalUnit $ou): void
{
    $employee = Employee::create([
        'login_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'User',
    ]);

    $employment = EmploymentDetail::create([
        'employee_id' => $employee->id,
    ]);

    $employment->organizationalUnits()->attach($ou->id, [
        'is_primary' => true,
    ]);
}
