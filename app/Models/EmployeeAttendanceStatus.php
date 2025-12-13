<?php

namespace App\Models;

use App\Traits\HasSafeGlobalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
