<x-filament::widget>
    <x-filament::card>
        <div>
            <span>{{ __('User Current Status is') }}</span>    
            <span>{{ $record?->status }}</span>
        </div>
    </x-filament::card>
</x-filament::widget>
