<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Visit extends BaseModel
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'document_number',

        'employee_id',
        'reporting_manager_id',

        'territory_id',
        'patch_id',

        'sales_tour_plan_id',
        'sales_tour_plan_detail_id',

        'visit_date',
        'start_time',
        'end_time',

        'visit_type',
        'visit_status',

        'approval_status',
        'approved_by',
        'approved_at',

        'remarks',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'visit_date'  => 'date',
        'start_time'  => 'datetime:H:i',
        'end_time'    => 'datetime:H:i',
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Sales employee who performed the visit.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Reporting manager at the time of visit (snapshot).
     */
    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    /**
     * Territory of the visit.
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /**
     * Patch under which the visit happened.
     */
    public function patch(): BelongsTo
    {
        return $this->belongsTo(Patch::class);
    }

    /**
     * Monthly Sales Tour Plan.
     */
    public function salesTourPlan(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlan::class);
    }

    /**
     * Date-wise Sales Tour Plan Detail.
     */
    public function salesTourPlanDetail(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlanDetail::class);
    }

    /**
     * Approver of the visit.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Polymorphic visit targets (Company, Contact, etc.).
     */
    public function visitables(): HasMany
    {
        return $this->hasMany(VisitableVisit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function primaryCompany(): ?AccountMaster
    {
        return $this->visitables()
            ->where('visitable_type', AccountMaster::class)
            ->first()
            ?->visitable;
    }

    public function customerAddresses(): Collection
    {
        return $this->primaryCompany()?->addresses()->with(['city', 'state'])->get()
            ?? collect();
    }

    public function isPlanned(): bool
    {
        return $this->visit_type === 'planned';
    }

    public function isCompleted(): bool
    {
        return $this->visit_status === 'completed';
    }

    public function requiresApproval(): bool
    {
        return $this->visit_status === 'completed'
            && $this->approval_status === 'pending';
    }
}
