<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Traits\HasApprovalWorkflow;

class SalesTourPlan extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'user_id',
        'month',
        'status',
        'manager_remarks',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The sales employee (creator) of the plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The details of the tour plan.
     */

    public function details(): HasMany
    {
        return $this->hasMany(SalesTourPlanDetail::class, 'sales_tour_plan_id');
    }

    /**
     * The manager who approved the plan.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Related patches for this plan.
     */
    public function patches(): HasMany
    {
        return $this->hasMany(Patch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
