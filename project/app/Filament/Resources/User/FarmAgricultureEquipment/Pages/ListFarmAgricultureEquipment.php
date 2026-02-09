<?php

namespace App\Filament\Resources\User\FarmAgricultureEquipment\Pages;

use App\Enums\DefinedCodifiers;
use App\Filament\Resources\User\FarmAgricultureEquipment\FarmAgricultureEquipmentResource;
use App\Models\AgricultureEquipment;
use App\Models\Codifier;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;

class ListFarmAgricultureEquipment extends ListRecords
{
    protected static string $resource = FarmAgricultureEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->extraModalFooterActions([
                    Action::make('createFromDatabase')
                        ->label('Izveidot no datubāzes')
                        ->modal()
                        ->schema([
                            Select::make('equipment_category_code')
                                ->label('Tehnikas kategorija')
                                ->options(Codifier::whereParentCode(DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE)->pluck('name', 'code'))
                                ->searchable(),
                            Select::make('equipment_sub_category_code')
                                ->visible(fn(Get $get) => !empty($get('equipment_category_code')) && Codifier::whereParentCode($get('equipment_category_code'))->count() > 0)
                                ->required()
                                ->live()
                                ->label('Tehnikas nosaukums')
                                ->options(fn(Get $get) => empty($get('equipment_category_code')) ? Codifier::whereParentOfParentCode(DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE)->pluck('name', 'code') : Codifier::whereParentCode($get('equipment_category_code'))->pluck('name', 'code'))
                                ->searchable(),
                            Select::make('agriculture_equipment_id')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search, Get $get) =>AgricultureEquipment::query()
                                    ->limit(20)
                                    ->when(!empty($search), fn($query) => $query
                                        ->whereLike('manufacturer', "%$search%")
                                        ->orWhereLike('model', "%$search%")
                                    )
                                    ->when(!empty($get('equipment_category_code')), fn($query) => $query
                                        ->where('equipment_category_code', $get('equipment_category_code'))
                                    )
                                    ->when(!empty($get('equipment_sub_category_code')), fn($query) => $query
                                        ->where('equipment_sub_category_code', $get('equipment_sub_category_code'))
                                    )
                                    ->get()
                                    ->pluck('fullName', 'id'),
                                )
                                ->getOptionLabelUsing(fn ($value): ?string => AgricultureEquipment::find($value)?->fullName)
                                ->options(fn(Get $get) => AgricultureEquipment::query()
                                    ->limit(20)
                                    ->when(!empty($get('equipment_category_code')), fn($query) => $query
                                        ->where('equipment_category_code', $get('equipment_category_code'))
                                    )
                                    ->when(!empty($get('equipment_sub_category_code')), fn($query) => $query
                                        ->where('equipment_sub_category_code', $get('equipment_sub_category_code'))
                                    )
                                    ->get()
                                    ->pluck('fullName', 'id')
                                )
                                ->label('Izvēlēties')
                        ])
                        ->modalSubmitActionLabel('Izmantot')
                        ->action(function (array $mountedActions, array $data) {
                            /** @var CreateAction $parentAction */
                            $parentAction = $mountedActions[0];
                            $livewire = $parentAction->getLivewire();

                            /** @var AgricultureEquipment $model */
                            $model = AgricultureEquipment::find($data['agriculture_equipment_id']);
                            $livewire->getMountedTableActionForm()->fill([
                                ...$model->toArray(),
                                'owner_id' => user()->id
                            ]);
                        })
                ])
                ->label('Izveidot jaunu lauksaimniecības tehniku'),
        ];
    }
}
