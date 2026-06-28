<?php

namespace App\Models;

use App\Enums\PromotionalBenefitType;
use Database\Factories\PromotionalSchemeBenefitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionalSchemeBenefit extends Model
{
    /** @use HasFactory<PromotionalSchemeBenefitFactory> */
    use HasFactory;

    protected $fillable = [
        'promotional_scheme_id',
        'benefit_type',
        'item_master_id',
        'buy_quantity',
        'get_quantity',
        'discount_value',
        'min_quantity',
        'max_quantity',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'benefit_type' => PromotionalBenefitType::class,
            'buy_quantity' => 'decimal:3',
            'get_quantity' => 'decimal:3',
            'discount_value' => 'decimal:4',
            'min_quantity' => 'decimal:3',
            'max_quantity' => 'decimal:3',
        ];
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(PromotionalScheme::class, 'promotional_scheme_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }
}
