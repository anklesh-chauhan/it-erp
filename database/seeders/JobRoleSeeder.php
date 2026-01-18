<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobRole;
use Illuminate\Support\Facades\DB;

class JobRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 🔝 Top Level
            $ceo = $this->createRole(
                name: 'Chief Executive Officer',
                code: 'CEO',
                level: 1
            );

            // 🔧 Technology
            $cto = $this->createRole(
                'Chief Technology Officer',
                'CTO',
                2,
                $ceo->id
            );

            $hrManager = $this->createRole(
                'HR Manager',
                'HR-MGR',
                3,
                $ceo->id
            );

            $engManager = $this->createRole(
                'Engineering Manager',
                'ENG-MGR',
                3,
                $cto->id
            );

            $this->createRole('Senior Developer', 'SR-DEV', 4, $engManager->id);
            $this->createRole('Junior Developer', 'JR-DEV', 5, $engManager->id);

            // 💰 Finance
            $cfo = $this->createRole(
                'Chief Financial Officer',
                'CFO',
                2,
                $ceo->id
            );

            $financeManager = $this->createRole(
                'Finance Manager',
                'FIN-MGR',
                3,
                $cfo->id
            );

            $this->createRole(
                'Accountant',
                'ACC',
                4,
                $financeManager->id
            );

            // ⚙ Operations
            $coo = $this->createRole(
                'Chief Operating Officer',
                'COO',
                2,
                $ceo->id
            );

            $opsManager = $this->createRole(
                'Operations Manager',
                'OPS-MGR',
                3,
                $coo->id
            );

            $this->createRole(
                'Operations Executive',
                'OPS-EXEC',
                4,
                $opsManager->id
            );

            // 📈 Sales & CRM
            $headSales = $this->createRole(
                'Head of Sales',
                'H-SALES',
                2,
                $ceo->id
            );

            $salesManager = $this->createRole(
                'Sales Manager',
                'SALES-MGR',
                3,
                $headSales->id
            );

            $this->createRole(
                'Senior Sales Executive',
                'SR-SALES',
                4,
                $salesManager->id
            );

            $this->createRole(
                'Sales Executive',
                'SALES-EXEC',
                5,
                $salesManager->id
            );

            $crmLead = $this->createRole(
                'CRM Support Lead',
                'CRM-LEAD',
                3,
                $headSales->id
            );

            $this->createRole(
                'CRM Support Executive',
                'CRM-EXEC',
                4,
                $crmLead->id
            );
        });
    }

    /**
     * Helper to create job roles consistently
     */
    protected function createRole(
        string $name,
        string $code,
        int $level,
        ?int $reportsTo = null
    ): JobRole {
        return JobRole::create([
            'name' => $name,
            'code' => $code,
            'level' => $level,
            'reports_to_job_role_id' => $reportsTo,
            'description' => "{$name} role responsible for level {$level} operations.",
        ]);
    }
}
