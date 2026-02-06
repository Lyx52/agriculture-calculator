<?php

namespace App\Filament\Resources\Admin\AgricultureEquipment;

use App\Filament\Resources\Admin\AgricultureEquipment\Pages\ListAgricultureEquipment;
use App\Filament\Resources\Admin\AgricultureEquipment\Schemas\AgricultureEquipmentForm;
use App\Filament\Resources\Admin\AgricultureEquipment\Tables\AgricultureEquipmentTable;
use App\Models\AgricultureEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgricultureEquipmentResource extends Resource
{
    protected static ?string $model = AgricultureEquipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $breadcrumb = 'Tehnikas datubāze';
    protected static ?string $navigationLabel = 'Tehnikas datubāze';
    protected static ?string $pluralLabel = 'Lauksaimniecības tehnika';
    protected static ?string $modelLabel = 'Lauksaimniecības tehnika';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AgricultureEquipmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgricultureEquipmentTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgricultureEquipment::route('/'),
        ];
    }
}
