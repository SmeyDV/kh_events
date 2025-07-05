<x-main-layout>
    <div class="relative">
        <x-welcome.hero-section />

        <div class="relative z-10 bg-white dark:bg-gray-900">
            <x-welcome.categories-section :categories="$categories" />

            <x-welcome.mini-navbar :tabs="$tabs" />

            <x-welcome.event-section :upcomingEvents="$upcomingEvents" />

            <x-welcome.organizer-cta />
        </div>
    </div>
</x-main-layout>