<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SgipStockSource: string implements HasLabel
{
    case SampleIssue = 'sample_issue';
    case Headquarters = 'headquarters';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SampleIssue => 'Require posted Sample Issue',
            self::Headquarters => 'Allow direct issue from HQ stock',
        };
    }
}
