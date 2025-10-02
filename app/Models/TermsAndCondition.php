<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'title',
        'content',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
