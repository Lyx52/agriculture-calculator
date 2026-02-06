<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Tables;

use App\Enums\DefinedCodifiers;
use App\Filament\Resources\User\Farms\Resources\Farmlands\FarmlandResource;
use App\Models\Codifier;
use App\Models\Farmland;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FarmlandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['materials']))
            ->columns([
                TextColumn::make('name')
                    ->label('Nosaukums')
                    ->searchable(),
                TextColumn::make('latestCropName')
                    ->label('Pēdējais iesētais kūltūraugs')
                    ->searchable(),
                TextColumn::make('area')
                    ->label('Zemes platība, ha'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('crop_id')
                    ->label('Kūltūraugs')
                    ->options(user()->crops()->get()->pluck('cropName', 'id'))
                    ->searchable(),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav saimniecības lauku')
            ->paginated()
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
            ])
            ->headerActions([
                CreateAction::make()->label('Izveidot jaunu lauku')
            ]);
    }
}
