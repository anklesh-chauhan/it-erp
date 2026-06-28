<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PromotionalSchemeAppliesTo: string implements HasLabel
{
    case Global = 'global';
    case Customer = 'customer';
    case Territory = 'territory';
    case Item = 'item';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Global => 'Global',
            self::Customer => 'Customer',
            self::Territory => 'Territory',
            self::Item => 'Item',
        };
    }
}
