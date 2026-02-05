<?php
namespace App\Providers\Filament\Traits;

use Filament\Support\Components\Component;
use Filament\Tables\Columns\Column;

trait DefaultConfiguration {
    public function boot(): void
    {
        Column::configureUsing(function (Column $column) {
            $column->translateLabel();
        });

        Component::configureUsing(function (Component $component) {
            $component->translateLabel();
        });
    }
}
