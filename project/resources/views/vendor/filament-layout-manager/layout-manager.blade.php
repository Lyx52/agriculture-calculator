{{-- resources/views/livewire/dynamic-grid.blade.php --}}

<div x-data="{ sortable: null }"
     x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('filament-layout-manager-styles', package:'asosick/filament-layout-manager'))]"
     x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('filament-layout-manager-scripts', package:'asosick/filament-layout-manager'))]"
     x-init="
        window.addEventListener('filament-layout-manager-scripts-loaded', () => {
            if (window.CustomSortableModule) window.CustomSortableModule.initialize();
        });
    "
    >
    <div class="flex justify-between w-full gap-y-8 py-8">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            {{$heading}}
        </h1>
        <div class="flex justify-end space-x-2">
            <div class="px-1 hidden md:flex">
                @if($editMode)
                    {{ $this->form }}
                @endif
                <div class="px-2">{{$this->editAction}}</div>
                <x-filament-actions::modals />
            </div>
        </div>
    </div>

    <div class="layout-manager-grid grid md:grid-cols-{{$columns}} gap-4" x-ref="grid">
        @foreach($container[$this->selectedLayout] ?? [] as $id => $component)
            <div wire:key="grid-item-{{ $id }}"
                 data-id="{{ $id }}"
                 class="layout-manager-widget p-1"
                 style="grid-column: span {{ $component['cols'] }} / span {{ $component['cols'] }}">

                @if($editMode)
                    <div class="layout-manager-edit-controls flex gap-1 px-2 py-1 mb-2">
                        <button wire:click="removeComponent('{{ $id }}')"
                            class="text-lg font-bold">
                            ×
                        </button>
                        <button
                            wire:click="toggleSize('{{ $id }}')"
                            class="p-1 text-lg">
                            {{$component['cols'] === $columns ? '←' : '→'}}
                        </button>
                        <button
                            wire:click="increaseSize('{{ $id }}')"
                            class="text-lg">
                            +
                        </button>
                        <button
                            wire:click="decreaseSize('{{ $id }}')"
                            class="text-lg">
                            -
                        </button>
                        <div class="handle cursor-move rounded-full p-1 text-lg">
                            &#x2725;
                        </div>
                    </div>
                @endif
                <livewire:dynamic-component
                    :is="$component['type']['widget_class']"
                    :data="$component['type']['data'] ?? []"
                    :container_key="$id"
                    :store="$component['store'] ?? []"
                    :key="$id.'-'.$component['cols']"
                />
            </div>
        @endforeach
    </div>
</div>
