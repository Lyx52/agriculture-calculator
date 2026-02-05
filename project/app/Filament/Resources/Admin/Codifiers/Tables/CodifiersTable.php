<?php

namespace App\Filament\Resources\Admin\Codifiers\Tables;

use App\Models\Codifier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CodifiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('parent'))
            ->columns([
                TextColumn::make('parent')
                    ->label('Grupa')
                    ->formatStateUsing(fn(Codifier $codifier) => $codifier->parent?->name ?? ''),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nosaukums'),
                TextColumn::make('code')
                    ->searchable()
                    ->label('Kods'),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Grupa')
                    ->options(Codifier::query()->whereHas('children')->pluck('name', 'id'))
                    ->searchable()

            ])
            ->emptyStateHeading('Nav klasifikatori')
            ->paginated()
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ]);
    }
}
