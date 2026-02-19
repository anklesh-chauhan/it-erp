<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function configurations(): HasMany
    {
        return $this->hasMany(ExpenseConfiguration::class);
    }
}
