<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationalUnit;
use App\Models\TypeMaster;

class OrganizationalUnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Create Organizational Unit Root Type
            |--------------------------------------------------------------------------
            */
            $ouRootType = TypeMaster::firstOrCreate(
                [
                    'typeable_type' => OrganizationalUnit::class,
                    'parent_id'     => null,
                ],
                [
                    'name'        => 'Organizational Unit',
                    'description' => 'Root type for organizational structure',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Create OU Sub Types
            |--------------------------------------------------------------------------
            */
            $hoType = TypeMaster::firstOrCreate(
                [
                    'name'            => 'Head Office',
                    'parent_id'       => $ouRootType->id,
                    'typeable_type'   => OrganizationalUnit::class,
                ],
                ['description' => 'Corporate Head Office']
            );

            $divisionType = TypeMaster::firstOrCreate(
                [
                    'name'            => 'Division',
                    'parent_id'       => $ouRootType->id,
                    'typeable_type'   => OrganizationalUnit::class,
                ],
                ['description' => 'Business Division']
            );

            $plantType = TypeMaster::firstOrCreate(
                [
                    'name'            => 'Plant',
                    'parent_id'       => $ouRootType->id,
                    'typeable_type'   => OrganizationalUnit::class,
                ],
                ['description' => 'Manufacturing Plant']
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Seed Organizational Units
            |--------------------------------------------------------------------------
            */

            // ===============================
            // Head Office
            // ===============================
            $ho = OrganizationalUnit::create([
                'name'           => 'Head Office',
                'code'           => 'HO',
                'type_master_id' => $hoType->id,
                'description'    => 'Corporate Head Office',
                'parent_id'      => null,
                'is_active'      => true,
            ]);

            // ===============================
            // Sales Division
            // ===============================
            $salesDivision = OrganizationalUnit::create([
                'name'           => 'Sales Division',
                'code'           => 'SALES',
                'type_master_id' => $divisionType->id,
                'description'    => 'Sales Operations',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            OrganizationalUnit::insert([
                [
                    'name'           => 'Sales – North',
                    'code'           => 'SALES-N',
                    'type_master_id' => $divisionType->id,
                    'description'    => 'North Region Sales',
                    'parent_id'      => $salesDivision->id,
                    'is_active'      => true,
                ],
                [
                    'name'           => 'Sales – South',
                    'code'           => 'SALES-S',
                    'type_master_id' => $divisionType->id,
                    'description'    => 'South Region Sales',
                    'parent_id'      => $salesDivision->id,
                    'is_active'      => true,
                ],
            ]);

            // ===============================
            // Manufacturing Division
            // ===============================
            $manufacturingDivision = OrganizationalUnit::create([
                'name'           => 'Manufacturing Division',
                'code'           => 'MFG',
                'type_master_id' => $divisionType->id,
                'description'    => 'Manufacturing Operations',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            OrganizationalUnit::insert([
                [
                    'name'           => 'Plant A',
                    'code'           => 'PLANT-A',
                    'type_master_id' => $plantType->id,
                    'description'    => 'Manufacturing Plant A',
                    'parent_id'      => $manufacturingDivision->id,
                    'is_active'      => true,
                ],
                [
                    'name'           => 'Plant B',
                    'code'           => 'PLANT-B',
                    'type_master_id' => $plantType->id,
                    'description'    => 'Manufacturing Plant B',
                    'parent_id'      => $manufacturingDivision->id,
                    'is_active'      => true,
                ],
            ]);

            // ===============================
            // Service Division
            // ===============================
            OrganizationalUnit::create([
                'name'           => 'Service Division',
                'code'           => 'SERVICE',
                'type_master_id' => $divisionType->id,
                'description'    => 'After Sales & Support',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);
        });
    }
}
