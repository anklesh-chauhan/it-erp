<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesDocumentPreference;

class SalesDocumentPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesDocumentPreference::firstOrCreate(
            [], // Search for any existing record (since there should only be one)
            [
                'attach_pdf_in_email' => true,
                'encrypt_pdf' => false,
                'discount_level' => 'line_item',
                'include_adjustments' => false,
                'include_shipping_charges' => false,
                'tax_mode' => 'exclusive',
                'rounding_option' => 'none',
                'enable_salesperson' => false,
                'enable_billable_expenses' => false,
                'default_markup_percentage' => null,
                'document_copy_type' => 'original_duplicate',
                'default_print_preferences' => json_encode(['font_size' => '12', 'show_logo' => true]),
            ]
        );
    }
}