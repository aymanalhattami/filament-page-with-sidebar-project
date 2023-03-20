<x-filament-page-with-sidebar::page>
    <x-filament::page>
        <x-filament::form wire:submit.prevent="save">
            {{ $this->form }}

            <x-filament::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament::form>
    </x-filament::page>
</x-filament-page-with-sidebar::page>
