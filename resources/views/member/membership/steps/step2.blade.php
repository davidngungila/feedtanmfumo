@php
    $currentStep = 2;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Identity Matrix</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Establish your core profile within our data infrastructure. Verified identity parameters are essential for capital deployment authorization.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step2') }}" id="step2Form" class="ajax-form">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Full Name -->
            <div class="space-y-4">
                <label for="name" class="premium-label">Full Legal Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                       class="premium-input bg-gray-100 cursor-not-allowed" readonly>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 italic">Immutable Record - Linked to Authentication Hub</p>
            </div>
            
            <!-- Email -->
            <div class="space-y-4">
                <label for="email" class="premium-label">Registered Channel (Email)</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="premium-input bg-gray-100 cursor-not-allowed" readonly>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 italic">Verified Communications Node</p>
            </div>
            
            <!-- Primary Phone -->
            <div class="space-y-4">
                <label for="phone" class="premium-label">Primary Mobile Gateway <span class="text-red-500">*</span></label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="premium-input" placeholder="e.g., +255 712 ..." required>
                @error('phone')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
            
            <!-- Alternate Phone -->
            <div class="space-y-4">
                <label for="alternate_phone" class="premium-label">Secondary Link (Optional)</label>
                <input type="text" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $user->alternate_phone) }}" 
                       class="premium-input" placeholder="Backup contact channel">
            </div>
            
            <!-- Gender -->
            <div class="space-y-4">
                <label for="gender" class="premium-label">Gender Verification <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="gender" name="gender" class="premium-input appearance-none pr-10" required>
                        <option value="">-- Select Vector --</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Institutional/Other</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('gender')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
            
            <!-- Date of Birth -->
            <div class="space-y-4">
                <label for="date_of_birth" class="premium-label">Chronological Index (DOB) <span class="text-red-500">*</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth" 
                       value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" 
                       class="premium-input" max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 italic">Minimum threshold: 18 Standard Years</p>
                @error('date_of_birth')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
            
            <!-- National ID -->
            <div class="space-y-4">
                <label for="national_id" class="premium-label">NIDA Identification Key <span class="text-red-500">*</span></label>
                <input type="text" id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" 
                       class="premium-input" placeholder="Enter NIDA Sequence" required>
                @error('national_id')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
            
            <!-- Marital Status -->
            <div class="space-y-4">
                <label for="marital_status" class="premium-label">Social Status Protocol <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="marital_status" name="marital_status" class="premium-input appearance-none pr-10" required>
                        <option value="">-- Select Status --</option>
                        <option value="single" {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('marital_status')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="mt-12 p-8 bg-gray-900 rounded-[2.5rem] text-white flex items-center justify-between group overflow-hidden relative">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] mb-1">Encrypted Transmission</p>
                <p class="text-xs font-medium text-gray-400 max-w-sm">All identity parameters are processed via AES-256 secure protocols for community safety.</p>
            </div>
            <div class="flex gap-4 relative z-10 shrink-0">
                <a href="{{ route('member.membership.step1') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                    Step 01
                </a>
                <button type="submit" class="luxury-button flex items-center gap-4">
                    Commit Identity
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
