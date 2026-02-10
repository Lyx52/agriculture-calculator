<?php

namespace App\Filament\Resources\User\FarmFertilizers\Schemas;

use App\Enums\CostType;
use App\Enums\UnitType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class FarmFertilizerForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateName = static function (Set $set, Get $get, $state, TextInput $component) {
            $values = [
                'value_caco3',
                'value_zn',
                'value_mo',
                'value_mn',
                'value_fe',
                'value_cu',
                'value_co',
                'value_b',
                'value_s',
                'value_na',
                'value_mg',
                'value_ca',
                'value_k2o',
                'value_p2o5',
                'value_n'
            ];
            $rawState = [];
            foreach ($values as $key) {
                $rawState[$key] = $get($key);
            }
            $rawState[$component->getName()] = $state;
            $label = generate_fertilizer_name($rawState);
            $set('name', $label);
        };
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                TextInput::make('name')
                    ->label('Nosaukums')
                    ->required(),
                TextInput::make('company')
                    ->label('Īpašnieks'),
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
                Section::make('Darbīgo vielu saturs')
                    ->inlineLabel()
                    ->schema([
                        TextInput::make('value_caco3')->live()->afterStateUpdated($updateName)->label("CaCO3")->default(0),
                        TextInput::make('value_zn')->live()->afterStateUpdated($updateName)->label("Zn")->default(0),
                        TextInput::make('value_mo')->live()->afterStateUpdated($updateName)->label("Mo")->default(0),
                        TextInput::make('value_mn')->live()->afterStateUpdated($updateName)->label("Mn")->default(0),
                        TextInput::make('value_fe')->live()->afterStateUpdated($updateName)->label("Fe")->default(0),
                        TextInput::make('value_cu')->live()->afterStateUpdated($updateName)->label("Cu")->default(0),
                        TextInput::make('value_co')->live()->afterStateUpdated($updateName)->label("Co")->default(0),
                        TextInput::make('value_b')->live()->afterStateUpdated($updateName)->label("B")->default(0),
                        TextInput::make('value_s')->live()->afterStateUpdated($updateName)->label("S")->default(0),
                        TextInput::make('value_na')->live()->afterStateUpdated($updateName)->label("Na")->default(0),
                        TextInput::make('value_mg')->live()->afterStateUpdated($updateName)->label("Mg")->default(0),
                        TextInput::make('value_ca')->live()->afterStateUpdated($updateName)->label("Ca")->default(0),
                        TextInput::make('value_k2o')->live()->afterStateUpdated($updateName)->label("K2O")->default(0),
                        TextInput::make('value_p2o5')->live()->afterStateUpdated($updateName)->label("P2O5")->default(0),
                        TextInput::make('value_n')->live()->afterStateUpdated($updateName)->label("N")->default(0),
                    ]),
                Hidden::make('owner_id')
                    ->default(auth()->id()),
            ]);
    }
}
