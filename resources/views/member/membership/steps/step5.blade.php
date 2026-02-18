@php
    $currentStep = 5;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Financial Conduit</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Map your institutional banking link. This conduit enables automated capital settlements and secure dividend distribution protocols.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step5') }}" id="step5Form" class="ajax-form">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Bank Name -->
            <div class="space-y-4">
                <label for="bank_name" class="premium-label">Banking Institution</label>
                <div class="relative">
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" 
                           class="premium-input pl-14" placeholder="e.g., CRDB, NMB, Standard Chartered">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10zm3 3h2v5H7v-5zm4 0h2v5h-2v-5zm4 0h2v5h-2v-5z"></path></svg>
                    </div>
                </div>
            </div>
            
            <!-- Bank Branch -->
            <div class="space-y-4">
                <label for="bank_branch" class="premium-label">Operational Branch Node</label>
                <input type="text" id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $user->bank_branch) }}" 
                       class="premium-input" placeholder="Enter branch location/identifier">
            </div>
            
            <!-- Account Number -->
            <div class="space-y-4">
                <label for="bank_account_number" class="premium-label">Primary Account Index</label>
                <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" 
                       class="premium-input font-mono tracking-wider" placeholder="X-XXXX-XXXX-X">
            </div>
            
            <!-- Payment Ref -->
            <div class="space-y-4">
                <label for="payment_reference_number" class="premium-label">Strategic Payment Reference</label>
                <input type="text" id="payment_reference_number" name="payment_reference_number" value="{{ old('payment_reference_number', $user->payment_reference_number) }}" 
                       class="premium-input" placeholder="Reference for initial onboarding fee">
            </div>
        </div>
        
        <div class="mt-12 p-8 bg-blue-900 rounded-[2.5rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 group overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10 flex items-center gap-5">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-blue-200 backdrop-blur-md">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-black tracking-tight">Secure Settlement Hub</h4>
                    <p class="text-[10px] font-bold text-blue-300 uppercase tracking-widest">Financial Interface Authorization Stage</p>
                </div>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto relative z-10">
                <a href="{{ route('member.membership.step4') }}" class="flex-1 md:flex-none px-8 py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-center">
                    BACK
                </a>
                <button type="submit" class="luxury-button flex-1 md:flex-none flex items-center justify-center gap-4">
                    MAP CONDUIT
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
