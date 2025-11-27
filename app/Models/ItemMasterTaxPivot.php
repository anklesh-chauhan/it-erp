<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class ItemMasterTaxPivot extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $table = 'item_master_tax_pivots';

    protected $fillable = [
        'item_master_id',
        'tax_id',
    ];

    /**
     * Relationship: Belongs to ItemMaster
     */
    public function item()
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    /**
     * Relationship: Belongs to Tax
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
