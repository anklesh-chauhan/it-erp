<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'item_master_id',
        'item_variant_id',
        'price',
        'discount',
    ];

    public function customer()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class);
    }

    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class);
    }
}

