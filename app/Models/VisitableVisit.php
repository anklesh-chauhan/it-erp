<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitableVisit extends Model
{
    protected $fillable = ['visit_id', 'visitable_id', 'visitable_type'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function visitable()
    {
        return $this->morphTo();
    }
}
