<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FollowUpMedia;

class FollowUpMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mediaOptions = ['Email', 'Phone Call', 'Meeting', 'WhatsApp', 'Social Media'];

        foreach ($mediaOptions as $media) {
            FollowUpMedia::create(['name' => $media]);
        }
    }
}
