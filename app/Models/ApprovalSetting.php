<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class ApprovalSetting extends Model
{
    use HasApprovalWorkflow;

    protected $table = 'approval_settings';

    protected $fillable = ['enabled_modules'];

    protected $casts = [
        'enabled_modules' => 'array',
    ];

    public static function instance()
    {
        return static::first();
    }

    public static function enabledModules(): array
    {
        return static::instance()?->enabled_modules ?? [];
    }

    public static function moduleRequiresApproval(string $module): bool
    {
        return in_array($module, static::enabledModules());
    }

    public function getEnabledModulesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }

        return [];
    }

    public static function approvedModuleOptions(): array
    {
        $modules = static::enabledModules(); // always returns array

        return collect($modules)
            ->mapWithKeys(fn ($class) => [
                class_basename($class) => class_basename($class),
            ])
            ->toArray();
    }

}
