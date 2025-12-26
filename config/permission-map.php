<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission Levels
    |--------------------------------------------------------------------------
    | F = Full (view, create, update, delete)
    | C = Create + View + Update
    | R = Read-only
    */

    'roles' => [

        /* ================= ADMIN ================= */
        'administration_admin' => [
            '*' => 'F', // Full access to everything
        ],

        /* ================= SALES ================= */
        'sales_admin' => [
            'LeadResource'               => 'F',
            'LeadActivityResource'       => 'F',
            'DealResource'               => 'F',
            'QuoteResource'              => 'F',
            'SalesOrderResource'         => 'F',
            'SalesInvoiceResource'       => 'R',
            'CustomerPriceResource'      => 'C',
            'SalesDcrResource'           => 'F',
            'SalesTourPlanResource'      => 'F',
            'ContactDetailResource'      => 'C',
            'CompanyMasterResource'      => 'R',
            'ItemMasterResource'         => 'R',
        ],

        'sales_user' => [
            'LeadResource'           => 'C',
            'QuoteResource'          => 'C',
            'SalesOrderResource'     => 'C',
            'SalesInvoiceResource'   => 'R',
        ],

        /* ================= HR ================= */
        'hr_admin' => [
            'EmployeeResource'                 => 'F',
            'EmpDepartmentResource'            => 'F',
            'EmpGradeResource'                 => 'F',
            'EmpJobTitleResource'              => 'F',
            'EmployeeAttendanceResource'       => 'F',
            'EmployeeAttendanceStatusResource' => 'F',
            'DailyAttendanceResource'          => 'F',
            'ShiftMasterResource'              => 'F',
        ],

        'hr_user' => [
            'EmployeeResource'           => 'C',
            'DailyAttendanceResource'    => 'C',
        ],

        /* ================= FINANCE ================= */
        'finance_admin' => [
            'ChartOfAccountResource'      => 'F',
            'LedgerResource'              => 'F',
            'PaymentMethodResource'       => 'F',
            'PaymentTermResource'         => 'F',
            'SalesInvoiceResource'        => 'F',
            'SalesDailyExpenseResource'   => 'F',
            'ExpenseTypeResource'         => 'F',
            'ExpenseConfigurationResource'=> 'F',
            'TaxResource'                 => 'F',
            'GstPanResource'              => 'F',
        ],

        'accounts_user' => [
            'SalesInvoiceResource'        => 'C',
            'SalesDailyExpenseResource'   => 'C',
        ],

        /* ================= AUDITOR ================= */
        'auditor' => [
            '*' => 'R',
        ],
    ],
];
