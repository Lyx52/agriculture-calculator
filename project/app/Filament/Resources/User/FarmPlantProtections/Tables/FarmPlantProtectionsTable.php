<?php

namespace App\Filament\Resources\User\FarmPlantProtections\Tables;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use App\Models\FarmCrop;
use App\Models\FarmPlantProtection;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FarmPlantProtectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(user()->plantProtectionProducts()->getQuery())
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->label('Nosaukums'),
                TextColumn::make('company')->searchable()->sortable()->label('Īpašnieks'),
                TextColumn::make('categoriesText')->label('Kategorijas'),
                TextColumn::make('cost_per_unit')->sortable()->formatStateUsing(fn(FarmPlantProtection $record) => $record->costsText)->label('Izmaksas'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('protection_category_codes')
                    ->label('Suga')
                    ->options(Codifier::whereParentCode(DefinedCodifiers::CROP_PROTECTION_USAGE)->pluck('name', 'code'))
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        $query->when(!empty($data['value']), fn(Builder $query) => $query->whereJsonContains('protection_category_codes', $data['value']));
                    }),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav augu aizsardzības līdzekļu')
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
