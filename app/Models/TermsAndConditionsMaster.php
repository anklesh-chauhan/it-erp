<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditionsMaster extends Model
{
    protected $fillable = [
        'document_type',
        'title',
        'content',
        'order',
        'is_default',
    ];
}
