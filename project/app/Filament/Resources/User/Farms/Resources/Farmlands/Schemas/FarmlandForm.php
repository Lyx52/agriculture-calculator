<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Schemas;

use App\Models\Codifier;
use App\Enums\DefinedCodifiers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FarmlandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Select::make('crop_id')
                    ->label('Kūltūraugs')
                    ->options(user()->crops()->get()->pluck('cropName', 'id'))
                    ->searchable(),
                Select::make('agriculture_technology_code')
                    ->required()
                    ->label('Lauksaimniecības tehnoloģija')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::AGRICULTURE_TECHNOLOGY)->pluck('name', 'code'))
                    ->searchable(),
                TextInput::make('name')
                    ->label('Nosaukums/Klasifikācija')
                    ->required(),
                TextInput::make('area')
                    ->label('Zemes platība')
                    ->postfix('ha')
                    ->required()
                    ->numeric(),
            ]);
    }
}
