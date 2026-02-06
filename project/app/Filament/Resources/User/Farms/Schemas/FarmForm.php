<?php

namespace App\Filament\Resources\User\Farms\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FarmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpan('full')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nosaukums')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nosaukums')
                            ->required(),
                        Hidden::make('owner_id')
                            ->default(auth()->id())
                    ])
            ]);
    }
}
