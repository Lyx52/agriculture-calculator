<?php

namespace App\Filament\Resources\Admin\AgricultureEquipment\Pages;

use App\Filament\Resources\Admin\AgricultureEquipment\AgricultureEquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgricultureEquipment extends ListRecords
{
    protected static string $resource = AgricultureEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Izveidot jaunu lauksaimniecības tehniku'),
        ];
    }
}
