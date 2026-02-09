<?php

namespace App\Filament\Resources\User\FarmEmployees\Schemas;

use App\Enums\CostType;
use App\Enums\EmployeeType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FarmEmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Select::make('employee_type')
                    ->live()
                    ->label('Darbinieka veids')
                    ->default(EmployeeType::WORKER)
                    ->options(EmployeeType::class)
                    ->searchable(),
                TextInput::make('name')->visible(fn(Get $get) => $get('employee_type') == EmployeeType::EXTERNAL_SERVICE)->label('Nosaukums')->required(),
                TextInput::make('name')->visible(fn(Get $get) => $get('employee_type') == EmployeeType::WORKER)->label('Vārds')->required(),
                TextInput::make('surname')->visible(fn(Get $get) => $get('employee_type') == EmployeeType::WORKER)->label('Uzvārds')->required(),
                Fieldset::make()
                    ->schema([
                        TextInput::make('salary')
                            ->numeric()
                            ->required()
                            ->label('Izmaksas'),
                        Select::make('cost_type')
                            ->label('Izmaksu tips')
                            ->required()
                            ->default(CostType::EUR_HOUR)
                            ->options(CostType::workOptions())
                            ->searchable()
                    ]),
                Hidden::make('owner_id')
                    ->default(auth()->id())
            ]);
    }
}
