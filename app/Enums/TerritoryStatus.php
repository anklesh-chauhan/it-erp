<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

// This enum assumes you might be using PHP 8.1+ Enums.
// It also includes interfaces from FilamentPHP (HasColor, HasLabel) for potential UI integration.
// If not using Filament, you can remove 'use Filament...' lines and implement getColor/getLabel manually or remove them.

enum TerritoryStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Inactive = 'inactive';

    /**
     * Get the display label for the enum case.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    /**
     * Get the color associated with the enum case (useful for UI, e.g., Filament).
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => Color::Green,
            self::Inactive => Color::Gray,
        };
    }
}
