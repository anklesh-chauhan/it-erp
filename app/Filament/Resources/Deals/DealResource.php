<?php

namespace App\Filament\Resources\Deals;

use App\Traits\HasSafeGlobalSearch;
use App\Models\DealStage;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Deals\Pages\ListDeals;
use App\Filament\Resources\Deals\Pages\CreateDeal;
use App\Filament\Resources\Deals\Pages\EditDeal;
use App\Filament\Resources\DealResource\Pages;
use App\Filament\Resources\DealResource\RelationManagers;
use App\Models\Deal;
use App\Models\TypeMaster;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasCustomerInteractionFields;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Filament\GlobalSearch\Actions\Action as GlobalSearchAction;



class DealResource extends Resource
{
    use HasCustomerInteractionFields;
    use HasSafeGlobalSearch;

    protected static ?string $model =  Deal::class;

    /**
     * @var DealStage
     */
    protected static ?string $statusModel = DealStage::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'reference_code';

    protected static int $globalSearchResultsLimit = 10;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...self::getCommonFormSchema(),

                TextInput::make('deal_name')
                    ->required()
                    ->maxLength(255),

                Select::make('type_id')
                    ->label('Type')
                    ->options(fn () => TypeMaster::ofType(Deal::class)
                        ->pluck('name', 'id')->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Hidden::make('type_type')
                    ->default(Deal::class),

                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('expected_revenue')
                    ->required()
                    ->numeric(),
                DatePicker::make('expected_close_date')
                    ->required(),
                TextInput::make('lead_source_id')
                    ->numeric(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_code')
                    ->label('Ref Code')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('deal_name')
                    ->label('Deal')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->colors([
                        'primary',
                        'warning' => 'Pending',
                        'success' => 'Won',
                        'danger' => 'Lost',
                    ])
                    ->sortable()
                    ->badge(),


                TextColumn::make('amount')
                    ->money('usd', true)
                    ->sortable(),

                TextColumn::make('expected_revenue')
                    ->label('Expected Revenue')
                    ->money('usd', true)
                    ->sortable(),

                TextColumn::make('expected_close_date')
                    ->label('Close Date')
                    ->date()
                    ->sortable(),

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
                SelectFilter::make('owner_id')
                    ->label('Owner')
                    ->relationship('owner', 'name')
                    ->searchable(),

                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(fn () => static::$statusModel::pluck('name', 'id')->toArray()) // ✅ safe
                    ->searchable(),


                Filter::make('transaction_date')
                    ->label('Transaction Date Range')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('transaction_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('transaction_date', '<=', $data['until']));
                    }),

                Filter::make('expected_close_date')
                    ->label('Expected Close Date Range')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('expected_close_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('expected_close_date', '<=', $data['until']));
                    }),

                Filter::make('amount_range')
                    ->label('Amount Range')
                    ->schema([
                        TextInput::make('min')->numeric(),
                        TextInput::make('max')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['min'], fn ($q) => $q->where('amount', '>=', $data['min']))
                            ->when($data['max'], fn ($q) => $q->where('amount', '<=', $data['max']));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->paginated([25, 50, 100]);
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
            'index' => ListDeals::route('/'),
            'create' => CreateDeal::route('/create'),
            'edit' => EditDeal::route('/{record}/edit'),
        ];
    }
}
