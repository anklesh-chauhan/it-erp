<?php

namespace App\Services\Reports;

use App\Models\DailyAttendance;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRegisterQuery
{
    public function builder(): Builder
    {
        return ReportVisibility::constrainToVisibleEmployees(
            DailyAttendance::query()->with(['employee.user', 'shift', 'status'])
        );
    }
}
