<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

        // 🔹 NEW lifecycle fields
        'reschedule_state',
        'rescheduled_for',
        'rescheduled_visit_id',
        'cancel_reason',

        'approval_status',
        'approved_by',
        'approved_at',

        'remarks',
        'attachments',

        'visit_outcome_id',
        'checkin_latitude',
        'checkin_longitude',
        'checkout_latitude',
        'checkout_longitude',
        'image_latitude',
        'image_longitude',

        'is_joint_work',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'visit_date'       => 'date',
        'start_time'       => 'datetime',
        'end_time'         => 'datetime',
        'approved_at'      => 'datetime',
        'rescheduled_for'  => 'date',
        'is_joint_work'    => 'boolean',
        'attachments' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function patch(): BelongsTo
    {
        return $this->belongsTo(Patch::class);
    }

    public function salesTourPlan(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlan::class);
    }

    public function salesTourPlanDetail(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlanDetail::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function outcome(): BelongsTo
    {
        return $this->belongsTo(VisitOutcome::class, 'visit_outcome_id');
    }

    public function jointUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'visit_joint_users');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function visitPurposes(): BelongsToMany
    {
        return $this->belongsToMany(VisitPurpose::class, 'visit_visit_purposes');
    }

    /**
     * Polymorphic visit targets (Company, Contact, etc.)
     */
    public function visitables(): HasMany
    {
        return $this->hasMany(VisitableVisit::class);
    }

    /**
     * 🔁 Link to newly created visit after reschedule approval
     */
    public function rescheduledVisit(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rescheduled_visit_id');
    }

    /**
     * 🔁 Original visit that created this visit
     */
    public function originalVisit(): HasOne
    {
        return $this->hasOne(self::class, 'rescheduled_visit_id');
    }

    public function followUps()
    {
        return $this->morphMany(FollowUp::class, 'followupable');
    }

    public function nextFollowUp()
    {
        return $this->followUps()
            ->whereNotNull('next_follow_up_date')
            ->orderBy('next_follow_up_date')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (CRITICAL FOR UI BUTTONS)
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
        return $this->primaryCompany()
            ?->addresses()
            ->with(['city', 'state'])
            ->get()
            ?? collect();
    }

    /* ---------- Status helpers ---------- */

    public function isDraft(): bool
    {
        return $this->visit_status === 'draft';
    }

    public function isStarted(): bool
    {
        return $this->visit_status === 'started';
    }

    public function isCompleted(): bool
    {
        return $this->visit_status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->visit_status === 'cancelled';
    }

    public function isRescheduleRequested(): bool
    {
        return $this->isCancelled() && $this->reschedule_state === 'requested';
    }

    public function isRescheduled(): bool
    {
        return $this->isCancelled() && $this->reschedule_state !== 'none';
    }

    public function canBeCancelled(): bool
    {
        return ! in_array($this->visit_status, ['completed', 'cancelled'], true);
    }

    public function canBeRescheduled(): bool
    {
        return $this->visit_status === 'draft';
    }

    public function requiresApproval(): bool
    {
        return $this->visit_status === 'completed'
            && $this->approval_status === 'pending';
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(VisitFeedback::class);
    }

    public function hasCheckInImage(): bool
    {
        return $this->media()
            ->whereHas('tags', fn ($q) => $q->where('slug', 'check-in'))
            ->exists();
    }

    public function hasCheckOutImage(): bool
    {
        return $this->media()
            ->whereHas('tags', fn ($q) => $q->where('slug', 'check-out'))
            ->exists();
    }

    public function hasGeneralVisitImage(): bool
    {
        return $this->media()
            ->whereHas('tags', fn ($q) => $q->where('slug', 'general-visit'))
            ->exists();
    }

}
