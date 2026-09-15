<?php

use App\Filament\Resources\Visits\Pages\EditVisit;
use App\Models\Visit;
use Database\Seeders\ConfigDrivenShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

it('maps visit create and update access onto sales roles', function () {
    expect(config('permission-map.roles.sales_user.VisitResource'))->toBe('C')
        ->and(config('permission-map.roles.sales_admin.VisitResource'))->toBe('F');
});

it('lets a sales user open and authorize visit edit after permissions are seeded', function () {
    $this->seed(ConfigDrivenShieldPermissionSeeder::class);

    $role = Role::findByName('sales_user', 'web');

    expect($role->hasPermissionTo('ViewAny:Visit'))->toBeTrue()
        ->and($role->hasPermissionTo('View:Visit'))->toBeTrue()
        ->and($role->hasPermissionTo('Create:Visit'))->toBeTrue()
        ->and($role->hasPermissionTo('Update:Visit'))->toBeTrue()
        ->and($role->hasPermissionTo('ViewOwnOU:Visit'))->toBeTrue();

    $user = reportingUser('SalesVisitEditor');
    $user->assignRole('sales_user');

    $territory = reportingTerritory('Sales Visit Terr');
    $patch = reportingPatch($territory, 'Sales Visit Patch');

    $this->actingAs($user);

    $visit = Visit::withoutEvents(fn (): Visit => Visit::query()->forceCreate([
        'document_number' => 'VIS-PERM-'.fake()->unique()->numerify('####'),
        'employee_id' => $user->id,
        'territory_id' => $territory->id,
        'patch_id' => $patch->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'draft',
        'approval_status' => 'draft',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]));

    expect($user->can('Update:Visit'))->toBeTrue()
        ->and($user->can('update', $visit))->toBeTrue()
        ->and($visit->created_by)->toBe($user->id);

    livewire(EditVisit::class, ['record' => $visit->getKey()])
        ->assertSuccessful();
});
