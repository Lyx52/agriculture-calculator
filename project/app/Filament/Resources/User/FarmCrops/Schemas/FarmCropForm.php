<?php

namespace App\Filament\Resources\User\FarmCrops\Schemas;

use App\Enums\CostType;
use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class FarmCropForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                TextInput::make('name')
                    ->label('Šķirnes nosaukums')
                    ->required(),
                Select::make('crop_species_code')
                    ->label('Suga')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::CROP_SPECIES)->pluck('name', 'code'))
                    ->searchable(),
                Fieldset::make()
                    ->schema([
                        TextInput::make('costs')
                            ->numeric()
                            ->required()
                            ->label('Izmaksas'),
                        Select::make('cost_type')
                            ->label('Izmaksu tips')
                            ->required()
                            ->default(CostType::EUR_HECTARES)
                            ->options(CostType::amountOptions())
                            ->searchable(),
                    ]),
                Hidden::make('owner_id')
                    ->default(auth()->id())
            ]);
    }
}
