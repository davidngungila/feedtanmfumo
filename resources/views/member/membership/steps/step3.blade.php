@php
    $currentStep = 3;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Geospatial Data</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Map your primary residential coordinates. Physical location validation is a prerequisite for localized community asset management.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step3') }}" id="step3Form" class="ajax-form">
        @csrf
        
        <div class="space-y-10">
            <!-- Full Address -->
            <div class="space-y-4">
                <label for="address" class="premium-label">Residential Deployment Address <span class="text-red-500">*</span></label>
                <textarea id="address" name="address" class="premium-input min-h-[120px] resize-none" 
                          placeholder="Enter your comprehensive residential coordinates..." required>{{ old('address', $user->address) }}</textarea>
                @error('address')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- City -->
                <div class="space-y-4">
                    <label for="city" class="premium-label">Municipal Hub (City) <span class="text-red-500">*</span></label>
                    <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" 
                           class="premium-input" placeholder="City Name" required>
                    @error('city')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
                </div>
                
                <!-- Region -->
                <div class="space-y-4">
                    <label for="region" class="premium-label">Administrative Region <span class="text-red-500">*</span></label>
                    <input type="text" id="region" name="region" value="{{ old('region', $user->region) }}" 
                           class="premium-input" placeholder="Region Name" required>
                    @error('region')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
                </div>
                
                <!-- Postal Code -->
                <div class="space-y-4">
                    <label for="postal_code" class="premium-label">Postal Index</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" 
                           class="premium-input" placeholder="Code (Optional)">
                </div>
            </div>
            
            <div class="mt-12 p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#015425] shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Localization Protocol</p>
                        <p class="text-xs font-bold text-gray-600">Primary residence verified through institutional mapping.</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <a href="{{ route('member.membership.step2') }}" class="px-8 py-4 bg-white hover:bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-gray-200 shadow-sm">
                        PREVIOUS
                    </a>
                    <button type="submit" class="luxury-button flex items-center gap-4">
                        Commit Coordinates
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
