<?php

namespace App\Filament\Resources\User\FarmCrops\Tables;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use App\Models\FarmCrop;
use App\Models\FarmEmployee;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FarmCropsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(user()->crops()->getQuery())
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->label('Šķirnes nosaukums'),
                TextColumn::make('species.name')->searchable()->sortable()->label('Sugas nosaukums'),
                TextColumn::make('cost_per_unit')->sortable()->formatStateUsing(fn(FarmCrop $record) => $record->costsText)->label('Izmaksas'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('crop_species_code')
                    ->label('Suga')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::CROP_SPECIES)->pluck('name', 'code'))
                    ->searchable(),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav kūltūraugu')
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
