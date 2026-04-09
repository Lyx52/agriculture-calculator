<?php
namespace App\Http\Livewire;

use App\Livewire\ConfigurableWidget;
use Asosick\FilamentLayoutManager\Http\Livewire\LayoutManager;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class UserWidgetLayoutManager extends LayoutManager  {
    public const LAYOUT_EDIT_MODE = 'LAYOUT_EDIT_MODE';
    public int $selectedLayout;
    public array $dashboardLayouts = [];
    protected function load(): void
    {
        $this->container = user()->dashboardLayouts->pluck('layout', 'id')->toArray();
    }

    #[On(ConfigurableWidget::UPDATE_WIDGET_CONFIG)]
    public function handleWidgetConfigurationUpdate(string $componentId, array $state)
    {
        $this->container[$this->selectedLayout][$componentId]['store']['configuration'] = $state;
    }

    protected function save(): void
    {
        $user = user();
        foreach ($this->container as $id => $layout) {
            $user->dashboardLayouts()
                ->where('id', $id)
                ->update([
                    'layout' => $layout
                ]);
        }
        $user->selected_layout_id = $this->selectedLayout;
        $user->save();
    }

    public function editAction(): Action
    {
        return parent::editAction()
            ->color(fn() => $this->editMode ? 'success' : 'primary')
            ->label(fn() => $this->editMode ? 'Saglabāt izkārtojumu' : 'Labot izkārtojumu')
            ->icon(fn() => $this->editMode ? Heroicon::CheckCircle : Heroicon::Cog8Tooth);
    }

    public function toggleEditMode(): void
    {
        if ($this->editMode) {
            $this->saveLayout();
        }

        parent::toggleEditMode();
        $this->dispatch(self::LAYOUT_EDIT_MODE, state: $this->editMode);
    }

    protected function saveNotification(): void
    {
        Notification::make()
            ->seconds(0.8)
            ->title('Izkārtojums saglabāts!')
            ->success()
            ->send();
    }

    public function form(Schema $schema): Schema
    {
        $user = user();
        return parent::form($schema)
            ->inline()
            ->schema([
                Select::make('selectedLayout')
                    ->model($user)
                    ->hiddenLabel()
                    ->relationship('dashboardLayouts', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->distinct()
                            ->required()
                            ->label('Izkārtojuma nosaukums'),
                        Hidden::make('user_id')->default($user->id)
                    ])
                    ->native(false)
                    ->live(),
                Select::make('selectedComponent')
                    ->hiddenLabel()
                    ->native(false)
                    ->options(Arr::get($this->settings, 'select_options', []))
                    ->suffixAction(Action::make('add_widget')
                        ->icon('heroicon-m-plus')
                        ->color('primary')
                        ->action(fn() => $this->addComponent())
                    )
            ]);
    }

    public function mount(?array $settings = []): void
    {
        parent::mount($settings);
        $user = user();
        if ($user->dashboardLayouts->isEmpty()) {
            $user->dashboardLayouts()->create([
                'name' => 'Noklusētais'
            ]);
        }

        $this->selectedLayout = $user->dashboardLayouts()->find($user->selected_layout_id)?->id ?? $user->dashboardLayouts()->first()?->id;
        $this->editMode = Session::get(self::LAYOUT_EDIT_MODE, false);
    }

    public function addComponent(): void
    {
        if (!isset($this->selectedComponent) || !$this->editMode) {
            return;
        }

        $componentId = uniqid();
        $widgetComponent = $this->settings['components'][$this->selectedComponent];
        $widgetClass = $widgetComponent['widget_class'];
        $configuration = [];
        if (method_exists($widgetClass, 'getDefaultConfiguration')) {
            $configuration = $widgetClass::getDefaultConfiguration();
        }

        $this->container[$this->selectedLayout][$componentId] = [
            'cols' => 1,
            'type' => $widgetComponent,
            'store' => [
                'id' => $componentId,
                'configuration' => $configuration
            ],
        ];
    }

    public function removeComponent(string $componentId): void
    {
        if (! $this->editMode) {
            return;
        }

        unset($this->container[$this->selectedLayout][$componentId]);
    }

    private function refocusToLayoutInUse(): void
    {
        if ($this->container[$this->selectedLayout] ?? []) {
            return;
        }
        $i = 0;
        while ($i < count($this->container)) {
            if (count($this->container[$i] ?? []) != 0) {
                $this->selectedLayout = $i;

                return;
            }
            $i = $i + 1;
        }
        $this->selectedLayout = 0;
    }

    public function updateLayout(array $orderedIds): void
    {
        if (! $this->editMode) {
            return;
        }
        $sortedData = [];
        foreach ($orderedIds as $key) {
            if (isset($this->container[$this->selectedLayout][$key])) {
                $sortedData[$key] = $this->container[$this->selectedLayout][$key];
            }
        }
        if (count($sortedData) === count($this->container[$this->selectedLayout])) {
            $this->container[$this->selectedLayout] = $sortedData;
        }
    }

    public function toggleSize(string $id): void
    {
        if (! $this->editMode) {
            return;
        }
        $cols = $this->container[$this->selectedLayout][$id]['cols'];
        $this->container[$this->selectedLayout][$id]['cols'] = $cols === $this->columns ? 1 : $this->columns;
    }

    public function increaseSize(string $id): void
    {
        if (! $this->editMode) {
            return;
        }
        $cols = $this->container[$this->selectedLayout][$id]['cols'];
        $this->container[$this->selectedLayout][$id]['cols'] = min($this->columns, $cols + 1);
    }

    public function decreaseSize(string $id): void
    {
        if (! $this->editMode) {
            return;
        }
        $cols = $this->container[$this->selectedLayout][$id]['cols'];
        $this->container[$this->selectedLayout][$id]['cols'] = max(1, $cols - 1);
    }
}
