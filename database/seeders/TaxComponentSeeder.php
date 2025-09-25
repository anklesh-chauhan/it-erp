<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;
use App\Models\TaxComponent;

class TaxComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $components = [
            'GST-18%' => [
                ['type' => 'CGST', 'rate' => 9.00],
                ['type' => 'SGST', 'rate' => 9.00],
            ],
            'GST-12%' => [
                ['type' => 'CGST', 'rate' => 6.00],
                ['type' => 'SGST', 'rate' => 6.00],
            ],
            'GST-5%' => [
                ['type' => 'CGST', 'rate' => 2.50],
                ['type' => 'SGST', 'rate' => 2.50],
            ],
            'IGST-18%' => [
                ['type' => 'IGST', 'rate' => 18.00],
            ],
            'VAT-10%' => [
                ['type' => 'VAT', 'rate' => 10.00],
            ],
        ];

        foreach ($components as $taxName => $componentData) {
            $tax = Tax::where('name', $taxName)->first();
            if ($tax) {
                foreach ($componentData as $data) {
                    TaxComponent::firstOrCreate([
                        'tax_id' => $tax->id,
                        'type' => $data['type'],
                    ], $data);
                }
            }
        }
    }
}