<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_terms')->insert([
            [
                'name' => 'Advance 100%',
                'code' => 'ADV100',
                'due_in_days' => 0,
                'description' => 'Full payment in advance before delivery.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Net 7',
                'code' => 'NET7',
                'due_in_days' => 7,
                'description' => 'Payment due within 7 days after invoice date.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Net 15',
                'code' => 'NET15',
                'due_in_days' => 15,
                'description' => 'Payment due within 15 days after invoice date.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Net 30',
                'code' => 'NET30',
                'due_in_days' => 30,
                'description' => 'Payment due within 30 days after invoice date.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Net 60',
                'code' => 'NET60',
                'due_in_days' => 60,
                'description' => 'Payment due within 60 days after invoice date.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Net 90',
                'code' => 'NET90',
                'due_in_days' => 90,
                'description' => 'Payment due within 90 days after invoice date.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
