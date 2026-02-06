<?php

namespace App\Filament\Resources\User\Farms\RelationManagers;

use App\Filament\Resources\User\Farms\FarmResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\FarmlandResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Tables\FarmlandsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class FarmlandsRelationManager extends RelationManager
{
    protected static string $relationship = 'farmlands';

    protected static ?string $relatedResource = FarmlandResource::class;

    public function table(Table $table): Table
    {
        return FarmlandsTable::configure($table)
            ->heading('Saimniecības lauki')
            ->headerActions([
                CreateAction::make()->label('Izveidot jaunu lauku'),
            ]);
    }
}
