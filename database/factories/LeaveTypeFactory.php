<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EmployeeAttendanceStatus;

class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??')),
            'name' => ucfirst($this->faker->word) . ' Leave',
            'is_paid' => true,
            'affects_payroll' => true,
            'is_active' => true,
            'employee_attendance_status_id' =>
            EmployeeAttendanceStatus::inRandomOrder()->value('id'),
        ];
    }

    /**
     * State: Casual Leave
     */
    public function casual(): static
    {
        return $this->state([
            'code' => 'CL',
            'name' => 'Casual Leave',
            'is_paid' => true,
        ]);
    }

    /**
     * State: Sick Leave
     */
    public function sick(): static
    {
        return $this->state([
            'code' => 'SL',
            'name' => 'Sick Leave',
            'is_paid' => true,
        ]);
    }

    /**
     * State: Privilege Leave
     */
    public function privilege(): static
    {
        return $this->state([
            'code' => 'PL',
            'name' => 'Privilege Leave',
            'is_paid' => true,
        ]);
    }

    /**
     * State: Leave Without Pay
     */
    public function lwp(): static
    {
        return $this->state([
            'code' => 'LWP',
            'name' => 'Leave Without Pay',
            'is_paid' => false,
        ]);
    }

    /**
     * State: Comp Off
     */
    public function compOff(): static
    {
        return $this->state([
            'code' => 'CO',
            'name' => 'Compensatory Off',
            'is_paid' => true,
        ]);
    }

    /**
     * State: On Duty
     */
    public function onDuty(): static
    {
        return $this->state([
            'code' => 'OD',
            'name' => 'On Duty',
            'is_paid' => true,
        ]);
    }
}
