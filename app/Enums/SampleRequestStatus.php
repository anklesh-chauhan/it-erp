<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SampleRequestStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case PartiallyIssued = 'partially_issued';
    case Fulfilled = 'fulfilled';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Approved => 'Approved',
            self::PartiallyIssued => 'Partially Issued',
            self::Fulfilled => 'Fulfilled',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => Color::Gray,
            self::Submitted => Color::Blue,
            self::Approved => Color::Green,
            self::PartiallyIssued => Color::Amber,
            self::Fulfilled => Color::Emerald,
            self::Cancelled => Color::Red,
        };
    }
}
