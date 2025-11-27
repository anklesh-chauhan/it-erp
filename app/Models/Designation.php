<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class Designation extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function contactDetails()
    {
        return $this->hasMany(ContactDetail::class);
    }
}
