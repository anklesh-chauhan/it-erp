<?php

namespace App\Filament\Resources\LeaveRuleCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class LeaveRuleCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')->required()->unique(ignoreRecord: true),
                TextInput::make('name')->required(),
                Textarea::make('description'),
            ]);
    }
}
