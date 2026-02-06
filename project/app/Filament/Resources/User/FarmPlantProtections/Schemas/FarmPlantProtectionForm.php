<?php

namespace App\Filament\Resources\User\FarmPlantProtections\Schemas;

use App\Enums\CostType;
use App\Enums\DefinedCodifiers;
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
                    ->label('Īpašnieks'),
                Textarea::make('description')
                    ->label('Apraksts'),
                Fieldset::make()
                    ->schema([
                        TextInput::make('costs')
                            ->numeric()
                            ->required()
                            ->label('Izmaksas'),
                        Select::make('cost_type')
                            ->label('Izmaksu tips')
                            ->required()
                            ->default(CostType::EUR_HOUR)
                            ->options(CostType::class)
                            ->searchable(),
                        Hidden::make('owner_id')
                            ->default(auth()->id())
                    ]),
                Hidden::make('owner_id')
                    ->default(auth()->id())
            ]);
    }
}
