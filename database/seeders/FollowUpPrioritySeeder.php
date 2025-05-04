<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FollowUpPriority;

class FollowUpPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorityOptions = ['Low', 'Medium', 'High', 'Urgent'];

        foreach ($priorityOptions as $priority) {
            FollowUpPriority::create(['name' => $priority]);
        }
    }
}
