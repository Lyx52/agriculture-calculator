<?php

namespace App\Filament\Resources\User\Farms\Schemas;

use App\Enums\CostType;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
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
                        Section::make('Noklusētās vērtības')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextInput::make('fuel_cost')
                                    ->numeric()
                                    ->required()
                                    ->label('Degvielas izmaksas')
                                    ->postfix('EUR/l'),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('default_employee_salary')
                                            ->numeric()
                                            ->required()
                                            ->label('Noklusētā darba alga'),
                                        Select::make('default_employee_salary_type')
                                            ->label('Noklusētās darba algas tips')
                                            ->required()
                                            ->default(CostType::EUR_HOUR)
                                            ->options(CostType::class)
                                            ->searchable(),
                                    ])
                            ]),
                        Hidden::make('owner_id')
                            ->default(auth()->id())
                    ])
            ]);
    }
}
