<x-filament::page>
    <form action="{{ route('admin.questions') }}" method="post">
        @csrf
        <div>
            <label for="comment" class="block text-sm font-medium text-gray-700">Add your question</label>
            <div class="mt-1">
                <textarea name="question" rows="4" name="comment" id="comment" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
            <br>
            <x-filament::button type="submit">Ask</x-filament::button>
        </div>

    </form>
    @foreach($questions as $question)
        <ul role="list" class="divide-y divide-gray-200 border-2 rounded-2xl">
        <li class="relative bg-white py-5 px-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 hover:bg-gray-50">
            <div class="flex justify-between space-x-3">
                <div class="min-w-0 flex-1">
                    <a href="#" class="block focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="truncate text-sm font-medium text-gray-900">{{ $question->question  }}</p>
                    </a>
                </div>
                <time datetime="2021-01-27T16:35" class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ $question->created_at->diffForHumans() }}</time>
            </div>
            <div class="mt-1">
                <p class="text-sm text-gray-600 line-clamp-2">{{ $question->answer }}</p>
            </div>
        </li>
    </ul>
    @endforeach
</x-filament::page>
