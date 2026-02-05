<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DatePicker;
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
            ->columns([
                TextColumn::make('operation_date')->formatStateUsing(fn(Carbon $state) => $state->formatted())->label('Datums'),
                TextColumn::make('operation.name')->searchable()->label('Apstrādes operācija'),
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
                    ->label('Labot'),
                DeleteAction::make()
                    ->label('Dzēst'),
                RestoreAction::make()
                    ->label('Atjaunot'),
            ]);
    }
}
