<?php

namespace Database\Factories;

use App\Models\LeaveApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveApplicationFactory extends Factory
{
    protected $model = LeaveApplication::class;

    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-2 months', 'now');
        $days = $this->faker->randomElement([0.5, 1, 2, 3, 4]);
        $to   = (clone $from)->modify('+' . max(0, ceil($days - 1)) . ' days');

        $isHalfDay = $days === 0.5;

        return [
            // 🔹 Dates
            'from_date'   => $from,
            'to_date'     => $to,
            'total_days'  => $days,

            // 🔹 Half-day support
            'is_half_day'   => $isHalfDay,
            'half_day_type' => $isHalfDay
                ? $this->faker->randomElement(['first_half', 'second_half'])
                : null,

            // 🔹 Workflow
            'approval_status'     => 'approved',
            'reason'     => $this->faker->sentence(),

            // 🔹 Payroll locks
            'payroll_locked'   => false,
            'payroll_lock_till'=> null,

            // 🔹 Audit
            'applied_at' => $from,
            'revoked_at' => null,
        ];
    }

    /**
     * State: Draft
     */
    public function draft(): static
    {
        return $this->state(fn () => [
            'approval_status' => 'draft',
            'applied_at' => null,
        ]);
    }

    /**
     * State: Applied (not yet approved)
     */
    public function applied(): static
    {
        return $this->state(fn () => [
            'approval_status' => 'applied',
            'applied_at' => now(),
        ]);
    }

    /**
     * State: Payroll locked
     */
    public function payrollLocked(): static
    {
        return $this->state(fn () => [
            'payroll_locked' => true,
            'payroll_lock_till' => now()->endOfMonth(),
        ]);
    }
}
