<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VisitType;

class VisitTypeSeeder extends Seeder
{
    public function run()
    {
        VisitType::insert([
            ['name' => 'Initial Meeting'],
            ['name' => 'Follow-up'],
            ['name' => 'Technical Discussion'],
            ['name' => 'Final Negotiation'],
        ]);
    }
}
