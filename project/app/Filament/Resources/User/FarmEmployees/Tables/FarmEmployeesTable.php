<?php

namespace App\Filament\Resources\User\FarmEmployees\Tables;

use App\Models\FarmEmployee;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FarmEmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullName')->searchable()->label('Vārds, Uzvārds / Nosaukums'),
                TextColumn::make('employeeType.name')->label('Darbinieka tips'),
                TextColumn::make('salaryText')->label('Alga / Izmaksas'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
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
