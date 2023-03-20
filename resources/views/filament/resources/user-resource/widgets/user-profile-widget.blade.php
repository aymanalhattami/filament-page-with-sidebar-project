<x-filament::widget>
    <div class="bg-white shadow rounded-xl">
        <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                <div class="min-w-0 flex-1">
                    <!-- Profile -->
                    <div class="flex items-center">
                        @if ($record->hasMedia())
                            <img class="hidden h-16 w-16 rounded-full sm:block"
                                src="{{ $record->getFirstMediaUrl() }}"
                                alt="">
                        @else
                            <div
                                class="bg-gray-300 content-center flex h-16 items-center justify-center rounded-full w-16">
                                <span class='text-3xl text-bold'>{{ substr($record->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center">
                                <img class="h-16 w-16 rounded-full sm:hidden"
                                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2.6&amp;w=256&amp;h=256&amp;q=80"
                                    alt="">
                                <h1
                                    class="ltr:ml-3 rtl:mr-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9">
                                    {{ $record->name }}</h1>
                            </div>
                            <dl class="mt-6 flex flex-col ltr:sm:ml-3 rtl:mr-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                <dt class="sr-only">Company</dt>
                                <dd class="flex items-center text-sm font-medium text-gray-500 ltr:sm:mr-6 rtl:sm:ml-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="ltr:mr-1.5 rtl:ml-1.5 h-5 w-5 flex-shrink-0 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>

                                    <span>{{ $record->email }}</span>
                                </dd>
                                <dt class="sr-only">Account status</dt>
                                <dd
                                    class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 rtl:sm:mr-6 ltr:sm:ml-6 sm:mt-0">
                                    {{-- <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-green-400" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                            clip-rule="evenodd"></path>
                                    </svg> --}}
                                    {{-- <x-badges text="{{ $record->status }}" /> --}}
                                    <div class="text-xs">
                                        <span>{{ trans('Created at') }} : </span>
                                        <span>{{ $record->created_at }}</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex ltr:space-x-3 rtl:space-x-reverse md:mt-0 ltr:md:ml-4 rtl:mr-4">
                    <a href="{{ route('filament.resources.users.edit', $record) }}"
                        class="text-blue">{{ trans('Edit') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-filament::widget>
