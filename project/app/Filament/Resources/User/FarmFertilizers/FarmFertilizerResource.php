<?php

namespace App\Filament\Resources\User\FarmFertilizers;

use App\Filament\Resources\User\FarmFertilizers\Pages\ListFarmFertilizers;
use App\Filament\Resources\User\FarmFertilizers\Schemas\FarmFertilizerForm;
use App\Filament\Resources\User\FarmFertilizers\Tables\FarmFertilizersTable;
use App\Models\FarmFertilizer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FarmFertilizerResource extends Resource
{
    protected static ?string $model = FarmFertilizer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Minerālmēsli';
    protected static ?string $breadcrumb = 'Minerālmēsli';
    protected static ?string $pluralLabel = 'Minerālmēsli';
    protected static ?string $modelLabel = 'Minerālmēsli';
    protected static ?int $navigationSort = 6;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmFertilizerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmFertilizersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmFertilizers::route('/')
        ];
    }
}
