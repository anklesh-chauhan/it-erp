<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class ItemBrand extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'name',
    ];
}
