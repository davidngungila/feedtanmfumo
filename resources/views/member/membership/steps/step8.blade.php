@php
    $currentStep = 8;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Collective Identity</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Declare your institutional or group affiliations. Collaborative integration within a registered entity enhances community resource accessibility.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step8') }}" id="step8Form" class="ajax-form">
        @csrf
        
        <div class="space-y-12">
            <!-- Registration Checkbox -->
            <div class="bg-gray-50/50 p-8 rounded-[2.5rem] border border-gray-100 group transition-all hover:bg-white hover:shadow-xl">
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_group_registered" value="1" 
                               {{ old('is_group_registered', $user->is_group_registered) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-10 bg-white border-2 border-gray-200 rounded-xl flex items-center justify-center peer-checked:bg-[#015425] peer-checked:border-[#015425] transition-all">
                             <svg class="w-6 h-6 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <span class="ml-5 text-sm font-black text-gray-900 group-hover:text-[#015425] transition-colors uppercase tracking-tight">Is your group registered with Government Authorities?</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Group Name -->
                <div class="space-y-4">
                    <label for="group_name" class="premium-label">Institutional Group Name</label>
                    <input type="text" id="group_name" name="group_name" value="{{ old('group_name', $user->group_name) }}" 
                           class="premium-input" placeholder="Official Registered Entity Name">
                </div>
                
                <!-- Group Leaders -->
                <div class="space-y-4">
                    <label for="group_leaders" class="premium-label">Authorized Group Directives</label>
                    <input type="text" id="group_leaders" name="group_leaders" value="{{ old('group_leaders', $user->group_leaders) }}" 
                           class="premium-input" placeholder="Names of authorized leaders">
                </div>
                
                <!-- Group Bank -->
                <div class="space-y-4">
                    <label for="group_bank_account" class="premium-label">Collective Asset Index (Bank Acct)</label>
                    <input type="text" id="group_bank_account" name="group_bank_account" value="{{ old('group_bank_account', $user->group_bank_account) }}" 
                           class="premium-input font-mono" placeholder="Group settlement account">
                </div>
            </div>
            
            <!-- Group Contacts -->
            <div class="space-y-4">
                <label for="group_contacts" class="premium-label">Institutional Communication Nodes</label>
                <textarea id="group_contacts" name="group_contacts" class="premium-input min-h-[120px] resize-none" 
                          placeholder="Comprehensive group contact details (Email, Mobile, Physical HQ)...">{{ old('group_contacts', $user->group_contacts) }}</textarea>
            </div>
            
            <div class="mt-12 p-10 bg-indigo-900 rounded-[3rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 group overflow-hidden relative shadow-2xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                <div class="relative z-10 flex items-center gap-6">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20">
                         <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-black">Collective Mandate</h4>
                        <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest opacity-80">Institutional Integration Stage</p>
                    </div>
                </div>
                
                <div class="flex gap-4 w-full md:w-auto relative z-10">
                    <a href="{{ route('member.membership.step7') }}" class="flex-1 md:flex-none px-10 py-5 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-center">
                        PHASE 07
                    </a>
                    <button type="submit" class=" luxury-button flex-1 md:flex-none bg-white text-indigo-900 shadow-none hover:bg-indigo-50 border-none">
                        COMMIT COLLECTIVE
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
