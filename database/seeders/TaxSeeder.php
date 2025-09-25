<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxes = [
            ['name' => 'GST-18%', 'total_rate' => 18.00, 'is_active' => true],
            ['name' => 'GST-12%', 'total_rate' => 12.00, 'is_active' => true],
            ['name' => 'GST-5%',  'total_rate' => 5.00,  'is_active' => true],
            ['name' => 'IGST-18%', 'total_rate' => 18.00, 'is_active' => true],
            ['name' => 'VAT-10%', 'total_rate' => 10.00, 'is_active' => true],
        ];

        foreach ($taxes as $tax) {
            Tax::firstOrCreate(['name' => $tax['name']], $tax);
        }
    }
}