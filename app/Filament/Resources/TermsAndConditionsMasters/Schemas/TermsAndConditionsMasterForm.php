<?php

namespace App\Filament\Resources\TermsAndConditionsMasters\Schemas;

use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;


class TermsAndConditionsMasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Select::make('document_type')
                        ->label('Document Type')
                        ->options([
                            'quote' => 'Quote',
                            'salesorder' => 'Sales Order',
                            'salesinvoice' => 'Sales Invoice',
                        ])
                        ->required()
                        ->native(false),

                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_default')
                            ->label('Set as Default')
                            ->inline(false),
                    ])->columnSpanFull(),
                    RichEditor::make('content')
                            ->label('Content')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'orderedList',
                                'bulletList',
                                'h2',
                                'h3',
                                'undo',
                                'redo',
                            ])
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
            ]);
    }
}
