<?php

namespace App\Filament\Resources\User\FarmFertilizers\Tables;

use App\Models\FarmFertilizer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FarmFertilizersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contents')->searchable()->sortable()->label('Darbavielu saturs'),
                TextColumn::make('name')->searchable()->sortable()->label('Nosaukums'),
                TextColumn::make('cost_per_unit')->sortable()->formatStateUsing(fn(FarmFertilizer $record) => $record->costsText)->label('Izmaksas'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('hasNutrients')
                    ->label('Satur darbīgo vielu')
                    ->multiple()
                    ->options([
                        'value_n' => "N",
                        'value_p2o5' => "P2O5",
                        'value_k2o' => "K2O",
                        'value_ca' => "Ca",
                        'value_mg' => "Mg",
                        'value_na' => "Na",
                        'value_s' => "S",
                        'value_b' => "B",
                        'value_co' => "Co",
                        'value_cu' => "Cu",
                        'value_fe' => "Fe",
                        'value_mn' => "Mn",
                        'value_mo' => "Mo",
                        'value_zn' => "Zn",
                        'value_caco3' => "CaCO3",
                    ])
                    ->query(function (Builder $query, SelectFilter $filter, array $data) {
                        $nutrients = $data['values'] ?? [];
                        $options = $filter->getOptions();
                        foreach (array_intersect($nutrients, array_keys($options)) as $nutrient) {
                            $query->where($nutrient, '>', 0);
                        }
                    })
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav minerālmēslu')
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
