<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Pending Events
    </h2>
  </x-slot>
  <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
        @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Organizer</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($pendingEvents as $event)
            <tr>
              <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">{{ $event->title }}</td>
              <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $event->organizer->name ?? '-' }}</td>
              <td class="px-4 py-2 text-yellow-600 font-semibold">{{ ucfirst($event->status) }}</td>
              <td class="px-4 py-2">
                <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold mr-2">Approve</button>
                </form>
                <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold">Reject</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No pending events found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>