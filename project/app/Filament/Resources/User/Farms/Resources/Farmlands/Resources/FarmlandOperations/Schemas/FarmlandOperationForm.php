<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Schemas;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use App\Models\FarmEmployee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FarmlandOperationForm
{
    public static function configure(Schema $schema): Schema
    {
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
            ]);
    }
}
