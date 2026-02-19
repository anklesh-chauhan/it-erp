<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDcrJointUser extends Model
{
    protected $fillable = ['sales_dcr_visit_id', 'user_id'];

    public function visit()
    {
        return $this->belongsTo(SalesDcrVisit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
