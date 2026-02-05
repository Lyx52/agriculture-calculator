<?php

namespace App\Filament\Resources\User\Farms\Pages;

use App\Filament\Resources\User\Farms\FarmResource;
use App\Filament\Resources\User\Farms\RelationManagers\FarmlandsRelationManager;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFarm extends EditRecord
{
    protected static string $resource = FarmResource::class;
    protected static ?string $navigationLabel = 'Rediģēt saimniecību';
    public static function getRelations(): array
    {
        return [
            FarmlandsRelationManager::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
