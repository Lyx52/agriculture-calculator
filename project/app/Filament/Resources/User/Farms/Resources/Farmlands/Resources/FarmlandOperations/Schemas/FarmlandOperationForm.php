<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Schemas;

use App\Enums\DefinedCodifiers;
use App\Enums\MaterialAmountType;
use App\Models\Codifier;
use App\Models\FarmCrop;
use App\Models\FarmEmployee;
use App\Models\FarmPlantProtection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FarmlandOperationForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = user()->load(['plantProtectionProducts', 'crops']);
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
                                FarmCrop::class => 'Kūltūraugs',
                            })
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search, Get $get) => match($get('material_type')) {
                                FarmPlantProtection::class => $user->plantProtectionProducts()
                                    ->limit(20)
                                    ->when(!empty($search), fn($query) => $query->whereLike('name', "%$search%"))
                                    ->pluck('name', 'id'),
                                FarmCrop::class => $user->crops()
                                    ->limit(20)
                                    ->when(!empty($search), fn($query) => $query->whereLike('name', "%$search%"))
                                    ->pluck('cropName', 'id'),
                            })
                            ->options(fn (Get $get) => match($get('material_type')) {
                                FarmPlantProtection::class => $user->plantProtectionProducts->pluck('name', 'id'),
                                FarmCrop::class => $user->crops->pluck('cropName', 'id'),
                            })
                            ->preload()
                            ->getOptionLabelUsing(fn ($value, Get $get): ?string => match($get('material_type')) {
                                FarmPlantProtection::class => FarmPlantProtection::find($value)?->productName,
                                default => FarmCrop::find($value)?->cropName,
                            })
                            ->native(false),
                        TextInput::make('material_amount')
                            ->numeric()
                            ->required()
                            ->label('Apjoms'),
                        Select::make('material_amount_type')
                            ->label('Apjoma tips')
                            ->default(MaterialAmountType::KILOGRAMS_LITERS_PER_HECTARE)
                            ->options(MaterialAmountType::class)
                            ->native(false),
                    ])
            ]);
    }
}
