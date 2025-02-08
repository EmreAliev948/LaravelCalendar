<x-layout>
    <div class="max-w-2xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">My Friends</h1>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            @forelse ($friends as $friend)
                <div class="border-b border-gray-200 dark:border-gray-700 last:border-0">
                    <a href="{{ route('friend.calendar', $friend->id) }}" 
                       class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">
                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="ml-4 text-lg text-gray-700 dark:text-gray-200">{{ $friend->name }}</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    <p>You haven't added any friends yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>