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
use Filament\Forms\Components\Tabs; // Import Tabs
use Filament\Forms\Components\Fieldset; // Import Fieldset
use Filament\Forms\Components\Grid; // Import Grid for better layout
use Filament\Tables\Actions\ViewAction; // Import ViewAction

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationGroup = 'Global Config';
    protected static ?string $navigationLabel = 'Organization Profile';
    protected static ?int $navigationSort = 1000;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // More relevant icon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Organization Details')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus() // Focus on this field when the form loads
                                    ->placeholder('e.g., Acme Corporation'),
                                Forms\Components\TextInput::make('display_name')
                                    ->maxLength(255)
                                    ->helperText('Optional: A user-friendly name for display purposes.')
                                    ->placeholder('e.g., Acme Co.'),
                                Forms\Components\FileUpload::make('logo')
                                    ->image()
                                    ->directory('organization-logos')
                                    ->maxSize(2048) // 2MB max
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']) // Add webp
                                    ->disk('public')
                                    ->preserveFilenames()
                                    ->imagePreviewHeight('150') // Adjust preview size
                                    ->columnSpanFull(), // Make logo span full width for better display
                                Forms\Components\Textarea::make('description')
                                    ->rows(3) // Make textarea a bit taller
                                    ->columnSpanFull()
                                    ->placeholder('A brief description of the organization.'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'pending' => 'Pending',
                                    ])
                                    ->required()
                                    ->default('active')
                                    ->native(false), // Use a custom select UI
                                Forms\Components\Select::make('industry_type_id')
                                    ->relationship('industryType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false) // Use a custom select UI
                                    ->placeholder('Select an industry'),
                                Forms\Components\Select::make('parent_organization_id')
                                    ->relationship('parentOrganization', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false) // Use a custom select UI
                                    ->placeholder('Select a parent organization'),
                                Forms\Components\DatePicker::make('founded_at')
                                    ->label('Founded On'),
                                Forms\Components\TextInput::make('size')
                                    ->maxLength(255)
                                    ->placeholder('e.g., 50-200 employees'),
                                Forms\Components\TextInput::make('annual_revenue')
                                    ->numeric()
                                    ->prefix('INR')
                                    ->minValue(0),
                                    // ->mask(RawJs::make('$money($event.target.value)')), // Use RawJs for currency masking
                                Forms\Components\TextInput::make('operation_hours')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Mon-Fri, 9 AM - 5 PM'),
                                Forms\Components\Select::make('timezone')
                                    ->options(\DateTimeZone::listIdentifiers())
                                    ->searchable()
                                    ->preload()
                                    ->native(false) // Use a custom select UI
                                    ->placeholder('Select timezone'),
                                Forms\Components\TextInput::make('language')
                                    ->maxLength(255)
                                    ->placeholder('e.g., English'),
                            ]),

                        Tabs\Tab::make('Contact Information')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\TextInput::make('website')
                                    ->url()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-link') // Add a link icon
                                    ->placeholder('e.g., https://www.example.com'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-envelope')
                                    ->placeholder('e.g., info@example.com'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-phone')
                                    ->placeholder('e.g., +1234567890'),
                                Forms\Components\TextInput::make('fax')
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-printer')
                                    ->placeholder('e.g., +1234567891'),
                                Fieldset::make('Contact Person Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_person')
                                            ->maxLength(255)
                                            ->placeholder('e.g., John Doe'),
                                        Forms\Components\TextInput::make('contact_person_email')
                                            ->email()
                                            ->maxLength(255)
                                            ->placeholder('e.g., john.doe@example.com'),
                                        Forms\Components\TextInput::make('contact_person_phone')
                                            ->tel()
                                            ->maxLength(255)
                                            ->placeholder('e.g., +1987654321'),
                                    ])->columns(1), // Adjust columns for fieldset
                            ]),

                        Tabs\Tab::make('Legal & Registration')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                Forms\Components\TextInput::make('legal_name')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Acme Corporation Ltd.'),
                                Forms\Components\TextInput::make('registration_number')
                                    ->maxLength(255)
                                    ->placeholder('e.g., ABC-12345'),
                                Forms\Components\TextInput::make('GST Number') // Consider renaming to 'GST_number' for consistency or use label()
                                    ->maxLength(255)
                                    ->placeholder('e.g., 22AAAAA0000A1Z2'),
                                Forms\Components\DatePicker::make('registration_date'),
                                Forms\Components\TextInput::make('legal_status')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Public Company, Private Limited'),
                            ]),

                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-hashtag')
                            ->schema([
                                Forms\Components\TextInput::make('linkedin_url')
                                    ->url()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-link')
                                    ->placeholder('e.g., https://linkedin.com/company/acme'),
                                Forms\Components\TextInput::make('twitter_url')
                                    ->url()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-link')
                                    ->placeholder('e.g., https://twitter.com/acmecorp'),
                                Forms\Components\TextInput::make('facebook_url')
                                    ->url()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-link')
                                    ->placeholder('e.g., https://facebook.com/acmecorp'),
                                Forms\Components\TextInput::make('instagram_url')
                                    ->url()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-link')
                                    ->placeholder('e.g., https://instagram.com/acmecorp'),
                            ]),

                        Tabs\Tab::make('Metadata & Audit')
                            ->icon('heroicon-o-server')
                            ->schema([
                                Forms\Components\KeyValue::make('metadata')
                                    ->keyLabel('Key') // More generic label
                                    ->valueLabel('Value')
                                    ->columnSpanFull()
                                    ->helperText('Additional key-value pairs for organizational data.'),
                                Forms\Components\Select::make('created_by')
                                    ->relationship('creator', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled() // Typically, this field is not user-editable
                                    ->placeholder('Select creator'),
                                Forms\Components\Select::make('updated_by')
                                    ->relationship('updater', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled() // Typically, this field is not user-editable
                                    ->placeholder('Select updater'),
                            ]),
                    ])->columnSpanFull(), // Make tabs span full width
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->disk('public')
                    ->circular() // Make logo circular for a nicer look
                    ->toggleable(isToggledHiddenByDefault: true), // Allow users to hide/show this column
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable() // Enable sorting
                    ->toggleable()
                    ->copyable() // Allow copying the name
                    ->label('Organization Name'), // Custom label
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Display Name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-envelope') // Add an icon
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-phone') // Add an icon
                    ->copyable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->website) // Make it a clickable URL
                    ->openUrlInNewTab() // Open in new tab
                    ->icon('heroicon-o-link'),
                Tables\Columns\TextColumn::make('industryType.name') // Display related industry name
                    ->label('Industry')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge() // Display status as a badge
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true) // Hidden by default
                    ->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true) // Hidden by default
                    ->label('Last Updated'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ])
                    ->native(false) // Use a custom select UI
                    ->placeholder('Filter by Status'),
                Tables\Filters\SelectFilter::make('industry_type_id')
                    ->relationship('industryType', 'name')
                    ->native(false) // Use a custom select UI
                    ->placeholder('Filter by Industry'),
                Tables\Filters\TrashedFilter::make(), // Add soft delete filter
            ])
            ->actions([
                ViewAction::make(), // Add a view action
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Add direct delete action
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(), // For soft deletes
                    Tables\Actions\RestoreBulkAction::make(), // For soft deletes
                ]),
            ])
            ->defaultSort('name', 'asc'); // Default sort by name ascending
    }

    public static function getRelations(): array
    {
        return [
            // Consider renaming 'AccountMasterResource' to something more general or ensuring it's correct.
            // If AddressesRelationManager only manages addresses for 'AccountMaster', you might need a dedicated
            // OrganizationAddressesRelationManager if organizations have their own addresses.
            AccountMasterResource\RelationManagers\AddressesRelationManager::class
            // Add other relation managers here if needed, e.g., UsersRelationManager, DepartmentsRelationManager
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