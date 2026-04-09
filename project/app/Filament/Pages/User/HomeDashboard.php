<?php

namespace App\Filament\Pages\User;

use App\Livewire\ConfigurableWidget;
use App\Livewire\LatestFarmlandOperations;
use App\Livewire\StatsOverview;
use Asosick\FilamentLayoutManager\Pages\LayoutManagerPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class HomeDashboard extends LayoutManagerPage
{
    protected static string $routePath = '/';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::Home;
    protected static ?string $navigationLabel = 'Sākums';
    protected static ?string $title = 'Sākums';
    protected function getComponents(): array
    {
        return [
            LatestFarmlandOperations::class,
            StatsOverview::class
        ];
    }

    protected function getComponentSelectOptions(): array
    {
        return ['My Company Widget', 'test'];
    }
}
