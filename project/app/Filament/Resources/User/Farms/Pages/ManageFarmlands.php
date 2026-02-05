<?php

namespace App\Filament\Resources\User\Farms\Pages;

use App\Filament\Resources\User\Farms\FarmResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Schemas\FarmlandForm;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Tables\FarmlandsTable;
use BackedEnum;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManageFarmlands extends ManageRelatedRecords
{
    protected static string $resource = FarmResource::class;

    protected static string $relationship = 'farmlands';
    protected ?string $heading = 'Saimniecības lauki';
    protected static ?string $navigationLabel = 'Saimniecības lauki';
    protected static ?string $breadcrumb = 'Saimniecības lauki';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public function form(Schema $schema): Schema
    {
        return FarmlandForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return FarmlandsTable::configure($table);
    }
}
