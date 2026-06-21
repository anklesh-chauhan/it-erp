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
            'LeadResource' => 'F',
            'LeadActivityResource' => 'F',
            'DealResource' => 'F',
            'QuoteResource' => 'F',
            'SalesOrderResource' => 'F',
            'SalesInvoiceResource' => 'R',
            'CustomerPriceResource' => 'C',
            'SalesDcrResource' => 'F',
            'SalesTourPlanResource' => 'F',
            'ContactDetailResource' => 'C',
            'CompanyMasterResource' => 'R',
            'ItemMasterResource' => 'R',
            'PurchaseOrderResource' => 'C',
            'GoodsReceiptNoteResource' => 'C',
            'DeliveryChallanResource' => 'C',
            'SampleRequestResource' => 'C',
            'SampleIssueResource' => 'C',
            'InventoryAdjustmentResource' => 'C',
            'InventoryStockResource' => 'R',
            'InventoryMovementResource' => 'R',
        ],

        'sales_user' => [
            'PatchResource' => 'C',
            'AccountMasterResource' => 'C',
            'SalesTourPlanResource' => 'C',
            'LeaveApplicationResource' => 'C',
            'LeadResource' => 'C',
            'QuoteResource' => 'C',
            'SalesOrderResource' => 'C',
            'SalesInvoiceResource' => 'R',
            'SampleRequestResource' => 'C',
            'SampleIssueResource' => 'R',
        ],

        /* ================= HR ================= */
        'hr_admin' => [
            'EmployeeResource' => 'F',
            'EmpDepartmentResource' => 'F',
            'EmpGradeResource' => 'F',
            'EmpJobTitleResource' => 'F',
            'EmployeeAttendanceResource' => 'F',
            'EmployeeAttendanceStatusResource' => 'F',
            'DailyAttendanceResource' => 'F',
            'ShiftMasterResource' => 'F',
        ],

        'hr_user' => [
            'EmployeeResource' => 'C',
            'DailyAttendanceResource' => 'C',
        ],

        /* ================= FINANCE ================= */
        'finance_admin' => [
            'ChartOfAccountResource' => 'F',
            'LedgerResource' => 'F',
            'PaymentMethodResource' => 'F',
            'PaymentTermResource' => 'F',
            'SalesInvoiceResource' => 'F',
            'SalesDailyExpenseResource' => 'F',
            'ExpenseTypeResource' => 'F',
            'ExpenseConfigurationResource' => 'F',
            'TaxResource' => 'F',
            'GstPanResource' => 'F',
            'PurchaseOrderResource' => 'F',
            'GoodsReceiptNoteResource' => 'F',
            'DeliveryChallanResource' => 'F',
            'SampleRequestResource' => 'F',
            'SampleIssueResource' => 'F',
            'InventoryAdjustmentResource' => 'F',
            'InventoryStockResource' => 'F',
            'InventoryMovementResource' => 'F',
            'ItemMasterResource' => 'R',
            'LocationMasterResource' => 'R',
        ],

        'accounts_user' => [
            'SalesInvoiceResource' => 'C',
            'SalesDailyExpenseResource' => 'C',
        ],

        /* ================= AUDITOR ================= */
        'auditor' => [
            '*' => 'R',
        ],
    ],
];
