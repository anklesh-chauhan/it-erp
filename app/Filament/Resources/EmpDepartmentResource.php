<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpDepartmentResource\Pages;
use App\Filament\Resources\EmpDepartmentResource\RelationManagers;
use App\Models\EmpDepartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EmpDepartmentResource extends Resource
{
    protected static ?string $model = EmpDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'HR & Organization';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Department Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('department_name')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('department_code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('organizational_unit_id')
                            ->relationship('organizationalUnit', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('department_head_id')
                            ->relationship('head', 'first_name')
                            ->searchable()
                            ->preload()
                            ->label('Department Head')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->nullable(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\Textarea::make('remark')
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('created_by_user_id')
                            ->default(Auth::id()),
                        Forms\Components\Hidden::make('updated_by_user_id')
                            ->default(Auth::id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizationalUnit.name')
                    ->label('OU')
                    ->sortable(),
                Tables\Columns\TextColumn::make('head.full_name')
                    ->label('Department Head')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('organizational_unit_id')
                    ->relationship('organizationalUnit', 'name')
                    ->label('Organizational Unit'),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query) => $query->where('is_active', true))
                    ->label('Active Departments'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GradesRelationManager::class,
            RelationManagers\DivisionsRelationManager::class,
            RelationManagers\JobTitlesRelationManager::class,
            RelationManagers\EmploymentDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpDeparments::route('/'),
            'create' => Pages\CreateEmpDeparment::route('/create'),
            'view' => Pages\ViewEmpDeparment::route('/{record}'),
            'edit' => Pages\EditEmpDeparment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
