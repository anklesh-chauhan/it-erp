<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class TourPlan extends BaseModel
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'user_id',
        'plan_date',
        'location',
        'start_time',
        'end_time',
        'visit_purpose_id',
        'target_customer',
        'notes',
        'mode_of_transport',
        'distance_travelled',
        'travel_expenses',
    ];

    /**
     * Relation with User (Salesperson/Field Staff)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation with VisitPurpose
     */
    public function visitPurpose()
    {
        return $this->belongsTo(VisitPurpose::class);
    }

    /**
     * Relation with VisitRoutes (Many-to-Many)
     */
    public function visitRoutes()
    {
        return $this->belongsToMany(VisitRoute::class, 'visit_route_tour_plan')
                    ->withPivot('visit_order')
                    ->withTimestamps();
    }
}
