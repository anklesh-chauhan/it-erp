<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowUpResource\Pages;
use App\Filament\Resources\FollowUpResource\RelationManagers;
use App\Models\FollowUp;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowUpResource extends Resource
{
    protected static ?string $model = FollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sales & Marketing';
    protected static ?string $navigationParentItem = 'Leads';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Follow ups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('followupable_type')
                    ->label('Follow-up Type')
                    ->options([
                        'App\Models\Lead' => 'Lead',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($set) => $set('followupable_id', null)),

                Forms\Components\Select::make('followupable_id')
                    ->label('Related Record')
                    ->options(fn (Forms\Get $get) => match ($get('followupable_type')) {
                        'App\Models\Lead' => Lead::pluck('id'),
                        default => [],
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('follow_up_date')
                    ->required()
                    ->label('Follow-up Date'),

                Forms\Components\Select::make('media')
                    ->relationship('media', 'name')
                    ->label('Media')
                    ->nullable(),

                Forms\Components\Textarea::make('interaction')
                    ->label('Interaction')
                    ->rows(3)
                    ->nullable(),

                Forms\Components\Textarea::make('outcome')
                    ->label('Outcome')
                    ->rows(2)
                    ->nullable(),

                Forms\Components\Select::make('result')
                    ->label('Result')
                    ->relationship('result', 'name')
                    ->nullable(),

                Forms\Components\DateTimePicker::make('next_follow_up_date')
                    ->label('Next Follow-up Date')
                    ->nullable(),

                Forms\Components\Select::make('priority')
                    ->label('Select Priority')
                    ->relationship('priority', 'name')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->relationship('status', 'name')
                    ->default('Pending')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('contactDetail');
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('followupable_type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->label('Follow-up Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('follow_up_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('media.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('contactDetail.full_name')
                    ->label('To Whom')
                    ->tooltip(fn ($record) => "Email: {$record->contactDetail->email}\nPhone: {$record->contactDetail->mobile_number}")
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('result.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFollowUps::route('/'),
            'create' => Pages\CreateFollowUp::route('/create'),
            'edit' => Pages\EditFollowUp::route('/{record}/edit'),
        ];
    }
}
