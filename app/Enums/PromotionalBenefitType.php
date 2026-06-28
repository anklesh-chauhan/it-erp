<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PromotionalBenefitType: string implements HasLabel
{
    case DiscountPercent = 'discount_percent';
    case DiscountAmount = 'discount_amount';
    case FreeItem = 'free_item';
    case BuyQuantity = 'buy_quantity';
    case GetQuantity = 'get_quantity';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DiscountPercent => 'Discount (%)',
            self::DiscountAmount => 'Discount (Amount)',
            self::FreeItem => 'Free Item',
            self::BuyQuantity => 'Buy Quantity',
            self::GetQuantity => 'Get Quantity',
        };
    }
}
