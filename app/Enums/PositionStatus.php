<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum for the status of a Position.
 * Includes FilamentPHP interfaces for UI integration (colors and labels).
 */
enum PositionStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Vacant = 'vacant';

    /**
     * Get the display label for the enum case.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Vacant => 'Vacant',
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
            self::Vacant => Color::Orange, // Or Color::Blue, Color::Yellow etc.
        };
    }
}
