<x-admin-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Pending Events for Approval') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pendingEvents as $event)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $event->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $event->organizer->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($event->status) }}</td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                  <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" onsubmit="return confirm('Approve this event?');">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Approve</button>
                  </form>
                  <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" onsubmit="return confirm('Reject this event?');">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Reject</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No pending events.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>