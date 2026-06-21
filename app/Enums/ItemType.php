<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ItemType: string implements HasLabel
{
    case Sample = 'sample';
    case Gift = 'gift';
    case PromotionalInput = 'promotional_input';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sample => 'Sample',
            self::Gift => 'Gift',
            self::PromotionalInput => 'Promotional Input',
        };
    }
}
