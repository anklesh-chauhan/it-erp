<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PunchRequest;
use App\Http\Resources\Api\V1\DailyAttendanceResource;
use App\Services\Field\FieldAttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private FieldAttendanceService $attendanceService,
    ) {}

    public function today(Request $request): JsonResponse
    {
        $attendance = $this->attendanceService->today($request->user());

        return response()->json([
            'data' => $attendance ? new DailyAttendanceResource($attendance) : null,
        ]);
    }

    public function punchIn(PunchRequest $request): DailyAttendanceResource
    {
        $attendance = $this->attendanceService->punchIn(
            $request->user(),
            $request->validated(),
        );

        return new DailyAttendanceResource($attendance);
    }

    public function punchOut(PunchRequest $request): DailyAttendanceResource
    {
        $attendance = $this->attendanceService->punchOut(
            $request->user(),
            $request->validated(),
        );

        return new DailyAttendanceResource($attendance);
    }
}
