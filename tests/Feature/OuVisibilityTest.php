<?php

use App\Models\User;
use App\Models\Patch;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\OrganizationalUnit;
use App\Models\TypeMaster;
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

    $typeMaster = TypeMaster::create([
        'name' => 'Organisation Unit',
        'description' => 'Test OU type',
    ]);

    /* ================= ORGANIZATIONAL UNITS ================= */
    $salesOu = OrganizationalUnit::create([
        'name' => 'Sales',
        'code' => 'SALES',
        'type_master_id' => $typeMaster->id,
        'is_active' => true,
    ]);

    $opsOu = OrganizationalUnit::create([
        'name' => 'Operations',
        'code' => 'OPS',
        'type_master_id' => $typeMaster->id,
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

    $salesTerritory = \App\Models\Territory::create([
        'name' => 'Sales Territory',
        'code' => 'TERR-SALES',
        'division_ou_id' => $salesOu->id,
        'type_master_id' => $typeMaster->id,
        'status' => 'active',
    ]);

    $opsTerritory = \App\Models\Territory::create([
        'name' => 'Ops Territory',
        'code' => 'TERR-OPS',
        'division_ou_id' => $opsOu->id,
        'type_master_id' => $typeMaster->id,
        'status' => 'active',
    ]);

    /* ================= PATCHES ================= */
    Patch::create([
        'name' => 'Sales Patch',
        'code' => 'SP-1',
        'territory_id' => $salesTerritory->id,
        'created_by' => $salesUser->id,
    ]);

    Patch::create([
        'name' => 'Ops Patch',
        'code' => 'OP-1',
        'territory_id' => $opsTerritory->id,
        'created_by' => $opsUser->id,
    ]);

    /* ================= ASSERT VISIBILITY ================= */

    $this->actingAs($salesUser);

    $salesVisible = Patch::query()
        ->applyVisibility('Patch')
        ->pluck('name')
        ->toArray();

    dump([
        'sales_user_id' => $salesUser->id,
        'sales_employee_exists' => (bool) $salesUser->employee,
        'sales_employee_ou_ids' => $salesUser->employee?->employmentDetail?->organizationalUnits?->pluck('organizational_units.id')->toArray(),
        'sales_visible' => $salesVisible,
        'sales_sql' => Patch::query()->applyVisibility('Patch')->toSql(),
    ]);

    expect($salesVisible)
        ->toContain('Sales Patch')
        ->not->toContain('Ops Patch');

    $this->actingAs($opsUser);

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
        'employee_id' => 'EMP-' . $user->id,
        'login_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'User',
        'mobile_number' => '999999999' . str_pad((string) $user->id, 2, '0', STR_PAD_LEFT),
        'email' => $user->email,
        'gender' => 'Other',
        'marital_status' => 'Single',
    ]);

    $employment = EmploymentDetail::create([
        'employee_id' => $employee->id,
    ]);

    $employment->organizationalUnits()->attach($ou->id, [
        'is_primary' => true,
    ]);
}
