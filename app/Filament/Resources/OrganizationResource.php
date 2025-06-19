<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationGroup = 'Global Config';
    protected static ?string $navigationLabel = 'Organization Profile';
    protected static ?int $navigationSort = 1000;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('display_name')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('organization-logos')
                    ->maxSize(2048) // 2MB max
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->disk('public')
                    ->preserveFilenames(),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('founded_at'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fax')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('legal_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('GST Number')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('registration_date'),
                Forms\Components\TextInput::make('legal_status')
                    ->maxLength(255),
                Forms\Components\Select::make('parent_organization_id')
                    ->relationship('parentOrganization', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('size')
                    ->maxLength(255),
                Forms\Components\TextInput::make('annual_revenue')
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
                Forms\Components\TextInput::make('operation_hours')
                    ->maxLength(255),
                Forms\Components\Select::make('timezone')
                    ->options(\DateTimeZone::listIdentifiers())
                    ->searchable(),
                Forms\Components\TextInput::make('language')
                    ->maxLength(255),
                Forms\Components\TextInput::make('linkedin_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('twitter_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('facebook_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instagram_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('updated_by')
                    ->relationship('updater', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\KeyValue::make('metadata')
                    ->keyLabel('Title')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('industry_type_id')
                    ->relationship('industryType', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo')
                    ->disk('public')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ]),
                Tables\Filters\SelectFilter::make('industry_type_id')
                    ->relationship('industryType', 'name'),
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
            AccountMasterResource\RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
