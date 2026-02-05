<?php

namespace App\Filament\Resources\User\Farms;

use App\Filament\Resources\User\Farms\Pages\CreateFarm;
use App\Filament\Resources\User\Farms\Pages\EditFarm;
use App\Filament\Resources\User\Farms\Pages\ListFarms;
use App\Filament\Resources\User\Farms\Pages\ManageFarmlands;
use App\Filament\Resources\User\Farms\RelationManagers\FarmlandsRelationManager;
use App\Filament\Resources\User\Farms\Schemas\FarmForm;
use App\Filament\Resources\User\Farms\Tables\FarmsTable;
use App\Models\Farm;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationLabel = 'Manas saimniecības';
    protected static ?string $breadcrumb = 'Manas saimniecības';
    protected static ?string $pluralLabel = 'Manas saimniecības';
    protected static ?string $modelLabel = 'Saimniecība';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmsTable::configure($table);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            EditFarm::class,
            ManageFarmlands::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            FarmlandsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarms::route('/'),
            'create' => CreateFarm::route('/create'),
            'edit' => EditFarm::route('/{record}/edit'),
            'farmlands' => ManageFarmlands::route('/{record}/farmlands'),
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
