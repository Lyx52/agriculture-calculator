<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex justify-end">
            @if($editMode)
            {{ $this->configure() }}
                @endif
        </div>
        {{ $this->content }}
    </x-filament::section>
    <x-filament-actions::modals />
</x-filament-widgets::widget>
