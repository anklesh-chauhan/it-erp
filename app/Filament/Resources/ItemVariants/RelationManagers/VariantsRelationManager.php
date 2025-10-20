<?php

namespace App\Filament\Resources\ItemVariants\RelationManagers;

use App\Filament\Resources\ItemVariants\ItemVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $relatedResource = ItemVariantResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
