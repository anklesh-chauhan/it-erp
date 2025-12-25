<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmpJobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobTitles = [
            [
                'title' => 'Sales Executive',
                'description' => 'Drives sales through client outreach and deal negotiations',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Marketing Specialist',
                'description' => 'Develops and executes marketing campaigns',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'HR Manager',
                'description' => 'Oversees recruitment, training, and employee relations',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Software Engineer',
                'description' => 'Designs and develops software applications',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Accountant',
                'description' => 'Manages financial records and ensures compliance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Operations Supervisor',
                'description' => 'Coordinates operational activities and logistics',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Customer Support Agent',
                'description' => 'Assists customers with inquiries and issues',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'R&D Scientist',
                'description' => 'Conducts research to innovate new products',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Legal Counsel',
                'description' => 'Provides legal advice and manages contracts',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Administrative Coordinator',
                'description' => 'Supports office operations and administrative tasks',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert job titles into the emp_job_titles table
        DB::table('emp_job_titles')->insert($jobTitles);
    }
}
