<?php

namespace App\Filament\Resources\Approvals\Tables;

use App\Filament\Actions\ApprovalAction;

use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Tables\Actions;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use League\Uri\Components\Component;
use Livewire\Component as LivewireComponent;
use Termwind\Components\Li;
use Illuminate\Database\Eloquent\Builder;

class ApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('approval_status', 'asc')
            ->columns([
                TextColumn::make('approvable_type')
                    ->label('Module')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('approvable_id')
                    ->label('Record ID')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->getDocumentNumber() ?? $state
                    ),

                TextColumn::make('requester.name')
                    ->label('Requested By')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('approval_status')
                    ->label('Approval Status')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->badge(),

                Tables\Columns\TextColumn::make('steps.level')
                    ->label('Level')
                    ->formatStateUsing(fn ($state) => 'Level ' . $state)
                    ->listWithLineBreaks()
                    ->sortable(),

                Tables\Columns\TextColumn::make('steps.approval_status')
                    ->label('Approver Status')
                    ->listWithLineBreaks()
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('steps.approver.name')
                    ->label('Approver')
                    ->listWithLineBreaks(),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('approval_status')
                    ->options([
                    'draft' => 'Draft',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    ApprovalAction::make(),

                    Action::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->schema([
                            Textarea::make('comments')->label('Comments')
                        ])
                        ->visible(fn ($record) =>
                            $record->steps()
                                ->where('approver_id', Auth::id())
                                ->where('approval_status', 'draft')
                                ->exists()
                        )
                        ->action(function ($record, array $data, LivewireComponent $livewire) {
                            app(\App\Services\Approval\ApprovalService::class)
                                ->approveStepByUser($record, Auth::id(), $data['comments'] ?? null);

                            $livewire->dispatch('refresh-sidebar');

                            Notification::make()
                                ->success()
                                ->title('Approved successfully!')
                                ->send();
                        }),

                    Action::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->schema([
                            Textarea::make('comments')->label('Comments'),
                        ])
                        ->visible(fn ($record) =>
                            $record->steps()
                                ->where('approver_id', Auth::id())
                                ->where('approval_status', 'draft')
                                ->exists()
                        )
                        ->action(function ($record, array $data, LivewireComponent $livewire) {
                            app(\App\Services\Approval\ApprovalService::class)
                                ->rejectStepByUser($record, Auth::id(), $data['comments'] ?? null);

                            $livewire->dispatch('refresh-sidebar');

                            Notification::make()
                                ->danger()
                                ->title('Rejected')
                                ->send();
                        }),

                ])
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('bulkApprove')
                        ->label('Approve Selected')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, LivewireComponent $livewire) {

                            $service = app(\App\Services\Approval\ApprovalService::class);
                            $userId = Auth::id();

                            foreach ($records as $record) {
                                /** @var \App\Models\Approval $record */
                                $step = $record->steps()
                                    ->where('approver_id', $userId)
                                    ->where('approval_status', 'draft')
                                    ->first();

                                if ($step) {
                                    $service->approveStepByUser($record, $userId, null);
                                }
                            }

                            $livewire->dispatch('refresh-sidebar');

                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Selected approvals approved!')
                                ->send();
                        }),

                    BulkAction::make('bulkReject')
                        ->label('Reject Selected')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Collection $records, LivewireComponent $livewire) {

                            $service = app(\App\Services\Approval\ApprovalService::class);
                            $userId = Auth::id();

                            foreach ($records as $record) {
                                /** @var \App\Models\Approval $record */
                                $step = $record->steps()
                                    ->where('approver_id', $userId)
                                    ->where('approval_status', 'draft')
                                    ->first();

                                if ($step) {
                                    $service->rejectStepByUser($record, $userId, null);
                                }
                            }

                            $livewire->dispatch('refresh-sidebar');

                            Notification::make()
                                ->danger()
                                ->title('Selected approvals rejected!')
                                ->send();
                        }),
                ]),
            ]);
    }
}
