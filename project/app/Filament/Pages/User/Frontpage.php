<?php

namespace App\Filament\Pages\User;

use App\Livewire\LatestFarmlandOperations;
use App\Livewire\StatsOverview;
use Filament\Pages\Dashboard;

class Frontpage extends Dashboard
{
    public function getWidgets(): array
    {
        return [
            ...parent::getWidgets(),
            StatsOverview::class,
            LatestFarmlandOperations::class,
        ];
    }
}
