<?php

namespace App\Livewire\Traits;

use App\Http\Livewire\UserWidgetLayoutManager;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\On;

trait HasConfigurableWidget
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public bool $editMode = false;
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

    public function configureAction(): Action {
        $action = Action::make('configureAction')
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
