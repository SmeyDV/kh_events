<x-admin-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('User Management') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <!-- Search and Filter Bar -->
          <form method="GET" action="" class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users by name or email..." class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
            <select name="role" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
              <option value="">All Roles</option>
              @foreach($roles as $role)
              <option value="{{ $role->slug }}" @if(request('role')==$role->slug) selected @endif>{{ ucfirst($role->name) }}</option>
              @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
          </form>

          <!-- Range and Total Count -->
          <div class="mb-2 text-sm text-gray-600 dark:text-gray-300">
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
          </div>

          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'role', 'direction' => request('direction', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:underline flex items-center">
                    Role
                    @if(request('sort') === 'role')
                    @if(request('direction', 'asc') === 'asc')
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    @else
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    @endif
                    @endif
                  </a>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->role->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{-- Actions (edit, delete, etc.) can go here --}}
                  <span class="text-gray-400">-</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <div class="mt-4">
            {{ $users->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>