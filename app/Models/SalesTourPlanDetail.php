<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

use App\Traits\HasApprovalWorkflow;

class SalesTourPlanDetail extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'sales_tour_plan_id',
        'date',
        'territory_id',
        'patch_ids',
        'purpose',
        'remarks',
        'joint_with',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'patch_ids' => 'array',
        'joint_with' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The parent tour plan.
     */
    public function tourPlan(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlan::class, 'sales_tour_plan_id');
    }

    /**
     * The related territory (optional).
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve patches related to this tour plan detail.
     */
    public function getPatchesAttribute()
    {
        return Patch::whereIn('id', $this->patch_ids ?? [])->get();
    }

    /**
     * Return joint employees as a collection of users.
     */
    public function getJointUsersAttribute()
    {
        return User::whereIn('id', $this->joint_with ?? [])->get();
    }
}
