<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'Cash',
                'description' => 'Cash payment at the time of purchase.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Credit Card',
                'description' => 'Payment using credit card (Visa, MasterCard, etc).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Debit Card',
                'description' => 'Payment using debit card linked to a bank account.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Transfer',
                'description' => 'Direct transfer from bank account.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cheque',
                'description' => 'Payment using a company or personal cheque.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UPI',
                'description' => 'Unified Payments Interface (e.g. Google Pay, PhonePe, Paytm).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PayPal',
                'description' => 'Online payment via PayPal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
