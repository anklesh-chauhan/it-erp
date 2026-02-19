<?php

namespace App\Models;

use App\Models\BaseModel;

class VisitPreference extends BaseModel
{
    protected $fillable = [

        'enable_check_in',
        'enable_check_out',
        'enforce_check_in_before_check_out',
        'allow_manual_time_edit',

        'require_check_in_image',
        'require_check_out_image',
        'require_general_visit_image',
        'require_gps',
        'geo_fence_radius_meters',

        'enforce_minimum_duration',
        'minimum_duration_minutes',

        'field_rules',
        'allow_rescheduling',
        'allow_cancellation',
        'require_visit_outcome',
    ];

    protected $casts = [
        'enable_check_in' => 'boolean',
        'enable_check_out' => 'boolean',
        'enforce_check_in_before_check_out' => 'boolean',
        'allow_manual_time_edit' => 'boolean',

        'require_check_in_image' => 'boolean',
        'require_check_out_image' => 'boolean',
        'require_general_visit_image' => 'boolean',
        'require_gps' => 'boolean',

        'enforce_minimum_duration' => 'boolean',

        'field_rules' => 'array',

        'allow_rescheduling' => 'boolean',
        'allow_cancellation' => 'boolean',
        'require_visit_outcome' => 'boolean',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }

    public function isFieldVisible(string $field): bool
    {
        return data_get($this->field_rules, "{$field}.visible", true);
    }

    public function isFieldRequired(string $field): bool
    {
        return data_get($this->field_rules, "{$field}.required", false);
    }

    public function isFieldEditable(string $field): bool
    {
        return data_get($this->field_rules, "{$field}.editable", true);
    }

    protected static function booted()
    {
        static::creating(function () {
            if (static::count() > 0) {
                throw new \RuntimeException(
                    'Only one VisitPreference row is allowed.'
                );
            }
        });
    }

}
