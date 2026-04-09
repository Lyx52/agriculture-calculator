<?php

namespace App\Livewire;

use App\Livewire\Traits\HasConfigurableWidget;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\ChartWidget;

abstract class ConfigurableChartWidget extends ChartWidget implements HasActions, HasSchemas
{
    public const UPDATE_WIDGET_CONFIG = 'UPDATE_WIDGET_CONFIG';
    use HasConfigurableWidget;
    protected string $view = 'livewire.configurable-chart-widget';
}
