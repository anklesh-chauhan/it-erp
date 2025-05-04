<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitRouteTourPlan extends Model
{
    use HasFactory;

    protected $table = 'visit_route_tour_plan';

    protected $fillable = [
        'visit_route_id',
        'tour_plan_id',
        'visit_order',
    ];

    /**
     * Relation with VisitRoute
     */
    public function visitRoute()
    {
        return $this->belongsTo(VisitRoute::class);
    }

    /**
     * Relation with TourPlan
     */
    public function tourPlan()
    {
        return $this->belongsTo(TourPlan::class);
    }
}
