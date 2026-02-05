<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Tables;

use App\Filament\Resources\User\Farms\Resources\Farmlands\FarmlandResource;
use App\Models\Farmland;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FarmlandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nosaukums')
                    ->searchable(),
                TextColumn::make('seed.name')
                    ->label('Kūltūraugs')
                    ->searchable(),
                TextColumn::make('area')
                    ->label('Zemes platība, ha'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('operations')
                    ->label('Apstrādes operācijas')
                    ->icon(Heroicon::Cog)
                    ->action(function (Action $action, Farmland $record) {
                        $action->redirect(FarmlandResource::getUrl('operations', [
                            'record' => $record,
                            'farm' => $record->farm
                        ]));
                    }),
                EditAction::make()
                    ->label('Labot'),
                DeleteAction::make()
                    ->label('Dzēst'),
                RestoreAction::make()
                    ->label('Atjaunot'),
            ]);
    }
}
