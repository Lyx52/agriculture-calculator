<?php

namespace App\Providers\Filament;

use App\Filament\Pages\User\Frontpage;
use App\Providers\Filament\Traits\DefaultPanel;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
class UserPanelProvider extends PanelProvider
{
    use DefaultPanel;
    public function panel(Panel $panel): Panel
    {
        return $this->defaultPanel($panel, 'user')
            ->registration()
            ->default()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->spa(hasPrefetching: true)
            ->discoverResources(in: app_path('Filament/Resources/User'), for: 'App\Filament\Resources\User')
            ->discoverPages(in: app_path('Filament/Pages/User'), for: 'App\Filament\Pages\User')
            ->pages([
                Frontpage::class,
            ]);
    }
}
