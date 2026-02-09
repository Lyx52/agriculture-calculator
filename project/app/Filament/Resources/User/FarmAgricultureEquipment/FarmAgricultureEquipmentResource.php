<?php

namespace App\Filament\Resources\User\FarmAgricultureEquipment;

use App\Filament\Resources\User\FarmAgricultureEquipment\Pages\ListFarmAgricultureEquipment;
use App\Filament\Resources\User\FarmAgricultureEquipment\Schemas\FarmAgricultureEquipmentForm;
use App\Filament\Resources\User\FarmAgricultureEquipment\Tables\FarmAgricultureEquipmentTable;
use App\Models\FarmAgricultureEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmAgricultureEquipmentResource extends Resource
{
    protected static ?string $model = FarmAgricultureEquipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $breadcrumb = 'Lauksaimniecības tehnika';
    protected static ?string $navigationLabel = 'Mana lauksaimniecības tehnika';
    protected static ?string $pluralLabel = 'Lauksaimniecības tehnika';
    protected static ?string $modelLabel = 'Lauksaimniecības tehnika';
    protected static ?string $recordTitleAttribute = 'model';

    public static function form(Schema $schema): Schema
    {
        return FarmAgricultureEquipmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmAgricultureEquipmentTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmAgricultureEquipment::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
