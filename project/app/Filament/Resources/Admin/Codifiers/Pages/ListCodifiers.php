<?php

namespace App\Filament\Resources\Admin\Codifiers\Pages;

use App\Filament\Resources\Admin\Codifiers\CodifierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCodifiers extends ListRecords
{
    protected static string $resource = CodifierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
