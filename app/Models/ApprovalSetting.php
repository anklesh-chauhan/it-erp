<?php

namespace App\Models;

use App\Helpers\ModelHelper;

class ApprovalSetting extends BaseModel
{
    protected $table = 'approval_settings';

    protected $fillable = ['enabled_modules'];

    protected $casts = [
        'enabled_modules' => 'array',
    ];

    public static function instance(): ?self
    {
        return static::first();
    }

    public static function enabledModules(): array
    {
        return static::instance()?->enabled_modules ?? [];
    }

    public static function moduleRequiresApproval(string $module): bool
    {
        $moduleBasename = class_basename($module);

        return collect(static::enabledModules())
            ->contains(fn (string $enabledModule): bool => $enabledModule === $module || class_basename($enabledModule) === $moduleBasename);
    }

    public function getEnabledModulesAttribute($value): array
    {
        return is_array($value) ? $value : (json_decode($value ?? '[]', true) ?: []);
    }

    public static function approvedModuleOptions(): array
    {
        return collect(static::enabledModules())
            ->mapWithKeys(fn (string $class): array => [
                class_basename($class) => ModelHelper::labelFromClass($class),
            ])
            ->toArray();
    }
}
