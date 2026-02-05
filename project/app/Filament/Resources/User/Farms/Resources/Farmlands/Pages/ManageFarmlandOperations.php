<?php

namespace App\Filament\Resources\User\Farms\Resources\Farmlands\Pages;

use App\Filament\Resources\User\Farms\Resources\Farmlands\FarmlandResource;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Schemas\FarmlandOperationForm;
use App\Filament\Resources\User\Farms\Resources\Farmlands\Resources\FarmlandOperations\Tables\FarmlandOperationsTable;
use BackedEnum;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageFarmlandOperations extends ManageRelatedRecords
{
    protected static string $resource = FarmlandResource::class;

    protected static string $relationship = 'operations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public function getHeading(): string|Htmlable|null
    {
        $farmlandName = $this->record->name ?? '';
        return "Pārvaldīt '{$farmlandName}' apstrādes operācijas";
    }

    public function form(Schema $schema): Schema
    {
        return FarmlandOperationForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return FarmlandOperationsTable::configure($table);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Izveidot apstrādes operāciju')
        ];
    }
}
