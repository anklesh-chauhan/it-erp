<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use Carbon\Carbon;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define a single organization record
        Organization::create([
            'name' => 'Tech Solutions Inc.',
            'display_name' => 'TSI',
            'website' => 'https://www.techsolutions.com',
            'founded_at' => Carbon::parse('2020-01-15'),
            'email' => 'contact@techsolutions.com',
            'phone' => '+1-555-123-4567',
            'contact_person' => 'Jane Doe',
            'contact_person_email' => 'jane.doe@techsolutions.com',
            'legal_name' => 'Tech Solutions Incorporated',
            'registration_number' => 'REG-TSI-2020',
            'gst_number' => '07ABCDE1234F1Z9',
            'registration_date' => Carbon::parse('2020-01-10'),
            'size' => 'Medium',
            'annual_revenue' => 5000000.00,
            'status' => 'active',
            'description' => 'A leading provider of innovative technology solutions.',
            'timezone' => 'UTC',
            'language' => 'en',
            'linkedin_url' => 'https://www.linkedin.com/company/techsolutions',
            'created_by' => 1, // Assuming a user with ID 1 exists
            'updated_by' => 1,
        ]);
    }
}