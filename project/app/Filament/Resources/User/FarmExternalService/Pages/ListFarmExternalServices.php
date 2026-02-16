<?php

namespace App\Filament\Resources\User\FarmExternalService\Pages;

use App\Filament\Resources\User\FarmExternalService\FarmExternalServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFarmExternalServices extends ListRecords
{
    protected static string $resource = FarmExternalServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Izveidot ārējo pakalpojumu'),
        ];
    }
}
