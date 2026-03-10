<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseConfigurationCondition extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'expense_configuration_id', 'condition_key', 'operator', 'value'
    ];

    /*--------------------------------------------------------------------------
    | Helpers
    |---------------------------------------------------------------------------
    */
    public const CONDITION_KEYS = [
        'outstation'   => 'Outstation',
        'visit_count'  => 'Visit Count',
        'joint_work'   => 'Joint Work',
        'distance'     => 'Distance',
    ];

    public const OPERATORS = [
        '='  => '=',
        '>'  => '>',
        '<'  => '<',
        '>=' => '>=',
        '<=' => '<=',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(ExpenseConfiguration::class, 'expense_configuration_id');
    }
}
