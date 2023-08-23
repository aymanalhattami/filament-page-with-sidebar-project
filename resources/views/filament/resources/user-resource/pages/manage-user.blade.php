<x-filament-page-with-sidebar::page>
    <x-filament-panels::page>
        <div>
            <form wire:submit="save">
                {{ $this->form }}

                <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
                <button class="mt-4" type="submit">
                    Submit
                </button>
            </form>
        </div>
    </x-filament-panels::page>
</x-filament-page-with-sidebar::page>
