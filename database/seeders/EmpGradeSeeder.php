<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmpGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [
            [
                'grade_name' => 'Sales Representative',
                'description' => 'Entry-level sales role focused on client acquisition',
                // 'department_id' => 1, // Sales department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Sales Manager',
                'description' => 'Manages sales team and client relationships',
                // 'department_id' => 1, // Sales department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Marketing Coordinator',
                'description' => 'Supports marketing campaigns and content creation',
                // 'department_id' => 2, // Marketing department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Marketing Manager',
                'description' => 'Oversees marketing strategies and team performance',
                // 'department_id' => 2, // Marketing department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'HR Specialist',
                'description' => 'Handles recruitment and employee onboarding',
                // 'department_id' => 3, // Human Resources department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'HR Director',
                'description' => 'Leads HR strategy and compliance',
                // 'department_id' => 3, // Human Resources department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'IT Support Technician',
                'description' => 'Provides technical support and system maintenance',
                // 'department_id' => 4, // Information Technology department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Senior Developer',
                'description' => 'Leads software development projects',
                // 'department_id' => 4, // Information Technology department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Accountant',
                'description' => 'Manages financial records and reporting',
                // 'department_id' => 5, // Finance department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Financial Analyst',
                'description' => 'Analyzes financial data and forecasts',
                // 'department_id' => 5, // Finance department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Operations Coordinator',
                'description' => 'Supports operational workflows and logistics',
                // 'department_id' => 6, // Operations department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Operations Manager',
                'description' => 'Oversees operational efficiency and processes',
                // 'department_id' => 6, // Operations department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Customer Support Representative',
                'description' => 'Handles customer inquiries and issues',
                // 'department_id' => 7, // Customer Support department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Customer Success Manager',
                'description' => 'Ensures customer satisfaction and retention',
                // 'department_id' => 7, // Customer Support department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'R&D Engineer',
                'description' => 'Conducts research and develops new products',
                // 'department_id' => 8, // Research and Development department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'R&D Manager',
                'description' => 'Leads research and development initiatives',
                // 'department_id' => 8, // Research and Development department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Legal Assistant',
                'description' => 'Supports legal documentation and compliance',
                // 'department_id' => 9, // Legal department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'General Counsel',
                'description' => 'Oversees legal strategy and compliance',
                // 'department_id' => 9, // Legal department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Administrative Assistant',
                'description' => 'Handles office administration and support tasks',
                // 'department_id' => 10, // Administration department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Office Manager',
                'description' => 'Manages office operations and staff',
                // 'department_id' => 10, // Administration department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert grades into the emp_grades table
        DB::table('emp_grades')->insert($grades);
    }
}
