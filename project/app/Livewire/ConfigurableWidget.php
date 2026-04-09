<?php

namespace App\Livewire;

use App\Livewire\Traits\HasConfigurableWidget;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

abstract class ConfigurableWidget extends Widget implements HasActions, HasSchemas
{
    public const UPDATE_WIDGET_CONFIG = 'UPDATE_WIDGET_CONFIG';
    use HasConfigurableWidget;

    protected string $view = 'livewire.configurable-widget';

    protected ?array $cachedContent = null;
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getSectionContentComponent(),
            ]);
    }

    public function getSectionContentComponent(): Component
    {
        return Section::make()
            ->schema($this->getCachedContent())
            ->columns(1)
            ->contained(false)
            ->gridContainer();
    }

    public function getContent(): array {
        return [];
    }

    public function getCachedContent(): array {
        return $this->cachedContent ??= $this->getContent();
    }
}
