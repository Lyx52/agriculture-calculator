<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class CostsOverviewWidget extends ConfigurableChartWidget
{
    protected ?string $heading = 'Blog Posts';
    public function configureActionUsing(Action $action)
    {
        return $action->schema([
            Select::make('chart_type')
                ->label('Tips')
                ->options([
                    'line' => 'Līniju',
                    'bar' => 'Stabiņu'
                ])
        ]);
    }

    public static function getDefaultConfiguration(): array
    {
        return [
            'chart_type' => 'line'
        ];
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return $this->getConfig('chart_type');
    }
}
