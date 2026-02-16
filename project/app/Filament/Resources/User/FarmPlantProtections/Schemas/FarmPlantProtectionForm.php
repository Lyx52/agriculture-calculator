<?php

namespace App\Filament\Resources\User\FarmPlantProtections\Schemas;

use App\Enums\CostType;
use App\Enums\DefinedCodifiers;
use App\Enums\UnitType;
use App\Models\Codifier;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class FarmPlantProtectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                TextInput::make('name')
                    ->label('Nosaukums')
                    ->required(),
                Select::make('protection_category_codes')
                    ->required()
                    ->multiple()
                    ->options(Codifier::whereParentCode(DefinedCodifiers::CROP_PROTECTION_USAGE)->pluck('name', 'code'))
                    ->searchable()
                    ->label('Kategorijas'),
                TextInput::make('company')
                    ->label('Ražotājs'),
                Textarea::make('description')
                    ->label('Apraksts'),
                Fieldset::make()
                    ->schema([
                        TextInput::make('cost_per_unit')
                            ->numeric()
                            ->required()
                            ->label('Izmaksas uz mērvienību')
                            ->postfix('EUR'),
                        Select::make('unit_type')
                            ->label('Mērvienība')
                            ->required()
                            ->default(UnitType::KILOGRAMS)
                            ->options(UnitType::class)
                            ->native(false),
                    ]),
                Hidden::make('owner_id')
                    ->default(auth()->id())
            ]);
    }
}
