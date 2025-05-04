<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FollowUpResult;

class FollowUpResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resultOptions = ['Successful', 'Pending', 'Failed', 'Rescheduled'];

        foreach ($resultOptions as $result) {
            FollowUpResult::create(['name' => $result]);
        }
    }
}
