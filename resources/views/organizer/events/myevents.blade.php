<x-organizer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">Your Created Events</h3>
                        <a href="{{ route('organizer.events.create') }}" class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-center font-semibold text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-transform transform hover:scale-105">
                            Create New Event
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($events as $event)
                        <div class="group relative bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg rounded-xl transition-shadow duration-300 hover:shadow-2xl">
                            <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400/1e293b/ffffff?text=Event' }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-bold">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $event->start_date->format('M d, Y') }}</p>
                                <div class="mt-2">
                                    @if($event->status == 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Published
                                    </span>
                                    @elseif($event->status == 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending Approval
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-between items-center relative z-10">
                                    <a href="{{ route('organizer.events.edit', $event) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <a href="{{ route('organizer.events.show', $event) }}" class="absolute inset-0 z-0">
                                <span class="sr-only">View details for {{ $event->title }}</span>
                            </a>
                        </div>
                        @empty
                        <p>You have not created any events yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organizer-layout>