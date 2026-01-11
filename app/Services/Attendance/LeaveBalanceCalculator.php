<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use App\Models\{
    LeaveBalance,
    LeaveInstance,
    LeaveAdjustment,
    LeaveEncashment,
    PayrollLeaveSnapshot,
    LeaveLapseRecord
};

class LeaveBalanceCalculator
{
    public function calculate(
        int $employeeId,
        int $leaveTypeId,
        ?Carbon $asOnDate = null,
        bool $usePayrollSnapshot = false
    ): array {

        $asOnDate ??= now();

        if ($usePayrollSnapshot) {
            return $this->calculateFromPayrollSnapshot(
                $employeeId,
                $leaveTypeId,
                $asOnDate
            );
        }

        return $this->calculateRealtime(
            $employeeId,
            $leaveTypeId,
            $asOnDate
        );
    }

    protected function calculateRealtime(
        int $employeeId,
        int $leaveTypeId,
        Carbon $asOnDate
    ): array {

        $balance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $employeeId,
                'leave_type_id' => $leaveTypeId,
            ],
            [
                'opening_balance' => 0,
            ]
        );

        $applied = LeaveInstance::approved()
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->whereDate('date', '<=', $asOnDate)
            ->sum('pay_factor');

        $adjustedPositive = LeaveAdjustment::positive()
            ->forEmployee($employeeId, $leaveTypeId)
            ->after($balance->year_start_date)
            ->sum('days');

        $adjustedNegative = LeaveAdjustment::negative()
            ->forEmployee($employeeId, $leaveTypeId)
            ->after($balance->year_start_date)
            ->sum('days');

        $encashed = LeaveEncashment::forEmployee($employeeId, $leaveTypeId)
            ->after($balance->year_start_date)
            ->sum('days');

        $lapsed = LeaveLapseRecord::forEmployee($employeeId, $leaveTypeId)
            ->sum('days');

        $closing =
            $balance->opening_balance
            + $adjustedNegative
            - $applied
            + $lapsed
            - $adjustedPositive
            - $encashed;

        return compact(
            'balance',
            'applied',
            'adjustedPositive',
            'adjustedNegative',
            'encashed',
            'lapsed',
            'closing'
        );
    }


    protected function calculateFromPayrollSnapshot(
        int $employeeId,
        int $leaveTypeId,
        Carbon $asOnDate
    ): array {

        $snapshot = PayrollLeaveSnapshot::latestForEmployee(
            $employeeId,
            $leaveTypeId
        );

        if (! $snapshot) {
            return $this->calculateRealtime(
                $employeeId,
                $leaveTypeId,
                $asOnDate
            );
        }

        $cutoffDate = $snapshot->processed_till;

        $applied = LeaveInstance::approved()
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->whereDate('date', '>', $cutoffDate)
            ->sum('pay_factor');

        $adjustedPositive = LeaveAdjustment::positive()
            ->forEmployee($employeeId, $leaveTypeId)
            ->after($cutoffDate)
            ->sum('days');

        $adjustedNegative = LeaveAdjustment::negative()
            ->forEmployee($employeeId, $leaveTypeId)
            ->after($cutoffDate)
            ->sum('days');

        $encashed = LeaveEncashment::forEmployee($employeeId, $leaveTypeId)
            ->after($cutoffDate)
            ->sum('days');

        $lapsed = LeaveLapseRecord::forEmployee($employeeId, $leaveTypeId)
            ->after($cutoffDate)
            ->sum('days');

        $closing =
            $snapshot->closing_balance
            + $adjustedNegative
            - $applied
            + $lapsed
            - $adjustedPositive
            - $encashed;

        return compact(
            'snapshot',
            'applied',
            'adjustedPositive',
            'adjustedNegative',
            'encashed',
            'lapsed',
            'closing'
        );
    }
}
