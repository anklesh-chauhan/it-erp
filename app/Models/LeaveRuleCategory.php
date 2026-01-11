<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRuleCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    public function rules()
    {
        return $this->hasMany(LeaveRule::class);
    }
}
