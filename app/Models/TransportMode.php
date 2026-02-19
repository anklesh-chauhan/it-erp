<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportMode extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name', 'code', 'is_distance_based'];

    protected $casts = [
        'is_distance_based' => 'boolean',
    ];

    public function expenseConfigurations(): HasMany
    {
        return $this->hasMany(ExpenseConfiguration::class);
    }
}
