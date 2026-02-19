<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitOutcome extends Model
{
    protected $fillable = ['label', 'code', 'notes'];
}
