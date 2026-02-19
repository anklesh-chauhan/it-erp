<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDcrVisit extends BaseModel
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'sales_dcr_id', 'visitable_type', 'visitable_id', 'visit_type_id',
        'visit_purpose_id', 'visit_outcome_id', 'check_in_at', 'check_out_at',
        'latitude', 'longitude', 'is_joint_work', 'notes'
    ];

    protected $casts = [
        'is_joint_work' => 'boolean',
    ];

    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }

    public function salesDcr(): BelongsTo
    {
        return $this->belongsTo(SalesDcr::class);
    }

    public function jointUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sales_dcr_joint_users', 'sales_dcr_visit_id', 'user_id');
    }

    public function outcome(): BelongsTo
    {
        return $this->belongsTo(VisitOutcome::class, 'visit_outcome_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(SalesDcrVisitFeedback::class);
    }

}
