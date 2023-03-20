<x-filament::widget>
    <x-filament::card>
        <div class="divide-y divide-gray-200">
            <div class="space-y-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Login') }}</h3>
                <p class="max-w-2xl text-sm text-gray-500">
                    <span>{{ __('Last Login Information for') }}</span>
                    <b class="text-blue-500">{{ $record->name }}</b>
                </p>
            </div>
            <div class="mt-6">
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Event') }}</dt>
                        <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <span class="flex-grow">{{ $activity?->event }}</span>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                        <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <span class="flex-grow">{{ $activity?->description }}</span>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('IP') }}</dt>
                        <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <span class="flex-grow">{{ $activity?->properties['ip'] }}</span>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('User Agent') }}</dt>
                        <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <span class="flex-grow">{{ $activity?->properties['user_agent'] }}</span>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Login At') }}</dt>
                        <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <span class="flex-grow">{{ $activity?->created_at }}</span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
