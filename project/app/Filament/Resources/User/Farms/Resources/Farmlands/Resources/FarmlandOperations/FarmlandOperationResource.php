<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations;

use App\Filament\Resources\User\Farms\Resources\Farmlands\FarmlandResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Schemas\FarmlandOperationForm;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Tables\FarmlandOperationsTable;
use App\Models\FarmlandOperation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmlandOperationResource extends Resource
{
    protected static ?string $model = FarmlandOperation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = FarmlandResource::class;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return FarmlandOperationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmlandOperationsTable::configure($table);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
