<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- MERGED CONTENT STARTS HERE --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ Auth::user()->name }}
                            </h2>
                    
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ Auth::user()->email }}
                            </p>
                        </header>
                    
                        <div class="mt-6 space-y-6">
                           {{-- Show Organizer-specific links --}}
                           @if (Auth::user()->role->name == 'organizer')
                               <div class="flex items-center gap-4">
                                   <a href="{{ route('organizer.my-events') }}" class="text-blue-500 hover:underline">{{ __('My Events') }}</a>
                                   <a href="{{ route('organizer.events.create') }}" class="text-blue-500 hover:underline">{{ __('Create New Event') }}</a>
                               </div>
                           @endif

                           {{-- Show booking history for regular users --}}
                           @if(Auth::user()->role->name == 'user')
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('bookings.index') }}" class="text-blue-500 hover:underline">{{ __('My Bookings') }}</a>
                                </div>
                           @endif
                        </div>
                    </section>
                </div>
            </div>
            {{-- MERGED CONTENT ENDS HERE --}}

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
