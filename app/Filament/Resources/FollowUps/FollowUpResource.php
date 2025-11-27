<?php

namespace App\Filament\Resources\FollowUps;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FollowUps\Pages\ListFollowUps;
use App\Filament\Resources\FollowUps\Pages\CreateFollowUp;
use App\Filament\Resources\FollowUps\Pages\EditFollowUp;
use App\Filament\Resources\FollowUpResource\Pages;
use App\Filament\Resources\FollowUpResource\RelationManagers;
use App\Models\FollowUp;
use App\Models\Lead;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowUpResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = FollowUp::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Sales & Marketing';
    protected static ?string $navigationParentItem = 'Leads';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Follow ups';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('followupable_type')
                    ->label('Follow-up Type')
                    ->options([
                        'App\Models\Lead' => 'Lead',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($set) => $set('followupable_id', null)),

                Select::make('followupable_id')
                    ->label('Related Record')
                    ->options(fn (Get $get) => match ($get('followupable_type')) {
                        'App\Models\Lead' => Lead::pluck('id'),
                        default => [],
                    })
                    ->searchable()
                    ->required(),

                DateTimePicker::make('follow_up_date')
                    ->required()
                    ->label('Follow-up Date'),

                Select::make('media')
                    ->relationship('media', 'name')
                    ->label('Media')
                    ->nullable(),

                Textarea::make('interaction')
                    ->label('Interaction')
                    ->rows(3)
                    ->nullable(),

                Textarea::make('outcome')
                    ->label('Outcome')
                    ->rows(2)
                    ->nullable(),

                Select::make('result')
                    ->label('Result')
                    ->relationship('result', 'name')
                    ->nullable(),

                DateTimePicker::make('next_follow_up_date')
                    ->label('Next Follow-up Date')
                    ->nullable(),

                Select::make('priority')
                    ->label('Select Priority')
                    ->relationship('priority', 'name')
                    ->nullable(),

                Select::make('status')
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
                TextColumn::make('followupable_type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->label('Follow-up Type')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('follow_up_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('media.name')
                    ->searchable(),

                TextColumn::make('contactDetail.full_name')
                    ->label('To Whom')
                    ->tooltip(fn ($record) => "Email: {$record->contactDetail->email}\nPhone: {$record->contactDetail->mobile_number}")
                    ->sortable()
                    ->searchable(),

                TextColumn::make('result.name')
                    ->searchable(),
                TextColumn::make('next_follow_up_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('priority.name')
                    ->searchable(),
                TextColumn::make('status.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListFollowUps::route('/'),
            'create' => CreateFollowUp::route('/create'),
            'edit' => EditFollowUp::route('/{record}/edit'),
        ];
    }
}
