<?php

namespace App\Services\Reports;

use App\Models\DailyAttendance;
use App\Models\PunchVsTourRow;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PunchVsTourQuery
{
    public function builder(): Builder
    {
        $punchesWithoutVisit = DailyAttendance::query()
            ->join('employees', 'employees.id', '=', 'daily_attendances.employee_id')
            ->whereNotNull('daily_attendances.first_punch_in')
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('visits')
                    ->whereColumn('visits.employee_id', 'employees.login_id')
                    ->whereColumn('visits.visit_date', 'daily_attendances.attendance_date')
                    ->where('visits.visit_status', '!=', 'cancelled')
                    ->whereNull('visits.deleted_at');
            })
            ->select([
                DB::raw("CONCAT('punch-', daily_attendances.id) as id"),
                DB::raw("'punch_without_visit' as mismatch"),
                'daily_attendances.attendance_date as activity_date',
                'employees.login_id as user_id',
                'daily_attendances.employee_id as employee_id',
                'daily_attendances.id as source_id',
                'daily_attendances.first_punch_in',
                'daily_attendances.last_punch_out',
                DB::raw('NULL as visit_status'),
                DB::raw('NULL as territory_id'),
                DB::raw('NULL as document_number'),
            ]);

        $visitsWithoutPunch = Visit::query()
            ->join('employees', 'employees.login_id', '=', 'visits.employee_id')
            ->where('visits.visit_status', '!=', 'cancelled')
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('daily_attendances')
                    ->whereColumn('daily_attendances.employee_id', 'employees.id')
                    ->whereColumn('daily_attendances.attendance_date', 'visits.visit_date')
                    ->whereNotNull('daily_attendances.first_punch_in')
                    ->whereNull('daily_attendances.deleted_at');
            })
            ->select([
                DB::raw("CONCAT('visit-', visits.id) as id"),
                DB::raw("'visit_without_punch' as mismatch"),
                'visits.visit_date as activity_date',
                'visits.employee_id as user_id',
                'employees.id as employee_id',
                'visits.id as source_id',
                DB::raw('NULL as first_punch_in'),
                DB::raw('NULL as last_punch_out'),
                'visits.visit_status',
                'visits.territory_id',
                'visits.document_number',
            ]);

        return ReportVisibility::constrainToVisibleUsers(
            PunchVsTourRow::query()
                ->fromSub($punchesWithoutVisit->unionAll($visitsWithoutPunch), 'punch_vs_tour_rows')
                ->with(['user', 'employee', 'territory'])
        );
    }
}
