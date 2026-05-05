@extends('layouts.admin')

@section('title', 'Location Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tanzania Location Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Location
                        </a>
                        <a href="{{ route('admin.locations.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-1"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search locations...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="regionFilter">
                                <option value="">All Regions</option>
                                @foreach(DB::table('tanzania_locations')->distinct()->pluck('region') as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="districtFilter">
                                <option value="">All Districts</option>
                            </select>
                        </div>
                    </div>

                    <!-- Locations Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Region</th>
                                    <th>Region Code</th>
                                    <th>District</th>
                                    <th>District Code</th>
                                    <th>Ward</th>
                                    <th>Ward Code</th>
                                    <th>Street</th>
                                    <th>Places</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr>
                                        <td>{{ $location->region }}</td>
                                        <td><span class="badge bg-info">{{ $location->regioncode }}</span></td>
                                        <td>{{ $location->district }}</td>
                                        <td><span class="badge bg-secondary">{{ $location->districtcode }}</span></td>
                                        <td>{{ $location->ward }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $location->wardcode }}</span></td>
                                        <td>{{ $location->street }}</td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($location->places, 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.locations.edit', $location->id) }}" 
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.locations.destroy', $location->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this location?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No locations found.</p>
                                            <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
                                                Add First Location
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($locations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $locations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const regionFilter = document.getElementById('regionFilter');
    const districtFilter = document.getElementById('districtFilter');

    // Search functionality
    function performSearch() {
        const query = searchInput.value.trim();
        const region = regionFilter.value;
        const district = districtFilter.value;
        
        let url = new URL(window.location);
        url.searchParams.set('search', query);
        url.searchParams.set('region', region);
        url.searchParams.set('district', district);
        
        window.location.href = url.toString();
    }

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Region filter change
    regionFilter.addEventListener('change', function() {
        const region = this.value;
        districtFilter.innerHTML = '<option value="">All Districts</option>';
        
        if (region) {
            fetch(`/admin/locations/search?q=${region}&type=district`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.district;
                        option.textContent = item.district;
                        districtFilter.appendChild(option);
                    });
                });
        }
        
        performSearch();
    });

    // District filter change
    districtFilter.addEventListener('change', performSearch);

    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
