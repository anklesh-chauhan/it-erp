<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use App\Models\ApprovalStep;
use App\Services\Approval\ApprovalService;
use Illuminate\Support\Facades\Auth;

class MyApprovals extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'My Approvals';
    protected string $view = 'filament.pages.my-approvals';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () =>
                ApprovalStep::query()
                    ->where('assigned_user_id', Auth::id())
                    ->where('status', 'pending')
                    ->with([
                        'approval.approvable',
                        'approval.requestedBy',
                    ])
            )
            ->columns([
                TextColumn::make('approval.approvable_type')
                    ->label('Module')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                TextColumn::make('approval.approvable.id')
                    ->label('Reference'),

                TextColumn::make('approval.requestedBy.name')
                    ->label('Requested By'),

                TextColumn::make('approval.created_at')
                    ->label('Requested On')
                    ->dateTime(),
            ])
            ->actions([
                Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (ApprovalStep $record) =>
                        app(ApprovalService::class)
                            ->approve($record->approval, Auth::id())
                    ),

                Action::make('reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('comments')
                            ->required(),
                    ])
                    ->action(fn (ApprovalStep $record, array $data) =>
                        app(ApprovalService::class)
                            ->reject(
                                $record->approval,
                                Auth::id(),
                                $data['comments']
                            )
                    ),
            ])
            ->emptyStateHeading('No pending approvals')
            ->emptyStateDescription('You currently have no approvals waiting for you.');
    }
}
