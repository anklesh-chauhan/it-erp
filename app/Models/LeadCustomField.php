<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadCustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'name',
    ];

}
