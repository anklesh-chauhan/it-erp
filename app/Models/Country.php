<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasApprovalWorkflow;

class Country extends Model
{
    use HasApprovalWorkflow;

        protected $fillable = ['name'];

        public function states(): HasMany
        {
            return $this->hasMany(State::class);
        }
}
