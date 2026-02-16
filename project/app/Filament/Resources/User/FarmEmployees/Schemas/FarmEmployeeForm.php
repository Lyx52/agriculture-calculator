<?php

namespace App\Filament\Resources\User\FarmEmployees\Schemas;

use App\Enums\CostType;
use App\Enums\DefinedCodifiers;
use App\Enums\EmployeeType;
use App\Models\Codifier;
use App\Models\FarmEmployee;
use App\Models\FarmEmployeeOperationCost;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                Section::make('Izmaksas pa operācijām')
                    ->afterStateHydrated(function (?FarmEmployee $record, Set $set) {
                        if (empty($record)) {
                            return;
                        }
                        $record->costs()->each(fn (FarmEmployeeOperationCost $item) => $set($item->operation_type_code, $item->toArray()));
                    })
                    ->collapsible()
                    ->collapsed()
                    ->schema(Codifier::whereParentCode(DefinedCodifiers::OPERATION_TYPES)->get()->map(fn(Codifier $codifier) =>
                        Fieldset::make($codifier->name)
                            ->statePath($codifier->code)
                            ->saveRelationshipsUsing(function (FarmEmployee $record, $state) {
                                if (empty($state['costs']) || empty($state['operation_type_code'])) {
                                    return;
                                }
                                $costsRecord = $record->costs()->createOrFirst(
                                    ['operation_type_code' => $state['operation_type_code']],
                                    $state
                                );

                                $costsRecord->update($state);
                            })
                            ->schema([
                                TextInput::make("costs")
                                    ->live()
                                    ->numeric()
                                    ->label('Izmaksas'),
                                Select::make("cost_type")
                                    ->live()
                                    ->required()
                                    ->label('Izmaksu tips')
                                    ->default(CostType::EUR_HOUR)
                                    ->options(CostType::workOptions())
                                    ->formatStateUsing(fn($state) => $state ?? CostType::EUR_HOUR)
                                    ->searchable(),
                                Hidden::make("operation_type_code")
                                    ->default($codifier->code)
                                    ->formatStateUsing(fn($state) => $state ?? $codifier->code)
                            ])
                    )->toArray()),
                Hidden::make('owner_id')
                    ->default(auth()->id()),
                Hidden::make('employee_type')
                    ->default(EmployeeType::WORKER),
            ]);
    }
}
