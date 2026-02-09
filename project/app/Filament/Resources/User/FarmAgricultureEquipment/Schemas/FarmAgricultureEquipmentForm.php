<?php

namespace App\Filament\Resources\User\FarmAgricultureEquipment\Schemas;

use App\Enums\DefinedCodifiers;
use App\Filament\Resources\Admin\AgricultureEquipment\Schemas\AgricultureEquipmentForm;
use App\Models\Codifier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FarmAgricultureEquipmentForm
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
                Section::make('Iegādes informācija')
                    ->collapsible()
                    ->schema([
                        TextInput::make('price')
                            ->label('Cena')
                            ->numeric()
                            ->required()
                            ->postfix('EUR'),
                        DatePicker::make('purchased_date')
                            ->label('Iegādes datums')
                            ->required()
                    ]),
                Section::make("Specifikācija")
                    ->visible(fn(Get $get) => !empty($get('equipment_category_code')))
                    ->schema(fn(Get $get) => AgricultureEquipmentForm::buildSpecificationForm($get('equipment_category_code'))),
                Hidden::make('owner_id')
                    ->default(auth()->id())
            ]);
    }
}
