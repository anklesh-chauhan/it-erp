<?php

namespace App\Enums;

enum AccountMasterCreditType: string
{
    case DAYS = 'Days';
    case LIMIT = 'Limit';
    case BOTH = 'Both';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->name)])->toArray();
    }

    public static function getLabel(string $value): string
    {
        return self::from($value)->name;
    }
    public static function getValue(string $label): string
    {
        return self::from($label)->value;
    }
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
    public static function isValidLabel(string $label): bool
    {
        return in_array($label, array_column(self::cases(), 'name'), true);
    }
}
