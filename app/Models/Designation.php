<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class Designation extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function contactDetails()
    {
        return $this->hasMany(ContactDetail::class);
    }
}
