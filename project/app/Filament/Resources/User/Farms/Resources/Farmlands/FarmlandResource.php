<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands;

use App\Filament\Resources\User\Farms\FarmResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Pages\ManageFarmlandOperations;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Schemas\FarmlandForm;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Tables\FarmlandsTable;
use App\Models\Farmland;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmlandResource extends Resource
{
    protected static ?string $model = Farmland::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = FarmResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmlandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmlandsTable::configure($table);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'operations' => ManageFarmlandOperations::route('/farmlands/{record}/operations'),
        ];
    }
}
