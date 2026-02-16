<?php

namespace App\Filament\Resources\User\FarmExternalService;

use App\Filament\Resources\User\FarmExternalService\Pages\ListFarmExternalServices;
use App\Filament\Resources\User\FarmExternalService\Schemas\FarmExternalServiceForm;
use App\Filament\Resources\User\FarmExternalService\Tables\FarmExternalServiceTable;
use App\Models\FarmEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmExternalServiceResource extends Resource
{
    protected static ?string $model = FarmEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Ārējie pakalpojumi';
    protected static ?string $breadcrumb = 'Ārējie pakalpojumi';
    protected static ?string $pluralLabel = 'Ārējie pakalpojumi';
    protected static ?string $modelLabel = 'Pakalpojums';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmExternalServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmExternalServiceTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmExternalServices::route('/'),
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
