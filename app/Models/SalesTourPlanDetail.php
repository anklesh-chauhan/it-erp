<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class SalesTourPlanDetail extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'sales_tour_plan_id',
        'date',
        'territory_id',
        'remarks',
        'joint_with',
        'visit_type_id',
        'visit_purpose_ids',
    ];

    protected array $visitPurposeIds = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'patch_ids' => 'array',
        'joint_with' => 'array',
        'visit_purpose_ids' => 'array',
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

    /**
     * The visit type (optional).
     */
    public function visitType(): BelongsTo
    {
        return $this->belongsTo(VisitType::class, 'visit_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve patches related to this tour plan detail.
     */

    public function patches(): BelongsToMany
    {
        // Point to the pivot table we just created
        return $this->belongsToMany(Patch::class, 'patch_sales_tour_plan_detail');
    }

    /**
     * Return joint employees as a collection of users.
     */
    public function getJointUsersAttribute()
    {
        return User::whereIn('id', $this->joint_with ?? [])->get();
    }

    /**
     * Return visit purposes as a collection.
     */
    public function getVisitPurposesAttribute()
    {
        return VisitPurpose::whereIn('id', $this->visit_purpose_ids ?? [])->get();
    }
}
