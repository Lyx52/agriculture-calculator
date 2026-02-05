<?php

namespace App\Filament\Resources\Admin\Codifiers;

use App\Filament\Resources\Admin\Codifiers\Pages\CreateCodifier;
use App\Filament\Resources\Admin\Codifiers\Pages\EditCodifier;
use App\Filament\Resources\Admin\Codifiers\Pages\ListCodifiers;
use App\Filament\Resources\Admin\Codifiers\Schemas\CodifierForm;
use App\Filament\Resources\Admin\Codifiers\Tables\CodifiersTable;
use App\Models\Codifier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CodifierResource extends Resource
{
    protected static ?string $model = Codifier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $breadcrumb = 'Klasifikatori';
    protected static ?string $navigationLabel = 'Klasifikatori';

    protected static ?string $pluralLabel = 'Klasifikatori';
    protected static ?string $modelLabel = 'Klasifikators';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CodifierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CodifiersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCodifiers::route('/'),
        ];
    }
}
