<?php

namespace App\Filament\Resources\User\FarmCrops;

use App\Filament\Resources\User\FarmCrops\Pages\ListFarmCrops;
use App\Filament\Resources\User\FarmCrops\Schemas\FarmCropForm;
use App\Filament\Resources\User\FarmCrops\Tables\FarmCropsTable;
use App\Models\FarmCrop;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FarmCropResource extends Resource
{
    protected static ?string $model = FarmCrop::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Kūltūraugi';
    protected static ?string $breadcrumb = 'Kūltūraugi';
    protected static ?string $pluralLabel = 'Kūltūraugi';
    protected static ?string $modelLabel = 'Kūltūraugs';
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Schema $schema): Schema
    {
        return FarmCropForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmCropsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmCrops::route('/'),
        ];
    }
}
