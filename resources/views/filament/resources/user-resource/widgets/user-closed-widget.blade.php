<x-filament::widget>
    @if ($record->status == App\CoreLogic\Enums\StatusEnum::Closed->value)
        <x-alerts.warning message='Sorry, you can not manage account in "closed" status' />
    @endif
</x-filament::widget>
