<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'route_date',
        'lead_id',
        'contact_detail_id',
        'company_id',
        'address_id',
        'description',
    ];

    /**
     * Relationship: Assigned User (Salesperson/Field Staff)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Linked Lead
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Relationship: Linked Contact Detail
     */
    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class);
    }

    /**
     * Relationship: Linked Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship: Linked Address
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function tourPlans()
    {
        return $this->belongsToMany(TourPlan::class, 'visit_route_tour_plan')
                    ->withPivot('visit_order')
                    ->withTimestamps();
    }

}
