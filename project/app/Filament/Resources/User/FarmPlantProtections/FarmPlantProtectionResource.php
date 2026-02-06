<?php

namespace App\Filament\Resources\User\FarmPlantProtections;

use App\Filament\Resources\User\FarmPlantProtections\Pages\ListFarmPlantProtections;
use App\Filament\Resources\User\FarmPlantProtections\Schemas\FarmPlantProtectionForm;
use App\Filament\Resources\User\FarmPlantProtections\Tables\FarmPlantProtectionsTable;
use App\Models\FarmPlantProtection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FarmPlantProtectionResource extends Resource
{
    protected static ?string $model = FarmPlantProtection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Augu aizsardzības līdzekļi';
    protected static ?string $breadcrumb = 'Augu aizsardzības līdzekļi';
    protected static ?string $pluralLabel = 'Augu aizsardzības līdzekļi';
    protected static ?string $modelLabel = 'Augu aizsardzības līdzeklis';
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmPlantProtectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmPlantProtectionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmPlantProtections::route('/'),
        ];
    }
}
