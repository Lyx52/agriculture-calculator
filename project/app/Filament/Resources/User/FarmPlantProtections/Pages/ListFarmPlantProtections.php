<?php

namespace App\Filament\Resources\User\FarmPlantProtections\Pages;

use App\Filament\Resources\User\FarmPlantProtections\FarmPlantProtectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFarmPlantProtections extends ListRecords
{
    protected static string $resource = FarmPlantProtectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
