<?php

namespace App\Filament\Resources\User\FarmFertilizers\Pages;

use App\Filament\Resources\User\FarmFertilizers\FarmFertilizerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFarmFertilizers extends ListRecords
{
    protected static string $resource = FarmFertilizerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Izveidot minerālmēslojumu'),
        ];
    }
}
