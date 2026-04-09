<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends ConfigurableWidget
{
    public function configureActionUsing(Action $action)
    {
        return $action->schema([
            TextInput::make('views')
        ]);
    }

    public static function getDefaultConfiguration(): array
    {
        return [
            'views' => ''
        ];
    }

    public function getContent(): array
    {
        return [
            Stat::make('Unique views', fn() => $this->getConfig('views'))
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
