<?php

namespace App\Filament\Resources\Admin\AgricultureEquipment\Schemas;

use App\Enums\DefinedCodifiers;
use App\Enums\DefinedEquipmentTypes;
use App\Enums\DriveType;
use App\Models\Codifier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AgricultureEquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                TextInput::make('manufacturer')
                    ->label('Marka')
                    ->required(),
                TextInput::make('model')
                    ->label('Modelis')
                    ->required(),
                Select::make('equipment_category_code')
                    ->live()
                    ->required()
                    ->label('Tehnikas kategorija')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE)->pluck('name', 'code'))
                    ->searchable(),
                Select::make('equipment_sub_category_code')
                    ->visible(fn(Get $get) => !empty($get('equipment_category_code')) && Codifier::whereParentCode($get('equipment_category_code'))->count() > 0)
                    ->required()
                    ->live()
                    ->label('Tehnikas nosaukums')
                    ->options(fn(Get $get) => empty($get('equipment_category_code')) ? Codifier::whereParentOfParentCode(DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE)->pluck('name', 'code') : Codifier::whereParentCode($get('equipment_category_code'))->pluck('name', 'code'))
                    ->searchable(),
                TextInput::make('price')
                    ->label('Cena')
                    ->numeric()
                    ->required()
                    ->postfix('EUR'),
                Section::make("Specifikācija")
                    ->visible(fn(Get $get) => !empty($get('equipment_category_code')))
                    ->schema(fn(Get $get) => self::buildSpecificationForm($get('equipment_category_code'))),
            ]);
    }

    // All parameters
    // "is_self_propelled"
    // "drive_type"
    // "working_width"
    // "weight"
    // "required_power"
    // "power"
    // "working_speed"
    // "specific_fuel_consumption"
    public static function buildSpecificationForm(string|null $categoryCode): array {
        return match($categoryCode) {
            DefinedEquipmentTypes::TRACTOR->value => self::buildTractorSpecifications(),
            default => self::buildBaseSpecifications(),
        };
    }

    public static function buildBaseSpecifications(): array {
        return [
            TextInput::make('required_power')
                ->visible(fn(Get $get) => empty($get('is_self_propelled')))
                ->label('Nepieciešamā')
                ->numeric()
                ->required()
                ->postfix('kW'),
            TextInput::make('power')
                ->visible(fn(Get $get) => $get('is_self_propelled'))
                ->label('Jauda')
                ->numeric()
                ->required()
                ->postfix('kW'),
            TextInput::make('specific_fuel_consumption')
                ->visible(fn(Get $get) => $get('is_self_propelled'))
                ->label('Īpatnējais degvielas patēriņš')
                ->numeric()
                ->default(0.270)
                ->required()
                ->postfix('kg/kWh'),
            TextInput::make('weight')
                ->label('Svars')
                ->numeric()
                ->required()
                ->postfix('kg'),
            TextInput::make('working_width')
                ->label('Darba platums')
                ->numeric()
                ->required()
                ->postfix('m'),
            TextInput::make('working_speed')
                ->label('Darba ātrums')
                ->default(7.0)
                ->numeric()
                ->required()
                ->postfix('km/h'),
            Toggle::make('is_self_propelled')
                ->live()
                ->label('Ir pašgājējs'),
        ];
    }

    public static function buildTractorSpecifications(): array {
        return [
            TextInput::make('power')
                ->label('Jauda')
                ->numeric()
                ->required()
                ->postfix('kW'),
            TextInput::make('weight')
                ->label('Svars')
                ->numeric()
                ->required()
                ->postfix('kg'),
            TextInput::make('specific_fuel_consumption')
                ->label('Īpatnējais degvielas patēriņš')
                ->numeric()
                ->default(0.270)
                ->required()
                ->postfix('kg/kWh'),
            Select::make('drive_type')
                ->label('Piedziņas tips')
                ->required()
                ->default(DriveType::DRIVE_2WD)
                ->options(DriveType::class)
        ];
    }
}
