<?php
namespace App\Http\Livewire;

use Asosick\FilamentLayoutManager\Http\Livewire\LayoutManager;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;

class UserWidgetLayoutManager extends LayoutManager  {
    public int $selectedLayout;
    public array $dashboardLayouts = [];
    protected function load(): void
    {
        $this->container = user()->dashboardLayouts->pluck('layout', 'id')->toArray();
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
    }

    protected function saveNotification(): void
    {
        Notification::make()
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
                'name' => 'Noklusētais izkārtojums'
            ]);
        }

        $this->selectedLayout = $user->selected_layout_id ?? $user->dashboardLayouts()->first()?->id;
    }

    public function addComponent(): void
    {
        if (!isset($this->selectedComponent) || !$this->editMode) {
            return;
        }

        $this->container[$this->selectedLayout][uniqid()] = [
            'cols' => 1,
            'type' => $this->settings['components'][$this->selectedComponent],
            'store' => [],
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
