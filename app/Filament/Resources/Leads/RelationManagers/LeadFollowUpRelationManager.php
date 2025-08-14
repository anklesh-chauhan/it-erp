<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use App\Models\FollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\LeadActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadFollowUpRelationManager extends RelationManager
{
    protected static string $relationship = 'followUps';
    protected static ?string $title = 'Follow-ups';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id()) // Automatically sets the current logged-in user
                    ->required(),

                Forms\Components\Hidden::make('lead_id')
                    ->default(fn (callable $get) => $get('record.id')),

                Forms\Components\DateTimePicker::make('follow_up_date')
                        ->required()
                        ->label('Follow-up Date'),

                Forms\Components\Select::make('to_whom')
                    ->options(function () {
                        $lead = $this->getOwnerRecord();
                        if ($lead) {
                            return \App\Models\ContactDetail::where('company_id', $lead->company_id)
                                ->get()
                                ->mapWithKeys(fn ($contact) => [
                                    $contact->id => "{$contact->first_name} {$contact->last_name}"
                                ]);
                        }
                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->label('To Whom'),

                Forms\Components\Textarea::make('interaction')
                    ->label('Interaction')
                    ->rows(3)
                    ->nullable(),

                Forms\Components\Textarea::make('outcome')
                    ->label('Outcome')
                    ->rows(2)
                    ->nullable(),

                Forms\Components\Select::make('follow_up_media_id')
                    ->relationship('media', 'name')
                    ->nullable(),

                Forms\Components\Select::make('follow_up_result_id')
                    ->label('Result')
                    ->relationship('result', 'name')
                    ->nullable(),

                Forms\Components\DateTimePicker::make('next_follow_up_date')
                    ->label('Next Follow-up Date')
                    ->nullable(),

                Forms\Components\Select::make('follow_up_priority_id')
                    ->relationship('priority', 'name')
                    ->nullable(),

                Forms\Components\Select::make('follow_up_status_id')
                    ->relationship('status', 'name')
                    ->required(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Lead Follow-ups')
            ->columns([
                Tables\Columns\TextColumn::make('follow_up_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('media.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('contactDetail.full_name')
                    ->label('To Whom')
                    ->tooltip(fn ($record) => $record->contactDetail
                        ? "Email: {$record->contactDetail->email}\nPhone: {$record->contactDetail->mobile_number}"
                        : "No contact details available.")
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('contactDetail', function ($q) use ($search) {
                            $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    }),

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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (RelationManager $livewire, array $data) {
                        $lead = $livewire->getOwnerRecord(); // Get the lead (parent record)
                        $followUp = FollowUp::latest()->first(); // Get the latest follow-up

                        if ($lead && $followUp) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Follow-up Created',
                                'description' => "A new follow-up has been added on " .
                                    \Carbon\Carbon::parse($followUp->followup_date)->format('d M Y, h:i A')  . " using " .
                                    ($followUp->media->name ?? 'Unknown') . " as media type.",
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (RelationManager $livewire, array $data) {
                        $lead = $livewire->getOwnerRecord(); // Get the lead (parent record)
                        $followUp = FollowUp::latest()->first(); // Get the latest follow-up

                        if ($lead && $followUp) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Follow-up Updated',
                                'description' => "A new follow-up has been added on " .
                                    \Carbon\Carbon::parse($followUp->followup_date)->format('d M Y, h:i A')  . " using " .
                                    ($followUp->media->name ?? 'Unknown') . " as media type.",
                            ]);
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (RelationManager $livewire, array $data) {
                        $lead = $livewire->getOwnerRecord(); // Get the lead (parent record)
                        $followUp = FollowUp::latest()->first(); // Get the latest follow-up

                        if ($lead && $followUp) {
                            LeadActivity::create([
                                'lead_id' => $lead->id,
                                'user_id' => Auth::id(),
                                'activity_type' => 'Follow-up Deleted',
                                'description' => "A new follow-up has been added on " .
                                    \Carbon\Carbon::parse($followUp->followup_date)->format('d M Y, h:i A')  . " using " .
                                    ($followUp->media->name ?? 'Unknown') . " as media type.",
                            ]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
