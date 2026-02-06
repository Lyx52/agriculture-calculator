<?php

namespace App\Filament\Resources\User\FarmCrops\Pages;

use App\Filament\Resources\User\FarmCrops\FarmCropResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFarmCrops extends ListRecords
{
    protected static string $resource = FarmCropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
