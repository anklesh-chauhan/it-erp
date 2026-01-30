<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitOutcome extends Model
{
    protected $fillable = ['visit_id', 'outcome_type', 'notes'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
