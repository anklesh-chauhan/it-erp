<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('deal_stages')->insert([
            ['name' => 'Qualification'],
            ['name' => 'Needs Analysis'],
            ['name' => 'Value Proposition'],
            ['name' => 'Identify Decision Makers'],
            ['name' => 'Proposal/Price Quote'],
            ['name' => 'Negotiation/Review'],
            ['name' => 'Closed Won'],
            ['name' => 'Closed Lost'],
            ['name' => 'Closed Lost to Competition'],
        ]);
    }
}
