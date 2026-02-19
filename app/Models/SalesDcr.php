<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesDcr extends BaseModel
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'dcr_date', 'user_id', 'sales_tour_plan_id', 'status',
        'total_expense', 'submitted_at', 'approved_at', 'remarks'
    ];

    protected $casts = [
        'dcr_date' => 'date',
        'submitted_at' => 'timestamp',
        'approved_at' => 'timestamp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(SalesDcrVisit::class);
    }

    public function expenses()
    {
        return $this->hasMany(SalesDcrExpense::class);
    }
}
