<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Status extends Model
{
    // Define common attributes or methods if needed
    protected $fillable = ['name', 'color', 'order'];

    // Polymorphic relationship to entities (Lead, Deal, etc.)
    public function statusable()
    {
        return $this->morphTo();
    }
}
