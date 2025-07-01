<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Events') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-6">Upcoming Events</h1>

            <!-- Loading State -->
            <div id="loading" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 dark:border-gray-100"></div>
              <p class="mt-2">Loading events...</p>
            </div>

            <!-- Error State -->
            <div id="error" class="hidden text-center py-12">
              <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-4 rounded-lg">
                <h2 class="text-xl font-semibold">Error Loading Events</h2>
                <p class="mt-2">Failed to load events. Please try again.</p>
                <button onclick="loadEvents()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                  Retry
                </button>
              </div>
            </div>

            <!-- Content Container -->
            <div id="content" class="hidden">
              <!-- Filters -->
              <div class="mb-6 flex flex-wrap gap-4 items-center">
                <select id="cityFilter" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                  <option value="">All Cities</option>
                </select>
                <input type="search" id="searchInput" placeholder="Search events..." class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600" />
                <button onclick="applyFilters()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                  Filter
                </button>
                <button onclick="clearFilters()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                  Clear
                </button>
              </div>

              <!-- Events Grid -->
              <div id="eventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Events will be loaded here -->
              </div>

              <!-- Pagination -->
              <div id="pagination" class="mt-8 flex justify-center">
                <!-- Pagination will be loaded here -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentPage = 1;
    let currentCityId = '';
    let currentSearch = '';
    const cities = @json($cities);

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      populateCities();
      loadEvents();

      // Add search on Enter key
      document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          applyFilters();
        }
      });
    });

    // Populate cities dropdown
    function populateCities() {
      const citySelect = document.getElementById('cityFilter');
      cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city.id;
        option.textContent = city.name;
        citySelect.appendChild(option);
      });
    }

    // Load events from API
    async function loadEvents(page = 1) {
      try {
        showLoading(true);
        hideError();

        // Build API URL with filters
        let url = `/api/v1/events?page=${page}`;
        if (currentCityId) url += `&city_id=${currentCityId}`;
        if (currentSearch) url += `&q=${encodeURIComponent(currentSearch)}`;

        const response = await fetch(url, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
          displayEvents(data.data.data); // data.data.data because of pagination structure
          displayPagination(data.data);
          showContent();
        } else {
          throw new Error(data.message || 'Failed to load events');
        }

      } catch (error) {
        console.error('Error loading events:', error);
        showError();
      } finally {
        showLoading(false);
      }
    }

    // Search events by query
    async function searchEvents(query) {
      if (!query.trim()) {
        loadEvents(currentPage);
        return;
      }

      try {
        showLoading(true);

        const response = await fetch(`/api/v1/events/search?q=${encodeURIComponent(query)}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
          displayEvents(data.data.data);
          displayPagination(data.data);
          showContent();
        } else {
          throw new Error(data.message || 'Search failed');
        }

      } catch (error) {
        console.error('Error searching events:', error);
        showError();
      } finally {
        showLoading(false);
      }
    }

    // Display events in grid
    function displayEvents(events) {
      const grid = document.getElementById('eventsGrid');

      if (!events || events.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full text-center py-12">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">No events found</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Try adjusting your search or check back later.</p>
          </div>
        `;
        return;
      }

      grid.innerHTML = events.map(event => {
        const imageUrl = event.images && event.images.length > 0 ?
          `/storage/${event.images[0].image_path}` :
          'https://via.placeholder.com/300x200';

        return `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
              <a href="/events/${event.id}">
                <img src="${imageUrl}" 
                     alt="${event.title}" 
                     class="w-full h-48 object-cover">
              </a>
              <div class="p-4">
                <h2 class="text-xl font-bold mb-2">
                  <a href="/events/${event.id}" class="hover:text-blue-600">${event.title}</a>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">By ${event.organizer?.name || 'Unknown'}</p>
                <p class="text-gray-600 dark:text-gray-400 mb-2">${formatDate(event.start_date)}</p>
                <p class="text-gray-500 dark:text-gray-400 mb-2">${event.city?.name || 'N/A'}</p>
                <p class="text-gray-700 dark:text-gray-200">${truncateText(event.description, 100)}</p>
                <div class="mt-4 flex justify-between items-center">
                  <span class="font-bold text-lg">${event.ticket_price > 0 ? '$' + parseFloat(event.ticket_price).toFixed(2) : 'Free'}</span>
                  <a href="/events/${event.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View Details
                  </a>
                </div>
              </div>
            </div>
          `;
      }).join('');
    }

    // Display pagination
    function displayPagination(paginationData) {
      const pagination = document.getElementById('pagination');

      if (paginationData.last_page <= 1) {
        pagination.innerHTML = '';
        return;
      }

      let paginationHTML = '<nav class="flex justify-center space-x-2">';

      // Previous button
      if (paginationData.prev_page_url) {
        paginationHTML += `<button onclick="loadEvents(${paginationData.current_page - 1})" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Previous</button>`;
      }

      // Page numbers
      for (let i = 1; i <= paginationData.last_page; i++) {
        const isActive = i === paginationData.current_page;
        paginationHTML += `<button onclick="loadEvents(${i})" class="px-3 py-2 ${isActive ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700'} rounded hover:bg-blue-500">${i}</button>`;
      }

      // Next button
      if (paginationData.next_page_url) {
        paginationHTML += `<button onclick="loadEvents(${paginationData.current_page + 1})" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Next</button>`;
      }

      paginationHTML += '</nav>';
      pagination.innerHTML = paginationHTML;
    }

    // Apply filters
    function applyFilters() {
      currentCityId = document.getElementById('cityFilter').value;
      currentSearch = document.getElementById('searchInput').value;
      currentPage = 1;

      if (currentSearch) {
        searchEvents(currentSearch);
      } else {
        loadEvents(1);
      }
    }

    // Clear filters
    function clearFilters() {
      document.getElementById('cityFilter').value = '';
      document.getElementById('searchInput').value = '';
      currentCityId = '';
      currentSearch = '';
      currentPage = 1;
      loadEvents(1);
    }

    // Utility functions
    function showLoading(show) {
      document.getElementById('loading').style.display = show ? 'block' : 'none';
    }

    function showError() {
      document.getElementById('error').classList.remove('hidden');
      document.getElementById('content').classList.add('hidden');
    }

    function hideError() {
      document.getElementById('error').classList.add('hidden');
    }

    function showContent() {
      document.getElementById('content').classList.remove('hidden');
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    }

    function truncateText(text, limit) {
      if (text.length <= limit) return text;
      return text.substring(0, limit) + '...';
    }
  </script>
</x-app-layout>