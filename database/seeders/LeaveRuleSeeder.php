<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [

            // 📧 Email & SMS Settings
            [
                'rule_key' => 'EMAIL_MANAGER_ON_APPLY',
                'category' => 'notification',
                'name' => 'Send Email to Manager on Leave Application',
                'condition' => ['event' => 'LEAVE_APPLIED'],
                'action' => ['send_email' => true, 'recipient' => 'manager'],
            ],
            [
                'rule_key' => 'EMAIL_MANAGER_APPROVE_REJECT',
                'category' => 'notification',
                'name' => 'Include Approve / Reject in Email',
                'condition' => ['event' => 'LEAVE_APPLIED'],
                'action' => ['email_action_links' => true],
            ],
            [
                'rule_key' => 'EMAIL_PARALLEL_STAGE_MANAGERS',
                'category' => 'notification',
                'name' => 'Send Email to Managers of Same Stage',
                'condition' => ['event' => 'LEAVE_STAGE_ACTION'],
                'action' => ['notify_same_stage' => true],
            ],

            // 🤒 Sick Leave Rules
            [
                'rule_key' => 'SL_AFTER_PL_DENIED',
                'category' => 'validation',
                'name' => 'No SL after PL',
                'condition' => ['leave_type' => 'SL', 'previous_day_leave' => 'PL'],
                'action' => ['deny' => true, 'message' => 'Sick Leave not allowed after Privilege Leave'],
            ],
            [
                'rule_key' => 'SL_NO_FUTURE',
                'category' => 'validation',
                'name' => 'No SL for Future Dates',
                'condition' => ['leave_type' => 'SL', 'from_date' => 'future'],
                'action' => ['deny' => true],
            ],
            [
                'rule_key' => 'SL_HALF_PAY',
                'category' => 'computation',
                'name' => 'SL is Half Pay',
                'condition' => ['leave_type' => 'SL'],
                'action' => ['pay_factor' => 0.5],
            ],
            [
                'rule_key' => 'SL_ATTACHMENT_REQUIRED',
                'category' => 'validation',
                'name' => 'SL Attachment Mandatory',
                'condition' => ['leave_type' => 'SL', 'days_greater_than' => 3],
                'action' => ['attachment_required' => true],
            ],

            // 🔁 Comp-Off
            [
                'rule_key' => 'CO_ALLOWED_DAY_TYPES',
                'category' => 'validation',
                'name' => 'CO Allowed Day Types',
                'condition' => ['leave_type' => 'CO+'],
                'action' => [
                    'allowed_day_types' => ['DP','PHP','PH','WO','WOP','OD','WO_PH']
                ],
            ],
            [
                'rule_key' => 'CO_EXPIRY_WINDOW',
                'category' => 'validation',
                'name' => 'CO Expiry Window',
                'condition' => ['leave_type' => 'CO-'],
                'action' => ['valid_days' => 60],
            ],
            [
                'rule_key' => 'CO_HALF_DAY_PRESENT',
                'category' => 'computation',
                'name' => 'Half Day CO+ if Half Day Present',
                'condition' => ['leave_type' => 'CO+', 'attendance' => 'HALF_DAY'],
                'action' => ['credit_factor' => 0.5],
            ],

            // 💰 Leave Encashment
            [
                'rule_key' => 'LEAVE_ENCASHMENT_ALLOWED',
                'category' => 'validation',
                'name' => 'Leave Encashment Allowed',
                'condition' => [
                    'leave_type_in' => ['PL','CL','SL','OL','CO','L1','L2','L3','L4','L5']
                ],
                'action' => ['encashable' => true],
            ],

            // ⏱ Half Day Restrictions
            [
                'rule_key' => 'NO_HALF_DAY_FOR_TYPES',
                'category' => 'validation',
                'name' => 'No Half Day for Certain Leaves',
                'condition' => [
                    'half_day' => true,
                    'leave_type_in' => ['PL','CL','SL','OL','CO','LWP','L1','L2','L3','L4','L5']
                ],
                'action' => ['deny' => true],
            ],

            // 👥 Substitute Approval
            [
                'rule_key' => 'SUBSTITUTE_APPROVAL_REQUIRED',
                'category' => 'workflow',
                'name' => 'Substitute Approval Required',
                'condition' => ['leave_type' => '*', 'days_greater_than' => 0],
                'action' => ['require_substitute' => true],
            ],
            [
                'rule_key' => 'SUBSTITUTE_NO_PAST',
                'category' => 'workflow',
                'name' => 'Restrict Past Days for Substitute',
                'condition' => ['substitute' => true, 'past_days' => true],
                'action' => ['deny' => true],
            ],

            // 📅 Month Restriction
            [
                'rule_key' => 'MONTH_RESTRICTION',
                'category' => 'restriction',
                'name' => 'Month Restriction',
                'condition' => ['month_in' => ['Jan','Feb','Mar']],
                'action' => ['deny' => true],
            ],

            // 📊 Leave Lapse
            [
                'rule_key' => 'LEAVE_LAPSE',
                'category' => 'computation',
                'name' => 'Leave Lapse',
                'condition' => ['past_days' => 0],
                'action' => ['lapse' => true],
            ],

            // 🚫 Notice Period
            [
                'rule_key' => 'NOTICE_PERIOD_ALLOWED_LEAVES',
                'category' => 'validation',
                'name' => 'Notice Period Leave Restriction',
                'condition' => ['notice_period' => true],
                'action' => [
                    'allowed_leave_types' => ['PL','CL','SL','OL','CO','L1','L2','L3','L4','L5','LWP','OD','CO+']
                ],
            ],

            // ⏰ Late Mark
            [
                'rule_key' => 'LATEMARK_CONVERSION',
                'category' => 'computation',
                'name' => 'LateMark to Leave Conversion',
                'condition' => ['late_marks' => true],
                'action' => [
                    'convert_to' => ['CL','PL','CO'],
                    'allow_unconfirmed' => true
                ],
            ],

            // 🧮 Payroll
            [
                'rule_key' => 'PAYROLL_CUTOFF',
                'category' => 'payroll',
                'name' => 'Payroll Cutoff Logic',
                'condition' => ['processed_month' => true],
                'action' => ['use_payroll_snapshot' => true],
            ],

            // ❌ Leave Cancellation
            [
                'rule_key' => 'LEAVE_CANCEL_NO_APPROVAL',
                'category' => 'workflow',
                'name' => 'Auto Approve Leave Cancellation',
                'condition' => ['future_leave' => true],
                'action' => ['auto_approve_cancel' => true],
            ],

            // 👁 Visibility
            [
                'rule_key' => 'HIDE_BALANCE_FROM_MANAGER',
                'category' => 'visibility',
                'name' => 'Hide Leave Balance from Manager',
                'condition' => ['role' => 'manager'],
                'action' => ['hide_leave_balance' => true],
            ],
        ];

        foreach ($rules as $rule) {
            $categoryId = DB::table('leave_rule_categories')
                ->where('key', $rule['category'])
                ->value('id');

            DB::table('leave_rules')->updateOrInsert(
                ['rule_key' => $rule['rule_key']],
                [
                    'leave_rule_category_id' => $categoryId,
                    'leave_type_id' => null,
                    'employee_attendance_status_id' => null,
                    'name' => $rule['name'],
                    'description' => $rule['name'],
                    'condition_json' => json_encode($rule['condition'], JSON_PRETTY_PRINT),
                    'action_json' => json_encode($rule['action'], JSON_PRETTY_PRINT),
                    'priority' => 100,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
