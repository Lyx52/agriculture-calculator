<?php

namespace App\Filament\Resources\User\FarmEmployees\Schemas;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
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
                TextInput::make('name')->label('Vārds')->required(),
                TextInput::make('surname')->label('Uzvārds')->required(),
                Select::make('employee_type_code')
                    ->label('Darbinieka tips')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::EMPLOYEE_TYPES)->pluck('name', 'code'))
                    ->searchable(),
                Fieldset::make('Izmaksas')
                    ->schema([
                        TextInput::make('salary')
                            ->numeric()
                            ->required()
                            ->label('Summa'),
                        Select::make('salary_cost_type_code')
                            ->label('Darbinieka tips')
                            ->options(Codifier::whereParentCode(DefinedCodifiers::COST_TYPES)->pluck('name', 'code'))
                            ->searchable(),
                    ])
            ]);
    }
}
