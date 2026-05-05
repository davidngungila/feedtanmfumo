@extends('layouts.admin')

@section('title', 'Add Location')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Location</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.locations.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('region') is-invalid @enderror" 
                                           id="region" name="region" value="{{ old('region') }}" required>
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="regioncode" class="form-label">Region Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('regioncode') is-invalid @enderror" 
                                           id="regioncode" name="regioncode" value="{{ old('regioncode') }}" 
                                           maxlength="5" required placeholder="e.g., AR">
                                    @error('regioncode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                           id="district" name="district" value="{{ old('district') }}" required>
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="districtcode" class="form-label">District Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('districtcode') is-invalid @enderror" 
                                           id="districtcode" name="districtcode" value="{{ old('districtcode') }}" 
                                           maxlength="5" required placeholder="e.g., ARC">
                                    @error('districtcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ward" class="form-label">Ward <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ward') is-invalid @enderror" 
                                           id="ward" name="ward" value="{{ old('ward') }}" required>
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="wardcode" class="form-label">Ward Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('wardcode') is-invalid @enderror" 
                                           id="wardcode" name="wardcode" value="{{ old('wardcode') }}" 
                                           maxlength="5" required placeholder="e.g., SEK">
                                    @error('wardcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="street" class="form-label">Street <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('street') is-invalid @enderror" 
                                           id="street" name="street" value="{{ old('street') }}" required>
                                    @error('street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="places" class="form-label">Places <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('places') is-invalid @enderror" 
                                              id="places" name="places" rows="3" required placeholder="e.g., Kariakoo Market, Posta, Swahili Street">{{ old('places') }}</textarea>
                                    <div class="form-text">Separate multiple places with commas</div>
                                    @error('places')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Locations
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger me-2">
                                            <i class="fas fa-times me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Save Location
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        <strong>Please correct the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uppercase codes
    const codeInputs = ['regioncode', 'districtcode', 'wardcode'];
    
    codeInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    // Auto-hide error messages after 10 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 10000);
});
</script>
@endpush
