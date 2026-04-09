<?php

namespace App\Livewire;

use App\Http\Livewire\UserWidgetLayoutManager;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class ConfigurableWidget extends Widget implements HasActions, HasSchemas
{
    public const UPDATE_WIDGET_CONFIG = 'UPDATE_WIDGET_CONFIG';
    use InteractsWithActions;
    use InteractsWithSchemas;
    protected array $cachedContent = [];
    protected string $view = 'livewire.configurable-widget';
    public bool $editMode = true;
    public array $data = [];
    public array $store = [];
    #[On(UserWidgetLayoutManager::LAYOUT_EDIT_MODE)]
    public function handleToggleEditMode($state)
    {
        $this->editMode = $state;
    }

    public function configureActionUsing(Action $action)
    {
        return $action;
    }

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
        return $this->getContent();
    }

    public function configure(): Action {
        $action = Action::make('configure')
            ->hiddenLabel()
            ->icon(Heroicon::Cog8Tooth)
            ->color('secondary')
            ->action(fn($data) => $this->updateWidgetConfiguration($data))
            ->modal();

        return $this->configureActionUsing($action)->fillForm(fn() => $this->store['configuration'] ?? []);
    }

    public function updateWidgetConfiguration(array $data): void {
        $componentId = $this->store['id'] ?? null;
        if (!empty($componentId)) {
            $this->dispatch(self::UPDATE_WIDGET_CONFIG, componentId: $componentId, state: $data);
            $this->store['configuration'] = $data;
        }
    }

    public static function getDefaultConfiguration(): array {
        return [];
    }

    public function getConfig(string $key, mixed $default = null): mixed {
        return ($this->store['configuration'] ?? [])[$key] ?? $default;
    }

    public function setConfig(string $key, mixed $value = null): void {
        $this->store['configuration'] ??= [];
        $this->store[$key] = $value;
    }
}
