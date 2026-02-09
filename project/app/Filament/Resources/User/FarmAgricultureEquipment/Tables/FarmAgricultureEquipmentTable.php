<?php

namespace App\Filament\Resources\User\FarmAgricultureEquipment\Tables;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FarmAgricultureEquipmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->sortable()->label('Tehnikas kategorija'),
                TextColumn::make('manufacturer')->sortable()->searchable()->label('Marka'),
                TextColumn::make('model')->sortable()->searchable()->label('Modelis'),
                TextColumn::make('price')->sortable()->searchable()->label('Cena'),
                TextColumn::make('purchased_date')->sortable()->searchable()->formatStateUsing(fn($state) => $state->formatted())->label('Iegādes datums'),
                TextColumn::make('power')->sortable()->searchable()->label('Jauda'),
                TextColumn::make('required_power')->sortable()->searchable()->label('Nepieciešamā jauda'),
            ])
            ->filters([
                SelectFilter::make('equipment_category_code')
                    ->label('Tehnikas kategorija')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE)->pluck('name', 'code'))
                    ->searchable(),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav tehnikas')
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
