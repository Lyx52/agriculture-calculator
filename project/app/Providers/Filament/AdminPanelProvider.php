<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Traits\DefaultPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AdminPanelProvider extends PanelProvider
{
    use DefaultPanel;
    public function panel(Panel $panel): Panel
    {
        return $this->defaultPanel($panel, 'admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources/Admin'), for: 'App\Filament\Resources\Admin')
            ->discoverPages(in: app_path('Filament/Pages/Admin'), for: 'App\Filament\Pages\Admin');
    }
}
