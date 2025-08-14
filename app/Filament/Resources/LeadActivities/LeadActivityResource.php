<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadActivityResource\Pages;
use App\Filament\Resources\LeadActivityResource\RelationManagers;
use App\Models\LeadActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadActivityResource extends Resource
{
    protected static ?string $model = LeadActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationParentItem = 'Lead';
    protected static ?string $navigationGroup = 'Sales & Marketing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'displayName') // Updated to show Company or Contact Name
                    ->label('Lead')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('activity_type')
                    ->label('Activity Type')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.displayName') // Uses Company or Contact Name
                    ->label('Lead')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('activity_type')
                    ->label('Activity Type')
                    ->badge()
                    ->color(fn ($record) => match ($record->activity_type) {
                        'Follow-up Created' => 'success',
                        'Item Attached' => 'warning',
                        default => 'primary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->tooltip(fn ($record) => $record->description) // Shows full text on hover
                    ->wrap()
                    ->limit(50)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A') // Improved date format
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('to')
                            ->label('To Date'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($query) => $query->whereDate('created_at', '>=', $data['from']))
                        ->when($data['to'], fn ($query) => $query->whereDate('created_at', '<=', $data['to']))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListLeadActivities::route('/'),
            'create' => Pages\CreateLeadActivity::route('/create'),
            'edit' => Pages\EditLeadActivity::route('/{record}/edit'),
        ];
    }
}
