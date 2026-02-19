<?php

namespace App\Filament\Resources\SalesDcrs\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn as ColumnsTextColumn;
use Filament\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms;
use Filament\Tables;

class SalesDcrVisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'visits';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\MorphToSelect::make('visitable')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(\App\Models\AccountMaster::class)
                            ->titleAttribute('name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Lead::class)
                            ->titleAttribute('name'),
                    ])
                    ->required(),

                Forms\Components\Toggle::make('is_joint_work'),

                Forms\Components\TimePicker::make('check_in_at'),
                Forms\Components\TimePicker::make('check_out_at'),

                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Visits')
            ->columns([
                Tables\Columns\TextColumn::make('visitable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                Tables\Columns\TextColumn::make('visitable.name')
                    ->label('Customer / Lead'),

                Tables\Columns\IconColumn::make('is_joint_work')
                    ->boolean(),

                Tables\Columns\TextColumn::make('check_in_at'),
                Tables\Columns\TextColumn::make('check_out_at'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
