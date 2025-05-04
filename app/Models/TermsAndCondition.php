<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
        'terms_type_id',
        'terms_and_conditions',
        'remarks',
    ];

    /**
     * Polymorphic Relation for `model`.
     */
    public function model()
    {
        return $this->morphTo();
    }
}
