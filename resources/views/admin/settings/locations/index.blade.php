@extends('layouts.admin')

@section('page-title', 'Location Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Location Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage Tanzania regions, districts, and locations</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Location
                </a>
                <a href="{{ route('admin.locations.import.page') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-green-300 rounded-md hover:bg-opacity-30 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Import CSV
                </a>
                <a href="{{ route('admin.locations.export') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-green-300 rounded-md hover:bg-opacity-30 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Locations</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total']) }}</p>
            <p class="text-xs text-gray-500 mt-1">All regions</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Regions</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['regions']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Tanzania regions</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Districts</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['districts']) }}</p>
            <p class="text-xs text-gray-500 mt-1">All districts</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Wards</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($stats['wards']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Local wards</p>
        </div>
    </div>

    <!-- Search and Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Locations</label>
                <div class="relative">
                    <input type="text" id="searchInput" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]" placeholder="Search by region, district, or ward...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Region</label>
                <select id="regionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Regions</option>
                    @foreach(DB::table('tanzania_locations')->distinct()->pluck('region') as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by District</label>
                <select id="districtFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Districts</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Show</label>
                <select id="perPageSelect" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                    <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Operations Bar -->
    <div id="bulkActionsBar" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-sm text-blue-800">
                    <span id="selectedCount">0</span> location(s) selected
                </span>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="selectAllOnPage()" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition text-sm">
                    Select All on Page
                </button>
                <button type="button" onclick="clearSelection()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                    Clear Selection
                </button>
                <form id="bulkDeleteForm" action="{{ route('admin.locations.bulk-delete') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete the selected locations?')">
                    @csrf
                    <input type="hidden" name="ids" id="bulkIds">
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm">
                        Delete Selected
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Tanzania Locations</h2>
            <div class="text-sm text-gray-600">
                Showing {{ $locations->count() }} of {{ $locations->total() }} locations
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">District</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Street</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Places</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($locations as $location)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="location-checkbox rounded border-gray-300 text-[#015425] focus:ring-[#015425]" value="{{ $location->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $location->region }}</div>
                                <div class="text-sm text-gray-500">{{ $location->regioncode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $location->district }}</div>
                                <div class="text-sm text-gray-500">{{ $location->districtcode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $location->ward }}</div>
                                <div class="text-sm text-gray-500">{{ $location->wardcode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $location->street }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $location->places }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.locations.edit', $location->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No locations found</p>
                                <p class="text-sm mt-1">Get started by adding your first location or importing from CSV.</p>
                                <div class="mt-4 space-x-3">
                                    <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Location
                                    </a>
                                    <a href="{{ route('admin.locations.import.page') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Import CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <script>
    // Auto-Apply Search and Filters JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const regionFilter = document.getElementById('regionFilter');
        const districtFilter = document.getElementById('districtFilter');
        const perPageSelect = document.getElementById('perPageSelect');
        let searchTimeout;

        // Debounced search function
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500); // 500ms delay
        }

        // Apply all filters by updating URL
        function applyFilters() {
            const url = new URL(window.location);
            
            // Update search parameter
            if (searchInput && searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            
            // Update region filter
            if (regionFilter && regionFilter.value) {
                url.searchParams.set('region', regionFilter.value);
            } else {
                url.searchParams.delete('region');
            }
            
            // Update district filter
            if (districtFilter && districtFilter.value) {
                url.searchParams.set('district', districtFilter.value);
            } else {
                url.searchParams.delete('district');
            }
            
            // Reset to first page when filters change
            url.searchParams.set('page', '1');
            
            // Navigate to new URL
            window.location.href = url.toString();
        }

        // Auto-apply search with debouncing
        if (searchInput) {
            searchInput.addEventListener('input', debounceSearch);
        }

        // Auto-apply region filter
        if (regionFilter) {
            regionFilter.addEventListener('change', function() {
                // Clear district filter when region changes
                if (districtFilter) {
                    districtFilter.innerHTML = '<option value="">All Districts</option>';
                    
                    if (this.value) {
                        // Load districts for selected region
                        fetch(`/admin/locations/search?region=${this.value}`)
                            .then(response => response.json())
                            .then(data => {
                                const districts = [...new Set(data.map(item => item.district))];
                                districts.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district;
                                    option.textContent = district;
                                    districtFilter.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error loading districts:', error));
                    }
                }
                
                applyFilters();
            });
        }

        // Auto-apply district filter
        if (districtFilter) {
            districtFilter.addEventListener('change', applyFilters);
        }

        // Handle per page selection
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const url = new URL(window.location);
                url.searchParams.set('per_page', this.value);
                url.searchParams.set('page', '1'); // Reset to first page
                window.location.href = url.toString();
            });
        }

        // Restore filter values from URL
        function restoreFilterValues() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Restore search
            if (searchInput && urlParams.get('search')) {
                searchInput.value = urlParams.get('search');
            }
            
            // Restore region filter
            if (regionFilter && urlParams.get('region')) {
                regionFilter.value = urlParams.get('region');
                // Trigger change to load districts
                regionFilter.dispatchEvent(new Event('change'));
            }
            
            // Restore district filter
            if (districtFilter && urlParams.get('district')) {
                // Wait for districts to load, then set value
                setTimeout(() => {
                    districtFilter.value = urlParams.get('district');
                }, 100);
            }
        }

        // Initialize filter values
        restoreFilterValues();
    });

    // Bulk Operations JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const locationCheckboxes = document.querySelectorAll('.location-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');
        const bulkIds = document.getElementById('bulkIds');

        // Handle select all checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                locationCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsBar();
            });
        }

        // Handle individual checkbox changes
        locationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActionsBar();
                updateSelectAllCheckbox();
            });
        });

        // Update bulk actions bar
        function updateBulkActionsBar() {
            const checkedBoxes = document.querySelectorAll('.location-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (selectedCount) {
                selectedCount.textContent = count;
            }
            
            if (count > 0 && bulkActionsBar) {
                bulkActionsBar.classList.remove('hidden');
                if (bulkIds) {
                    const ids = Array.from(checkedBoxes).map(cb => cb.value);
                    bulkIds.value = JSON.stringify(ids);
                }
            } else if (bulkActionsBar) {
                bulkActionsBar.classList.add('hidden');
                if (bulkIds) {
                    bulkIds.value = '';
                }
            }
        }

        // Update select all checkbox state
        function updateSelectAllCheckbox() {
            const checkedBoxes = document.querySelectorAll('.location-checkbox:checked');
            const totalBoxes = document.querySelectorAll('.location-checkbox');
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedBoxes.length === totalBoxes.length && totalBoxes.length > 0;
                selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes.length;
            }
        }

        // Make functions globally accessible
        window.selectAllOnPage = function() {
            locationCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBulkActionsBar();
            updateSelectAllCheckbox();
        };

        window.clearSelection = function() {
            locationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkActionsBar();
            updateSelectAllCheckbox();
        };

        window.updateBulkActionsBar = updateBulkActionsBar;
        window.updateSelectAllCheckbox = updateSelectAllCheckbox;
    });

    // Function to filter table rows
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRegion = regionFilter.value.toLowerCase();
        const selectedDistrict = districtFilter.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 0) return; // Skip empty rows
            
            const region = cells[0].textContent.toLowerCase();
            const district = cells[2].textContent.toLowerCase();
            const ward = cells[4].textContent.toLowerCase();
            const street = cells[6].textContent.toLowerCase();
            const places = cells[7].textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || 
                region.includes(searchTerm) || 
                district.includes(searchTerm) || 
                ward.includes(searchTerm) || 
                street.includes(searchTerm) || 
                places.includes(searchTerm);
                
            const matchesRegion = selectedRegion === '' || region === selectedRegion;
            const matchesDistrict = selectedDistrict === '' || district === selectedDistrict;
            
            if (matchesSearch && matchesRegion && matchesDistrict) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterTable);
    regionFilter.addEventListener('change', function() {
        // Update district filter based on selected region
        const selectedRegion = this.value;
        districtFilter.innerHTML = '<option value="">All Districts</option>';
        
        if (selectedRegion) {
            const districts = [...new Set(Array.from(document.querySelectorAll('tbody tr'))
                .filter(row => row.style.display !== 'none')
                .map(row => row.cells[2].textContent.trim()))];
            
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtFilter.appendChild(option);
            });
        }
        
        filterTable();
    });
    districtFilter.addEventListener('change', filterTable);
});
</script>
@endsection

