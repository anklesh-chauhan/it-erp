<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class SalesDocumentPreference extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = [
        'attach_pdf_in_email',
        'encrypt_pdf',
        'discount_level',
        'include_adjustments',
        'include_shipping_charges',
        'tax_mode',
        'rounding_option',
        'enable_salesperson',
        'enable_billable_expenses',
        'default_markup_percentage',
        'document_copy_type',
        'default_print_preferences',
    ];

    protected $casts = [
        'attach_pdf_in_email' => 'boolean',
        'encrypt_pdf' => 'boolean',
        'include_adjustments' => 'boolean',
        'include_shipping_charges' => 'boolean',
        'enable_salesperson' => 'boolean',
        'enable_billable_expenses' => 'boolean',
        'default_markup_percentage' => 'decimal:2',
        'default_print_preferences' => 'array',
    ];

    /**
     * Default value handling if needed
     */
    protected $attributes = [
        'attach_pdf_in_email' => true,
        'encrypt_pdf' => false,
        'discount_level' => 'none',
        'include_adjustments' => false,
        'include_shipping_charges' => false,
        'tax_mode' => 'exclusive',
        'rounding_option' => 'none',
        'enable_salesperson' => false,
        'enable_billable_expenses' => false,
        'document_copy_type' => 'original_duplicate',
    ];
}
