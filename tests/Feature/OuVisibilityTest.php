<?php

use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\OrganizationalUnit;
use App\Models\Patch;
use App\Models\Territory;
use App\Models\TypeMaster;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

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
    $typeMaster = TypeMaster::query()->create([
        'name' => 'Organisation Unit',
        'description' => 'Test OU type',
    ]);

    $salesOu = OrganizationalUnit::query()->create([
        'name' => 'Sales',
        'code' => 'SALES',
        'type_master_id' => $typeMaster->id,
        'is_active' => true,
    ]);

    $opsOu = OrganizationalUnit::query()->create([
        'name' => 'Operations',
        'code' => 'OPS',
        'type_master_id' => $typeMaster->id,
        'is_active' => true,
    ]);

    $salesUser = User::factory()->create();
    $opsUser = User::factory()->create();

    $salesUser->givePermissionTo('ViewAny:Patch', 'ViewOwnOU:Patch');
    $opsUser->givePermissionTo('ViewAny:Patch', 'ViewOwnOU:Patch');

    attachUserToOu($salesUser, $salesOu);
    attachUserToOu($opsUser, $opsOu);

    $salesUser = $salesUser->fresh();
    $opsUser = $opsUser->fresh();

    $salesTerritory = Territory::query()->create([
        'name' => 'Sales Territory',
        'code' => 'TERR-SALES',
        'division_ou_id' => $salesOu->id,
        'type_master_id' => $typeMaster->id,
        'status' => 'active',
    ]);

    $opsTerritory = Territory::query()->create([
        'name' => 'Ops Territory',
        'code' => 'TERR-OPS',
        'division_ou_id' => $opsOu->id,
        'type_master_id' => $typeMaster->id,
        'status' => 'active',
    ]);

    Patch::query()->forceCreate([
        'name' => 'Sales Patch',
        'code' => 'SP-1',
        'territory_id' => $salesTerritory->id,
        'created_by' => $salesUser->id,
        'updated_by' => $salesUser->id,
    ]);

    Patch::query()->forceCreate([
        'name' => 'Ops Patch',
        'code' => 'OP-1',
        'territory_id' => $opsTerritory->id,
        'created_by' => $opsUser->id,
        'updated_by' => $opsUser->id,
    ]);

    $this->actingAs($salesUser);

    $salesVisible = Patch::query()
        ->applyVisibility('Patch')
        ->pluck('name')
        ->all();

    expect($salesVisible)
        ->toContain('Sales Patch')
        ->not->toContain('Ops Patch');

    $this->actingAs($opsUser);

    $opsVisible = Patch::query()
        ->applyVisibility('Patch')
        ->pluck('name')
        ->all();

    expect($opsVisible)
        ->toContain('Ops Patch')
        ->not->toContain('Sales Patch');
});

function attachUserToOu(User $user, OrganizationalUnit $ou): void
{
    $employee = Employee::query()->create([
        'employee_id' => 'EMP-'.$user->id,
        'login_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'User',
        'mobile_number' => '999999999'.str_pad((string) $user->id, 2, '0', STR_PAD_LEFT),
        'email' => $user->email,
        'gender' => 'Other',
        'marital_status' => 'Single',
    ]);

    $employment = EmploymentDetail::query()->create([
        'employee_id' => $employee->id,
    ]);

    $employment->organizationalUnits()->attach($ou->id, [
        'is_primary' => true,
    ]);
}
