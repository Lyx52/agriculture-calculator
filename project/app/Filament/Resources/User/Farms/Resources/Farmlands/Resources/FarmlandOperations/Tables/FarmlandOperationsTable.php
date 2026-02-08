<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Tables;

use App\Models\Farm;
use App\Models\FarmlandOperation;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FarmlandOperationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('operation_date')
            ->columns([
                TextColumn::make('operation_date')->sortable()->formatStateUsing(fn(Carbon $state) => $state->formatted())->label('Datums'),
                TextColumn::make('operation.name')->sortable()->searchable()->label('Apstrādes operācija'),
                TextColumn::make('operationMaterialCount')
                    ->badge()
                    ->color(Color::Orange)
                    ->getStateUsing(fn(FarmlandOperation $record) => $record->materials()->count())
                    ->label('Izmantoto materiālu skaits'),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('operation_date_filter')
                    ->schema([
                        DatePicker::make('date_from')->label('Datums no'),
                        DatePicker::make('date_to')->label('Datums līdz'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                !empty($data['date_from']),
                                fn(Builder $query) => $query->whereDate('operation_date', '>=', $data['date_from'])
                            )
                            ->when(
                                !empty($data['date_to']),
                                fn(Builder $query) => $query->whereDate('operation_date', '<=', $data['date_to'])
                            );
                    }),
            ])
            ->filtersApplyAction(function (Action $action) {
                return $action->label('Filtrēt');
            })
            ->recordActions([
                EditAction::make()
                    ->label('Labot')
                    ->modalHeading('Labot apstrādes operāciju'),
                DeleteAction::make()
                    ->label('Dzēst')
                    ->modalHeading('Dzēst apstrādes operāciju'),
                RestoreAction::make()
                    ->label('Atjaunot')
                    ->modalHeading('Atjaunot apstrādes operāciju'),
            ]);
    }
}
