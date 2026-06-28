<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PromotionalSchemeType: string implements HasLabel
{
    case PercentDiscount = 'percent_discount';
    case FixedDiscount = 'fixed_discount';
    case BuyXGetY = 'buy_x_get_y';
    case SlabDiscount = 'slab_discount';
    case FreeGoods = 'free_goods';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PercentDiscount => 'Percentage Discount',
            self::FixedDiscount => 'Fixed Amount Discount',
            self::BuyXGetY => 'Buy X Get Y',
            self::SlabDiscount => 'Slab / Tier Discount',
            self::FreeGoods => 'Free Goods',
        };
    }
}
