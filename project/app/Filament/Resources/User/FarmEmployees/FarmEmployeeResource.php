<?php

namespace App\Filament\Resources\User\FarmEmployees;

use App\Filament\Resources\User\FarmEmployees\Pages\ListFarmEmployees;
use App\Filament\Resources\User\FarmEmployees\Schemas\FarmEmployeeForm;
use App\Filament\Resources\User\FarmEmployees\Tables\FarmEmployeesTable;
use App\Models\FarmEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmEmployeeResource extends Resource
{
    protected static ?string $model = FarmEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Mani darbinieki / pakalpojumi';
    protected static ?string $breadcrumb = 'Mani darbinieki / pakalpojumi';
    protected static ?string $pluralLabel = 'Mani darbinieki / pakalpojumi';
    protected static ?string $modelLabel = 'Darbinieks / Pakalpojums';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmEmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmEmployeesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarmEmployees::route('/'),
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
