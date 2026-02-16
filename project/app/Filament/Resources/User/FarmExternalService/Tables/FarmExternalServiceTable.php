<?php

namespace App\Filament\Resources\User\FarmExternalService\Tables;

use App\Enums\EmployeeType;
use App\Models\FarmEmployee;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FarmExternalServiceTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(user()->employees()->getQuery()->where('employee_type', EmployeeType::EXTERNAL_SERVICE))
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->label('Nosaukums'),
                TextColumn::make('salary')->sortable()->formatStateUsing(fn(FarmEmployee $record) => $record->salaryText)->label('Izmaksas'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->filtersApplyAction(fn(Action $action) => $action->label('Filtrēt'))
            ->emptyStateHeading('Nav ārējo pakalpojumu')
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
