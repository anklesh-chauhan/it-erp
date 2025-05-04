<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FollowUpStatus;

class FollowUpStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusOptions = ['Open', 'In Progress', 'Closed', 'On Hold'];

        foreach ($statusOptions as $status) {
            FollowUpStatus::create(['name' => $status]);
        }
    }
}
