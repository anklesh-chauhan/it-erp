<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyAttendance;
use Carbon\Carbon;

class RedirectMarketingToPunchIn
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Skip if not logged in or not marketing role
        if (! $user || ! $user->hasRole('marketing')) {
            return $next($request);
        }

        // Check if user has already punched in today
        $hasPunchedInToday = DailyAttendance::where('employee_id', $user->employee?->id)
            ->whereDate('attendance_date', today())
            ->exists();

        // If already punched in → allow normal access
        if ($hasPunchedInToday) {
            return $next($request);
        }

        // If on the Punch In page already → allow
        if ($request->routeIs('filament.admin.pages.punch-in')) {
            return $next($request);
        }

        // Redirect to Punch In page
        return redirect()->route('filament.admin.pages.punch-in');
    }
}
