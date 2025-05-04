<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPlan extends Model
{
    use HasFactory, SoftDeletes;

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
