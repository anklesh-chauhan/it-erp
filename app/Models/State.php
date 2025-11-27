<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class State extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'country_id', 'gst_code'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
