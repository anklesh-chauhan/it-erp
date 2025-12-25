<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            'Owner',
            'Managing Director',
            'Director',
            'CEO',
            'COO',
            'CFO',
            'General Manager',
            'Sales Manager',
            'Marketing Manager',
            'Purchase Manager',
            'Operations Manager',
            'HR Manager',
            'Accounts Manager',
            'Project Manager',
            'Team Lead',
            'Senior Executive',
            'Executive',
            'Officer',
            'Engineer',
            'Supervisor',
            'Technician',
            'Coordinator',
            'Administrator',
            'Assistant',
            'Clerk',
        ];

        foreach ($designations as $name) {
            Designation::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
