<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Schemas;

use App\Enums\DefinedCodifiers;
use App\Enums\MaterialAmountType;
use App\Models\Codifier;
use App\Models\FarmCrop;
use App\Models\FarmEmployee;
use App\Models\FarmlandOperation;
use App\Models\FarmPlantProtection;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RelationshipRepeater;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;

class FarmlandOperationForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = user();
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Select::make('operation_code')
                    ->required()
                    ->label('Apstrādes tips')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::OPERATION_TYPES)->pluck('name', 'code'))
                    ->searchable(),
                DatePicker::make('operation_date')
                    ->required()
                    ->label('Apstrādes datums'),
                Select::make('employee_id')
                    ->label('Darbinieks')
                    ->options(FarmEmployee::all()->pluck('fullName', 'id'))
                    ->searchable(),
                // Todo: Te vajadzetu padomat par perf optimizāciju
                Repeater::make('materialInput')
                    ->label('Izmantotie materiāli')
                    ->relationship('materials')
                    ->orderColumn(false)
                    ->addActionLabel('Pievienot materiālu')
                    ->schema([
                        Select::make('material_type')
                            ->live()
                            ->native(false)
                            ->default(FarmCrop::class)
                            ->label('Materiāla veids')
                            ->options([
                                FarmCrop::class => 'Kūltūrauga sēkla',
                                FarmPlantProtection::class => 'Augu aizsardzības līdzekļi'
                            ]),
                        Select::make('material_id')
                            ->required()
                            ->label(fn(Get $get) => match($get('material_type')) {
                                FarmPlantProtection::class => 'Augu aizsardzības līdzeklis',
                                default => 'Kūltūraugs',
                            })
                            ->options(fn(Get $get) =>  match($get('material_type')) {
                                FarmPlantProtection::class => $user->plantProtectionProducts->pluck('productName', 'id'),
                                default => $user->crops->pluck('cropName', 'id'),
                            })
                            ->native(false),
                        TextInput::make('material_amount')
                            ->numeric()
                            ->required()
                            ->label('Apjoms'),
                        Select::make('material_amount_type')
                            ->label('Darbinieks')
                            ->default(MaterialAmountType::KILOGRAMS_LITERS_PER_HECTARE)
                            ->options(MaterialAmountType::class)
                            ->native(false),
                    ])
            ]);
    }
}
