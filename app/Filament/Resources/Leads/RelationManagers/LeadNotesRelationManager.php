<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Attachment;
use App\Models\LeadNote;
use App\Models\LeadActivity;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadNotesRelationManager extends RelationManager
{
    protected static string $relationship = 'leadNotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lead_id')
                    ->label('Lead ID')
                    ->relationship('lead', 'id')
                    ->default(fn ($record) => $record?->lead_id)
                    ->hidden() // ✅ Completely hide the field
                    ->required(),

                Select::make('user_id')
                    ->label('Created By')
                    ->relationship('user', 'name')
                    ->default(fn () => Auth::id())
                    ->hidden(), // ✅ Completely hide the field
                Textarea::make('note')
                    ->label('Note')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('file_path')
                    ->label('Attachments')
                    ->multiple()
                    ->directory('lead-notes') // Ensure files are stored in storage/app/public/lead-notes
                    ->storeFiles()
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note')
            ->columns([
                TextColumn::make('user.name')->label('Created By')->sortable(),
                TextColumn::make('note')->label('Note')->limit(50),
                TextColumn::make('attachments')
                    ->label('Attachments')
                    ->formatStateUsing(function ($record) {
                        if ($record->attachments->isEmpty()) {
                            return 'No attachments';
                        }

                        return $record->attachments->map(function ($attachment) {
                            // Log the file path for debugging
                            Log::info('Generating attachment link', [
                                'file_path' => $attachment->file_path,
                                'full_url' => asset('storage/' . $attachment->file_path),
                            ]);

                            // Check if the file exists
                            $filePath = storage_path('app/public/' . $attachment->file_path);
                            if (!file_exists($filePath)) {
                                Log::warning('Attachment file not found', [
                                    'file_path' => $attachment->file_path,
                                    'full_path' => $filePath,
                                ]);
                                return "{$attachment->file_name} (File not found)";
                            }

                            // Generate the download link with event propagation stopped
                            $url = asset('storage/' . $attachment->file_path);
                            return "<a href='{$url}' target='_blank' download onclick='event.stopPropagation();'>{$attachment->file_name}</a>";
                        })->implode(', ');
                    })
                    ->html()
                    ->extraAttributes(['class' => 'no-row-click']),
                TextColumn::make('created_at')->label('Created At')->sortable()->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')->label('Recent Notes')
                    ->query(fn ($query) => $query->orderByDesc('created_at')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->before(function (array $data) {
                    $data['user_id'] = Auth::id();
                    return $data;
                })
                ->after(function (LeadNote $record, array $data, RelationManager $livewire) {

                    $lead = $livewire->getOwnerRecord();
                        if ($lead) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Note Added',
                                'description' => "Note '{$record->note}' has been added to the lead" .
                                    (isset($data['file_path']) && count($data['file_path']) > 0 ? ' with attachments' : ''),
                            ]);
                        }

                    // Check for uploaded files in $data['file_path']
                    if (isset($data['file_path']) && is_array($data['file_path'])) {
                        foreach ($data['file_path'] as $filePath) {
                            try {
                                Attachment::create([
                                    'file_name' => basename($filePath),
                                    'file_path' => $filePath,
                                    'file_type' => pathinfo($filePath, PATHINFO_EXTENSION),
                                    'attachable_id' => $record->id,
                                    'attachable_type' => LeadNote::class,
                                    'description' => null, // Optional: Add a description if needed
                                ]);
                                Log::info('Attachment created for LeadNote ID: ' . $record->id, ['file_path' => $filePath]);
                            } catch (\Exception $e) {
                                Log::error('Failed to create attachment for LeadNote ID: ' . $record->id, [
                                    'error' => $e->getMessage(),
                                    'file_path' => $filePath,
                                ]);
                            }
                        }
                    } else {
                        Log::warning('No file_path found in data for LeadNote ID: ' . $record->id);
                    }
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (LeadNote $record, array $data, RelationManager $livewire) {
                        $lead = $livewire->getOwnerRecord();
                        if ($lead) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Note Updated',
                                'description' => "Note '{$record->note}' has been updated",
                            ]);
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (LeadNote $record, RelationManager $livewire) {
                        $lead = $livewire->getOwnerRecord();
                        if ($lead) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Note Deleted',
                                'description' => "Note '{$record->note}' has been removed from the lead",
                            ]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function (array $records, RelationManager $livewire) {
                            $lead = $livewire->getOwnerRecord();
                            if ($lead) {
                                foreach ($records as $record) {
                                    LeadActivity::create([
                                        'lead_id' => $lead->id,
                                        'user_id' => Auth::id(),
                                        'activity_type' => 'Note Deleted',
                                        'description' => "Note '{$record->note}' has been removed from the lead",
                                    ]);
                                }
                            }
                        }),
                ]),
            ]);
    }

}