@if(session('success'))
<div id="flash-success" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="flash-error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
@endif

<script>
// Auto-hide flash messages after 5 seconds
setTimeout(function() {
    const successMsg = document.getElementById('flash-success');
    const errorMsg = document.getElementById('flash-error');
    
    if (successMsg) {
        successMsg.remove();
    }
    if (errorMsg) {
        errorMsg.remove();
    }
}, 5000);

// CSV Import Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('csv_file');
    const fileName = document.getElementById('file-name');
    const importBtn = document.getElementById('import-btn');
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file type
            const allowedTypes = ['text/csv', 'text/plain', 'application/csv'];
            if (!allowedTypes.includes(file.type) && !file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
                alert('Please select a CSV file.');
                resetImportForm();
                return;
            }
            
            // Validate file size (10MB max)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 10MB.');
                resetImportForm();
                return;
            }
            
            fileName.textContent = file.name;
            importBtn.disabled = false;
        } else {
            resetImportForm();
        }
    });
    
    // Handle form submission
    const importForm = document.querySelector('form[action*="import"]');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            
            if (!file) {
                e.preventDefault();
                alert('Please select a CSV file to import.');
                return;
            }
            
            // Show loading state
            importBtn.disabled = true;
            importBtn.innerHTML = `
                <svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Importing...
            `;
        });
    }
});

// Reset import form function
function resetImportForm() {
    const fileInput = document.getElementById('csv_file');
    const fileName = document.getElementById('file-name');
    const importBtn = document.getElementById('import-btn');
    
    fileInput.value = '';
    fileName.textContent = 'No file selected';
    importBtn.disabled = true;
    importBtn.innerHTML = `
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
        Import Locations
    `;
}
</script>
