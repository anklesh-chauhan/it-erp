<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Role;

class DepartmentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = DB::table('emp_departments')
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->pluck('department_name');

        $roles = [];

        foreach ($departments as $departmentName) {

            Role::firstOrCreate(
                [
                    'name' => Str::slug($departmentName), // sales, human-resources
                    'guard_name' => 'web',
                ]
            );
        }
    }
}
