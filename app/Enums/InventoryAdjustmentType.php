<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InventoryAdjustmentType: string implements HasLabel
{
    case Receipt = 'receipt';
    case Issue = 'issue';
    case Increase = 'increase';
    case Decrease = 'decrease';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Receipt => 'Receipt',
            self::Issue => 'Issue',
            self::Increase => 'Increase',
            self::Decrease => 'Decrease',
        };
    }

    public function isInbound(): bool
    {
        return in_array($this, [self::Receipt, self::Increase], true);
    }
}
