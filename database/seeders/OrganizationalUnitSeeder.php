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

            /* ================= ROOT TYPE ================= */
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

            /* ================= SUB TYPES ================= */
            $hoType = TypeMaster::firstOrCreate(
                ['name' => 'Head Office', 'parent_id' => $ouRootType->id, 'typeable_type' => OrganizationalUnit::class],
                ['description' => 'Corporate Head Office']
            );

            $divisionType = TypeMaster::firstOrCreate(
                ['name' => 'Division', 'parent_id' => $ouRootType->id, 'typeable_type' => OrganizationalUnit::class],
                ['description' => 'Business Division']
            );

            $plantType = TypeMaster::firstOrCreate(
                ['name' => 'Plant', 'parent_id' => $ouRootType->id, 'typeable_type' => OrganizationalUnit::class],
                ['description' => 'Manufacturing Plant']
            );

            /* ================= HEAD OFFICE ================= */
            $ho = OrganizationalUnit::firstOrCreate([
                'code' => 'HO',
            ], [
                'name'           => 'Head Office',
                'type_master_id' => $hoType->id,
                'description'    => 'Corporate Head Office',
                'parent_id'      => null,
                'is_active'      => true,
            ]);

            /* ================= SALES ================= */
            $sales = OrganizationalUnit::firstOrCreate([
                'code' => 'SALES',
            ], [
                'name'           => 'Sales Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Sales Operations',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            OrganizationalUnit::insertOrIgnore([
                [
                    'name' => 'Sales – North',
                    'code' => 'SALES-N',
                    'type_master_id' => $divisionType->id,
                    'parent_id' => $sales->id,
                    'is_active' => true,
                ],
                [
                    'name' => 'Sales – South',
                    'code' => 'SALES-S',
                    'type_master_id' => $divisionType->id,
                    'parent_id' => $sales->id,
                    'is_active' => true,
                ],
            ]);

            /* ================= MARKETING ================= */
            OrganizationalUnit::firstOrCreate([
                'code' => 'MKT',
            ], [
                'name'           => 'Marketing Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Marketing Operations',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            /* ================= FINANCE ================= */
            OrganizationalUnit::firstOrCreate([
                'code' => 'FIN',
            ], [
                'name'           => 'Finance Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Accounts & Finance',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            /* ================= HR ================= */
            OrganizationalUnit::firstOrCreate([
                'code' => 'HR',
            ], [
                'name'           => 'HR Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Human Resources',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            /* ================= IT ================= */
            OrganizationalUnit::firstOrCreate([
                'code' => 'IT',
            ], [
                'name'           => 'IT Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Information Technology',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            /* ================= MANUFACTURING ================= */
            $mfg = OrganizationalUnit::firstOrCreate([
                'code' => 'MFG',
            ], [
                'name'           => 'Manufacturing Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'Manufacturing Operations',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);

            OrganizationalUnit::insertOrIgnore([
                [
                    'name' => 'Plant A',
                    'code' => 'PLANT-A',
                    'type_master_id' => $plantType->id,
                    'parent_id' => $mfg->id,
                    'is_active' => true,
                ],
                [
                    'name' => 'Plant B',
                    'code' => 'PLANT-B',
                    'type_master_id' => $plantType->id,
                    'parent_id' => $mfg->id,
                    'is_active' => true,
                ],
            ]);

            /* ================= SERVICE ================= */
            OrganizationalUnit::firstOrCreate([
                'code' => 'SERVICE',
            ], [
                'name'           => 'Service Division',
                'type_master_id' => $divisionType->id,
                'description'    => 'After Sales & Support',
                'parent_id'      => $ho->id,
                'is_active'      => true,
            ]);
        });
    }
}
