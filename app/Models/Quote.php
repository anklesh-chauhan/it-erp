<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends SalesDocument
{
    protected $table = 'quotes';

    protected $fillable = [
        ...parent::FILLABLE,
        'company_id',
        'expiration_date',
        'accepted_at',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'accepted_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
