<?php

namespace App\Filament\Resources\Admin\Codifiers\Schemas;

use App\Models\Codifier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CodifierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->schema([
                TextInput::make('name')
                    ->label('Nosaukums')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kods')
                    ->required()
                    ->unique('codifiers', 'code', ignoreRecord: true)
                    ->maxLength(256),
                Select::make('parent_id')
                    ->label('Grupa')
                    ->options(Codifier::pluck('name', 'id'))
                    ->searchable(),
                TextArea::make('value')
                    ->label('Vērtība')
                    ->rows(12)
                    ->json()
            ]);
    }
}
