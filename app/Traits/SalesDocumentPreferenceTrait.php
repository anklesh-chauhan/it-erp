<?php

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\SalesDocumentPreference;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

trait SalesDocumentPreferenceTrait
{
    public static function getSalesDocumentPreferenceAction(): Action
    {
        return Action::make('sales_document_preferences')
            ->label('Preferences')
            ->icon('heroicon-o-cog-6-tooth')
            ->modalHeading('Sales Document Preferences')
            ->form([
                Select::make('discount_level')
                    ->label('Discount Mode')
                    ->options([
                        'none' => 'No Discount',
                        'line_item' => 'Line Item Discount',
                        'transaction' => 'Transaction Discount',
                        'both' => 'Both Line Item and Transaction Discount',
                    ])
                    ->required()
                    ->default(fn () => SalesDocumentPreference::first()?->discount_level ?? 'none'),
            ])
            ->action(function (array $data, $livewire) {
                $preference = SalesDocumentPreference::firstOrCreate([], [
                    'user_id' => Auth::id(),
                ]);

                $preference->update([
                    'discount_level' => $data['discount_level'],
                ]);

                Notification::make()
                    ->title('Preferences updated successfully')
                    ->success()
                    ->send();

                $livewire->redirect(request()->header('Referer')); // Refresh the page
            })
            ->modalSubmitActionLabel('Save')
            ->modalCancelActionLabel('Cancel');
    }
}
