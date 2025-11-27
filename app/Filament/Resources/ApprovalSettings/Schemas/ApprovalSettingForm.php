<?php

namespace App\Filament\Resources\ApprovalSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms;;
use App\Helpers\ModelHelper;

class ApprovalSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\CheckboxList::make('enabled_modules')
                    ->label('Models Requiring Approval')
                    ->options(ModelHelper::getModelOptions())
                    ->columns(6)
                    ->helperText('Select which models need approval workflow.'),
            ])->columns(1);
    }
}
