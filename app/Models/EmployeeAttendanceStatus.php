<?php

namespace App\Models;

use App\Traits\HasSafeGlobalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

class EmployeeAttendanceStatus extends Model
{
    use HasSafeGlobalSearch;

    protected $fillable = [
        'status_code',
        'status',
        'remarks',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class, 'status_id');
    }

    protected static function booted()
    {
        static::updating(function ($model) {

            // 🔒 BLOCK ALL UPDATES FOR SYSTEM-DEFINED
            if ($model->is_system) {
                throw ValidationException::withMessages([
                    'status' => 'System-defined statuses cannot be modified.',
                ]);
            }

            // 🔒 BLOCK CODE CHANGE IF USED (for non-system records)
            if (
                $model->isDirty('status_code') &&
                $model->attendances()->exists()
            ) {
                throw ValidationException::withMessages([
                    'status_code' => 'Status code cannot be changed because it is already used.',
                ]);
            }
        });

        static::deleting(function ($model) {

            // ❌ NEVER DELETE SYSTEM-DEFINED
            if ($model->is_system) {
                throw ValidationException::withMessages([
                    'status' => 'System-defined statuses cannot be deleted.',
                ]);
            }

            // ❌ CANNOT DELETE IF USED
            if ($model->attendances()->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'This status cannot be deleted because it is in use.',
                ]);
            }
        });
    }
}
