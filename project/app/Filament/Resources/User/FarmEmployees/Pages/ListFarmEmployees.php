<?php

namespace App\Filament\Resources\User\FarmEmployees\Pages;

use App\Filament\Resources\User\FarmEmployees\FarmEmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFarmEmployees extends ListRecords
{
    protected static string $resource = FarmEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
