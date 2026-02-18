@php
    $currentStep = 4;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Economic Sector</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Declare your professional occupation and fiscal capacity. This data calibrates your credit limit and investment eligibility within our secondary market.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step4') }}" id="step4Form" class="ajax-form">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Occupation -->
            <div class="space-y-4">
                <label for="occupation" class="premium-label">Professional Role</label>
                <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $user->occupation) }}" 
                       class="premium-input" placeholder="e.g., Clinical Architect, Trade Specialist">
            </div>
            
            <!-- Employer -->
            <div class="space-y-4">
                <label for="employer" class="premium-label">Institutional Affiliation / Employer</label>
                <input type="text" id="employer" name="employer" value="{{ old('employer', $user->employer) }}" 
                       class="premium-input" placeholder="Enter Organization or 'Sovereign / Self-Employed'">
            </div>
            
            <!-- Income -->
            <div class="space-y-4 md:col-span-2">
                <label for="monthly_income" class="premium-label">Operational Monthly Yield (TZS)</label>
                <div class="relative">
                    <input type="number" id="monthly_income" name="monthly_income" value="{{ old('monthly_income', $user->monthly_income) }}" 
                           class="premium-input pl-16" min="0" step="0.01" placeholder="0.00">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-300">CURR</div>
                </div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 italic">Approximate net monthly financial inflow in Tanzanian Shillings</p>
                @error('monthly_income')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="mt-12 p-10 bg-[#015425] rounded-[2.5rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 group shadow-2xl shadow-green-900/20">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20">
                    <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xl font-black tracking-tight">Fiscal Validation</h4>
                    <p class="text-[10px] font-bold text-green-200 uppercase tracking-widest opacity-80">Economic Capacity Analysis Stage</p>
                </div>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <a href="{{ route('member.membership.step3') }}" class="flex-1 md:flex-none px-10 py-5 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-center">
                    PREV 03
                </a>
                <button type="submit" class="flex-1 md:flex-none px-12 py-5 bg-white text-[#015425] rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all">
                    COMMIT SECTOR
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
