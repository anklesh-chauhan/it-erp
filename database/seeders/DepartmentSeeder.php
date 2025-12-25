<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Management',
            'Sales',
            'Marketing',
            'Business Development',
            'Operations',
            'Production',
            'Quality Control',
            'Purchase / Procurement',
            'Supply Chain',
            'Logistics',
            'Warehouse',
            'Finance',
            'Accounts',
            'Human Resources',
            'Administration',
            'Information Technology',
            'Engineering',
            'Research & Development',
            'Project Management',
            'Customer Support',
            'Service',
            'Maintenance',
            'Legal',
            'Compliance',
            'Training',
        ];

        foreach ($departments as $name) {
            Department::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
