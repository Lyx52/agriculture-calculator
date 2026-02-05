<?php

namespace App\Livewire;

use App\Models\FarmlandOperation;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestFarmlandOperations extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Pēdējās apstrādes operācijas')
            ->query(fn (): Builder => FarmlandOperation::query())
            ->paginated(false)
            ->columns([
                TextColumn::make('operation_date')->formatStateUsing(fn(Carbon $state) => $state->formatted())->label('Datums'),
                TextColumn::make('operation.name')->label('Apstrādes operācija'),
            ]);
    }
}
