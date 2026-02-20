<?php

namespace App\Console\Commands;

use App\Models\Visit;
use App\Models\VisitPreference;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCheckoutVisitsCommand extends Command
{
    protected $signature = 'visits:auto-checkout';

    protected $description = 'Automatically check out visits that are still started at the configured auto-checkout time.';

    public function handle(): int
    {
        $prefs = VisitPreference::current();

        if (! $prefs->enable_auto_checkout || blank($prefs->auto_checkout_time)) {
            return self::SUCCESS;
        }

        if ($prefs->require_check_out_image) {
            return self::SUCCESS;
        }

        $checkoutTime = $this->parseCheckoutTime($prefs->auto_checkout_time);
        if ($checkoutTime === null) {
            return self::SUCCESS;
        }

        $now = now();
        if ($now->lt($checkoutTime)) {
            return self::SUCCESS;
        }

        $visits = Visit::query()
            ->where('visit_status', 'started')
            ->whereDate('start_time', $now->toDateString())
            ->get();

        $count = 0;
        foreach ($visits as $visit) {
            $visit->update([
                'end_time' => $checkoutTime,
                'visit_status' => 'completed',
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->info("Auto-checked out {$count} visit(s).");
        }

        return self::SUCCESS;
    }

    /**
     * Parse preference time (HH:MM:SS or HH:MM) into today's datetime in app timezone.
     */
    protected function parseCheckoutTime(string|Carbon|null $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return Carbon::today()->setTimeFromTimeString($value->format('H:i:s'));
        }
        if (is_string($value) && preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $value)) {
            return Carbon::today()->setTimeFromTimeString(
                strlen($value) === 5 ? $value.':00' : $value
            );
        }

        return null;
    }
}
