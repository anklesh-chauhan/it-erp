<?php

namespace App\Filament\Resources\LeaveApplications\Pages;

use App\Filament\Resources\LeaveApplications\LeaveApplicationResource;
use Filament\Resources\Pages\CreateRecord;
use App\Orchestrators\LeaveApplicationOrchestrator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateLeaveApplication extends CreateRecord
{
    protected static string $resource = LeaveApplicationResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return app(LeaveApplicationOrchestrator::class)->apply([
                'employee_id' => Auth::id(),
                'leave_type_code' => \App\Models\LeaveType::find($data['leave_type_id'])->code,
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
                'days' => \Carbon\Carbon::parse($data['from_date'])
                    ->diffInDays($data['to_date']) + 1,
                'is_half_day' => $data['is_half_day'] ?? false,
                'half_day_type' => $data['half_day_type'] ?? null,
                'reason' => $data['reason'] ?? null,
                'substitute_user_id' => $data['substitute_user_id'] ?? null,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
