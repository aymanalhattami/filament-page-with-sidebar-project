<x-filament-page-with-sidebar::page>
    <x-filament-panels::page>
        <div>
            <form wire:submit="save">
                {{ $this->form }}
                
                <div class="mt-4">
                    {{ $this->saveAction }}
                </div>
            </form>
        </div>
    </x-filament-panels::page>
</x-filament-page-with-sidebar::page>
