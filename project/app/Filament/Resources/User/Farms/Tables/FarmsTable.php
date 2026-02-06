<?php

namespace App\Filament\Resources\User\Farms\Tables;

use App\Models\Farm;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FarmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(user()->farms()->getQuery())
            ->columns([
                TextColumn::make('name')->searchable()->label('Saimniecības nosaukums'),
                TextColumn::make('farmlandCount')
                    ->badge()
                    ->getStateUsing(fn(Farm $record) => $record->farmlands()->count())
                    ->label('Lauku skaits'),
                TextColumn::make('farmlandArea')
                    ->badge()
                    ->color(Color::Green)
                    ->getStateUsing(fn(Farm $record) => $record->farmlands()->sum('area'))
                    ->label('Kopējā lauku platība, ha'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav saimniecības')
            ->paginated()
            ->recordActions([
                EditAction::make()
                    ->label('Labot'),
                DeleteAction::make()
                    ->label('Dzēst'),
                RestoreAction::make()
                    ->label('Atjaunot'),
            ]);
    }
}
