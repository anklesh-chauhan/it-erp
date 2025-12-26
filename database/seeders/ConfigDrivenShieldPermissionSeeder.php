<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Filament\Facades\Filament;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ConfigDrivenShieldPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roleModel       = Utils::getRoleModel();
        $permissionModel = Utils::getPermissionModel();

        // 🔹 Discover ALL resources from ALL panels
        $resources = collect(Filament::getPanels())
            ->flatMap(fn ($panel) => $panel->getResources())
            ->unique()
            ->mapWithKeys(function ($resource) {
                $model = class_basename($resource::getModel());
                return [class_basename($resource) => $model];
            });

        foreach (config('permission-map.roles') as $roleName => $rules) {

            $role = $roleModel::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web',
            ]);

            $permissions = collect();

            foreach ($rules as $resourceClass => $level) {

                // Wildcard (admin / auditor)
                if ($resourceClass === '*') {
                    $permissions = $permissionModel::all();
                    break;
                }

                if (! $resources->has($resourceClass)) {
                    logger()->warning("Permission map resource not found: {$resourceClass}");
                    continue;
                }

                $model = $resources[$resourceClass];

                foreach ($this->permissionsForLevel($level, $model) as $perm) {
                    $permissions->push(
                        $permissionModel::firstOrCreate([
                            'name'       => $perm,
                            'guard_name' => 'web',
                        ])
                    );
                }
            }

            $role->syncPermissions($permissions->unique('name'));
        }

        $this->command->info('Shield permissions synced using Action:Model format.');
    }

    protected function permissionsForLevel(string $level, string $model): array
    {
        return match ($level) {
            'R' => [
                "ViewAny:{$model}",
                "View:{$model}",
            ],
            'C' => [
                "ViewAny:{$model}",
                "View:{$model}",
                "Create:{$model}",
                "Update:{$model}",
            ],
            'F' => [
                "ViewAny:{$model}",
                "View:{$model}",
                "Create:{$model}",
                "Update:{$model}",
                "Delete:{$model}",
                "Restore:{$model}",
                "RestoreAny:{$model}",
                "ForceDelete:{$model}",
                "ForceDeleteAny:{$model}",
                "Replicate:{$model}",
                "Reorder:{$model}",
            ],
            default => [],
        };
    }
}
